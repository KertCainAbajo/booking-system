<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        // Redirect based on user role (staff/owner/admin only)
        $user = Auth::user();
        $roleName = $user->role->name;
        
        $dashboardRoute = match($roleName) {
            'it_admin' => 'admin.dashboard',
            'business_owner' => 'owner.dashboard',
            'staff' => 'staff.dashboard',
            default => 'staff.dashboard',
        };

        $this->redirectIntended(default: route($dashboardRoute, absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Logo -->
    <div class="mb-3 sm:mb-4 flex justify-center">
        <img src="{{ asset('images/shop.png') }}" alt="GasMonkey Logo" class="h-20 sm:h-24 md:h-28 w-auto">
    </div>

    <!-- Header -->
    <div class="mb-4 sm:mb-6 text-center">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 leading-tight">
            Welcome to<br/>
            <span class="text-green-800">Dexter Auto Services</span>
        </h2>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 sm:mb-6" :status="session('status')" />

    <form wire:submit="login" class="space-y-4 sm:space-y-5">
        <!-- Email Address -->
        <div>
            <div class="auth-input-wrapper">
                <svg class="auth-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                </svg>
                <input 
                    wire:model="form.email" 
                    id="email" 
                    type="email" 
                    name="email" 
                    placeholder="Email address"
                    class="auth-input"
                    required 
                    autofocus 
                    autocomplete="username"
                />
            </div>
            <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-sm" />
        </div>

        <!-- Password -->
        <div>
            <div class="auth-input-wrapper">
                <svg class="auth-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <input 
                    wire:model="form.password" 
                    id="password" 
                    type="password"
                    name="password" 
                    placeholder="Password"
                    class="auth-input pr-12"
                    required 
                    autocomplete="current-password"
                />
                <button 
                    type="button"
                    id="togglePassword"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors"
                    onclick="
                        const passwordInput = document.getElementById('password');
                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordInput.setAttribute('type', type);
                        this.innerHTML = type === 'password' 
                            ? '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\'></path><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\'></path></svg>'
                            : '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21\'></path></svg>';
                    "
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-sm" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input 
                    wire:model="form.remember" 
                    id="remember" 
                    type="checkbox" 
                    class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-2 focus:ring-gray-800 transition-colors" 
                    name="remember"
                >
                <span class="ml-2 text-sm font-medium text-gray-700">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link" wire:navigate>
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="auth-btn-primary group" wire:loading.attr="disabled">
                <span wire:loading.remove class="flex items-center justify-center">
                    Log In
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </span>
                <span wire:loading class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Logging in...
                </span>
            </button>
        </div>
    </form>
</div>
