<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TwoFactorSetup extends Component
{
    #[\Livewire\Attributes\Locked]
    public $qrCodeUrl = '';
    #[\Livewire\Attributes\Locked]
    public $secret = '';
    public $verificationCode = '';
    public $recoveryCodes = [];
    public $showRecoveryCodes = false;

    public function mount()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Setup is open to every role, so go to the role-aware dashboard redirect
        if ($user->google2fa_enabled) {
            return redirect()->route('dashboard');
        }

        $this->generateSecret();
    }

    public function generateSecret()
    {
        $google2fa = new Google2FA();
        $this->secret = $google2fa->generateSecretKey();
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $companyName = config('app.name', 'Dexter Auto Services');
        
        $this->qrCodeUrl = $google2fa->getQRCodeUrl(
            $companyName,
            $user->email,
            $this->secret
        );
    }

    public function enable2FA()
    {
        $this->validate([
            'verificationCode' => 'required|string|size:6',
        ]);

        $google2fa = new Google2FA();
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $valid = $google2fa->verifyKey($this->secret, $this->verificationCode);

        if (!$valid) {
            $this->addError('verificationCode', 'The verification code is invalid. Please try again.');
            return;
        }

        // Generate recovery codes
        $this->recoveryCodes = $this->generateRecoveryCodes();

        // Save to database
        $user->update([
            'google2fa_secret' => $this->secret,
            'google2fa_enabled' => true,
            'recovery_codes' => json_encode($this->recoveryCodes),
        ]);

        $this->showRecoveryCodes = true;
        $this->dispatch('toast', type: 'success', message: 'Two-factor sign-in is on. Save your recovery codes before you continue.');
    }

    protected function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = Str::random(10);
        }
        return $codes;
    }

    public function finishSetup()
    {
        session()->flash('toast', ['type' => 'success', 'message' => "Two-factor sign-in is set up. You'll be asked for a code each time you sign in."]);

        // Straight to the right dashboard: the extra /dashboard redirect would use up the message
        return redirect()->route(match (Auth::user()->role?->name) {
            'super_admin' => 'admin.dashboard',
            'business_owner' => 'owner.dashboard',
            default => 'customer.dashboard',
        });
    }

    public function render()
    {
        return view('livewire.customer.two-factor-setup');
    }
}
