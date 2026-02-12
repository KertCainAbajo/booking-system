<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;
use PragmaRX\Google2FA\Google2FA;

class UnifiedLoginForm extends Form
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    #[Validate('nullable|string|size:6')]
    public string $otp_code = '';

    public bool $requires_2fa = false;
    public ?int $pending_user_id = null;
    public ?string $user_type = null;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // First attempt: Check credentials
        $credentials = $this->only(['email', 'password']);

        if (! Auth::validate($credentials)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }

        // Get the user
        $user = \App\Models\User::where('email', $this->email)->first();

        if (!$user) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }

        // Load relationships
        $user->load('role', 'customer');

        // Determine user type
        if ($user->customer) {
            $this->user_type = 'customer';
        } else {
            $this->user_type = 'staff';
        }

        // Check if customer has 2FA enabled
        if ($this->user_type === 'customer' && $user->google2fa_enabled && $user->google2fa_secret) {
            $this->requires_2fa = true;
            $this->pending_user_id = $user->id;

            // If OTP code is provided, verify it
            if ($this->otp_code) {
                $this->verify2FA($user);
            } else {
                // Return early to show 2FA input
                return;
            }
        } else {
            // No 2FA, proceed with login
            Auth::login($user, $this->remember);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Verify 2FA code
     */
    protected function verify2FA($user): void
    {
        $google2fa = new Google2FA();
        
        $valid = $google2fa->verifyKey($user->google2fa_secret, $this->otp_code);

        if (!$valid) {
            // Check if it's a recovery code
            $recoveryCodes = json_decode($user->recovery_codes, true) ?? [];
            
            if (in_array($this->otp_code, $recoveryCodes)) {
                // Remove used recovery code
                $recoveryCodes = array_diff($recoveryCodes, [$this->otp_code]);
                $user->update(['recovery_codes' => json_encode(array_values($recoveryCodes))]);
                
                Auth::login($user, $this->remember);
                return;
            }

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.otp_code' => 'The authentication code is invalid.',
            ]);
        }

        // Valid 2FA code, log in
        Auth::login($user, $this->remember);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
