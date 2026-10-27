<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

/**
 * "Sign in with Google" for customers.
 *
 * Owners and super admins must sign in with email + password (and their 2FA code),
 * so Google sign-in never changes anyone's role and never bypasses two-factor.
 */
class GoogleAuthController extends Controller
{
    public function redirectToGoogle(): RedirectResponse
    {
        // Stateful: Socialite stores a random "state" in the session and checks it on return,
        // which stops an attacker from logging a victim into the attacker's account.
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $e) {
            return $this->fail('Your Google sign-in expired or was started in another tab. Please try again from this page.');
        } catch (\Throwable $e) {
            Log::warning('Google sign-in failed', ['error' => $e->getMessage()]);

            return $this->fail('Unable to sign in with Google. Please try again or use your email and password.');
        }

        $email = Str::lower((string) $googleUser->getEmail());
        $raw = $googleUser->getRaw();
        if ($email === '' || ! ($raw['email_verified'] ?? $raw['verified_email'] ?? false)) {
            return $this->fail('Your Google account email is not verified. Please verify it with Google or use email and password.');
        }

        // Match an account already linked to this Google ID first, then by email
        $user = User::with(['role', 'customer'])->where('google_id', $googleUser->getId())->first()
            ?? User::with(['role', 'customer'])->where('email', $email)->first();

        if ($user) {
            if ($user->role?->name !== 'customer') {
                return $this->fail('Owner and admin accounts must sign in with email and password.');
            }

            if ($user->google2fa_enabled) {
                return $this->fail('This account uses two-factor authentication. Please sign in with your email, password and code.');
            }

            if ($user->google_id && $user->google_id !== $googleUser->getId()) {
                return $this->fail('This email is linked to a different Google account.');
            }

            DB::transaction(function () use ($user, $googleUser) {
                if (! $user->google_id) {
                    $user->forceFill(['google_id' => $googleUser->getId()])->save();
                }
                if (! $user->customer) {
                    Customer::create(['user_id' => $user->id]);
                }
            });
        } else {
            $user = DB::transaction(function () use ($googleUser, $email) {
                $user = User::create([
                    'name' => $googleUser->getName() ?: Str::before($email, '@'),
                    'email' => $email,
                    'password' => Hash::make(Str::random(40)), // unusable; they sign in with Google
                    'role_id' => Role::firstOrCreate(['name' => 'customer'])->id,
                    'google_id' => $googleUser->getId(),
                ]);
                $user->forceFill(['email_verified_at' => now()])->save();
                Customer::create(['user_id' => $user->id]);

                return $user;
            });
        }

        Auth::login($user, true);
        request()->session()->regenerate(); // new session ID after login (prevents session fixation)

        Log::info('Google sign-in', ['user_id' => $user->id]);

        return redirect()->route('customer.dashboard');
    }

    protected function fail(string $message): RedirectResponse
    {
        return redirect()->route('login')->withErrors(['form.email' => $message]);
    }
}
