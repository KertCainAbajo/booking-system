<?php

namespace App\Livewire\BusinessOwner;

use App\Livewire\Actions\Logout;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.owner')]
class Profile extends Component
{
    public $name;
    public $email;
    public $phone;
    public $successMessage = '';
    
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';
    public $passwordSuccessMessage = '';

    public function mount()
    {
        /** @var User $user */
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
    }

    public function updateProfile()
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->fill($validated);
        $user->save();

        $this->successMessage = 'Profile updated successfully!';
        
        // Clear success message after 3 seconds
        $this->dispatch('profile-updated');
    }

    public function updatePassword()
    {
        $validated = $this->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');
        $this->passwordSuccessMessage = 'Password updated successfully!';
        $this->dispatch('password-updated');
    }

    public function logout(Logout $logout)
    {
        $logout();
        return redirect()->route('guest.home');
    }

    public function render()
    {
        return view('livewire.business-owner.profile');
    }
}
