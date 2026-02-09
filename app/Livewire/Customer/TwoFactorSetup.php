<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TwoFactorSetup extends Component
{
    public $qrCodeUrl = '';
    public $secret = '';
    public $verificationCode = '';
    public $recoveryCodes = [];
    public $showRecoveryCodes = false;

    public function mount()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($user->google2fa_enabled) {
            return redirect()->route('customer.dashboard');
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
        session()->flash('success', 'Two-Factor Authentication has been enabled successfully!');
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
        return redirect()->route('customer.dashboard');
    }

    public function render()
    {
        return view('livewire.customer.two-factor-setup');
    }
}
