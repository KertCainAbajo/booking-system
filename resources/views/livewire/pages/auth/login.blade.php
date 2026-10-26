<?php

use App\Livewire\Forms\UnifiedLoginForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public UnifiedLoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        // If 2FA is required, stay on page
        if ($this->form->requires_2fa && !Auth::check()) {
            return;
        }

        Session::regenerate();

        // Redirect based on user type and role
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('role', 'customer');

        // Check if user is a customer
        if ($user->customer) {
            $this->redirect(route('customer.dashboard', absolute: false), navigate: true);
            return;
        }

        // Staff/Admin/Business Owner redirect
        $roleName = $user->role?->name ?? 'staff';

        $dashboardRoute = match($roleName) {
            'it_admin' => 'admin.dashboard',
            'business_owner' => 'owner.dashboard',
            'staff' => 'staff.dashboard',
            default => 'staff.dashboard',
        };

        $this->redirect(route($dashboardRoute, absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold tracking-tight text-gray-900">Welcome back</h2>
        <p class="mt-2 text-base text-gray-600">Sign in to book a service or check on your car.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    @if (!$form->requires_2fa)
        <!-- Google first: the quickest way in -->
        <a href="{{ route('auth.google') }}" class="auth-btn-secondary gap-3">
            <svg class="w-5 h-5" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Continue with Google
        </a>

        <div class="my-7 flex items-center gap-4" aria-hidden="true">
            <div class="h-px flex-1 bg-gray-200"></div>
            <span class="text-sm text-gray-500">or sign in with email</span>
            <div class="h-px flex-1 bg-gray-200"></div>
        </div>
    @endif

    <form wire:submit="login" class="space-y-5">
        @if (!$form->requires_2fa)
            <!-- Email Address -->
            <div>
                <label for="email" class="auth-label">Email address</label>
                <div class="auth-input-wrapper">
                    <input
                        wire:model="form.email"
                        id="email"
                        type="email"
                        name="email"
                        placeholder="you@example.com"
                        class="auth-input"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <svg class="auth-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div x-data="{ show: false }">
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="auth-label !mb-0">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="auth-link" wire:navigate>Forgot password?</a>
                    @endif
                </div>
                <div class="auth-input-wrapper">
                    <input
                        wire:model="form.password"
                        id="password"
                        type="password"
                        :type="show ? 'text' : 'password'"
                        name="password"
                        placeholder="Your password"
                        class="auth-input !pr-20"
                        required
                        autocomplete="current-password"
                    />
                    <svg class="auth-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <button
                        type="button"
                        @click="show = !show"
                        class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-lg text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-200/70 focus:outline-none focus-visible:ring-4 focus-visible:ring-emerald-600/20 transition"
                        :aria-pressed="show.toString()"
                        x-text="show ? 'Hide' : 'Show'"
                    >Show</button>
                </div>
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <label for="remember" class="flex items-center gap-3 cursor-pointer select-none w-fit">
                <input
                    wire:model="form.remember"
                    id="remember"
                    type="checkbox"
                    class="w-5 h-5 rounded-md border-gray-300 text-emerald-600 focus:ring-4 focus:ring-emerald-600/20"
                    name="remember"
                >
                <span class="text-[15px] text-gray-700">Keep me signed in</span>
            </label>
        @else
            <!-- 2FA Code Input -->
            <div class="rounded-2xl bg-emerald-50 ring-1 ring-emerald-200 p-4 flex gap-3">
                <svg class="w-6 h-6 text-emerald-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <div>
                    <p class="font-semibold text-gray-900">Security check</p>
                    <p class="text-[15px] text-gray-700 mt-0.5">Enter the 6-digit code from your Google Authenticator app.</p>
                </div>
            </div>

            <div>
                <label for="otp_code" class="auth-label">Verification code</label>
                <input
                    wire:model="form.otp_code"
                    id="otp_code"
                    type="text"
                    name="otp_code"
                    placeholder="000000"
                    inputmode="numeric"
                    class="auth-input !pl-4 !h-14 text-center !text-2xl tracking-[0.5em] font-mono"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    required
                    autofocus
                    autocomplete="one-time-code"
                />
                <x-input-error :messages="$errors->get('form.otp_code')" class="mt-2" />
                <p class="text-sm text-gray-500 mt-2">Lost your phone? Enter a recovery code instead.</p>
            </div>
        @endif

        <!-- Submit Button -->
        <button type="submit" class="auth-btn-primary group" wire:loading.attr="disabled">
            <span wire:loading.remove class="flex items-center justify-center gap-2">
                {{ $form->requires_2fa ? 'Verify & sign in' : 'Sign in' }}
                <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </span>
            <span wire:loading class="flex items-center justify-center gap-3">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ $form->requires_2fa ? 'Verifying...' : 'Signing in...' }}
            </span>
        </button>

        @if ($form->requires_2fa)
            <button type="button" wire:click="$set('form.requires_2fa', false)" class="w-full text-[15px] font-semibold text-gray-600 hover:text-gray-900">
                ← Back to sign in
            </button>
        @endif
    </form>

    <!-- Register -->
    <p class="mt-8 text-center text-[15px] text-gray-600">
        New to Dexter Auto?
        <a href="{{ route('register') }}" class="auth-link" wire:navigate>Create an account</a>
    </p>
</div>
