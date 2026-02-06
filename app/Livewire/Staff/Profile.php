<?php

namespace App\Livewire\Staff;

use App\Livewire\Actions\Logout;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.staff')]
class Profile extends Component
{
    public $name;
    public $email;
    public $phone;
    public $successMessage = '';

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

    public function logout(Logout $logout)
    {
        $logout();
        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.staff.profile');
    }
}
