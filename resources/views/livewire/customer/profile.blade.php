<div class="space-y-6">
    <!-- Profile header -->
    <section class="cp-card relative overflow-hidden p-6 sm:p-8">
        <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-emerald-500/15 blur-3xl" aria-hidden="true"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center gap-5">
            <span class="w-20 h-20 flex-shrink-0 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-3xl font-extrabold text-gray-950 shadow-lg shadow-emerald-900/50">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </span>
            <div class="min-w-0">
                <p class="text-sm font-medium text-emerald-300">My Profile</p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight text-white truncate">{{ auth()->user()->name }}</h1>
                <p class="mt-1 text-gray-400 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Personal details -->
        <section class="cp-card p-6 sm:p-7">
            <div class="flex items-center gap-3 mb-6">
                <span class="w-10 h-10 rounded-xl bg-emerald-400/10 text-emerald-300 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </span>
                <div>
                    <h2 class="text-lg font-bold text-white">Personal details</h2>
                    <p class="text-sm text-gray-400">How we contact you about your car.</p>
                </div>
            </div>

            @if($successMessage)
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                     class="mb-5 flex items-center gap-3 rounded-xl bg-emerald-400/10 ring-1 ring-emerald-400/30 p-3.5 text-emerald-200">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ $successMessage }}</span>
                </div>
            @endif

            <form wire:submit.prevent="updateProfile" class="space-y-5">
                <div>
                    <label for="name" class="cp-label">Full name <span class="text-rose-400">*</span></label>
                    <input type="text" id="name" wire:model="name" class="cp-input" required autocomplete="name">
                    @error('name') <span class="cp-error">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="email" class="cp-label">Email address <span class="text-rose-400">*</span></label>
                    <input type="email" id="email" wire:model="email" class="cp-input" required autocomplete="email">
                    @error('email') <span class="cp-error">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="phone" class="cp-label">Phone number</label>
                    <input type="text" id="phone" wire:model="phone" class="cp-input" placeholder="e.g. 0917 123 4567" autocomplete="tel">
                    @error('phone') <span class="cp-error">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="cp-btn-primary w-full sm:w-auto" wire:loading.attr="disabled" wire:target="updateProfile">
                    <span wire:loading.remove wire:target="updateProfile">Save changes</span>
                    <span wire:loading wire:target="updateProfile">Saving...</span>
                </button>
            </form>
        </section>

        <!-- Password -->
        <section class="cp-card p-6 sm:p-7">
            <div class="flex items-center gap-3 mb-6">
                <span class="w-10 h-10 rounded-xl bg-sky-400/10 text-sky-300 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </span>
                <div>
                    <h2 class="text-lg font-bold text-white">Change password</h2>
                    <p class="text-sm text-gray-400">Use at least 8 characters.</p>
                </div>
            </div>

            @if($passwordSuccessMessage)
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                     class="mb-5 flex items-center gap-3 rounded-xl bg-emerald-400/10 ring-1 ring-emerald-400/30 p-3.5 text-emerald-200">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ $passwordSuccessMessage }}</span>
                </div>
            @endif

            <form wire:submit.prevent="updatePassword" class="space-y-5">
                <div>
                    <label for="current_password" class="cp-label">Current password <span class="text-rose-400">*</span></label>
                    <input type="password" id="current_password" wire:model="current_password" class="cp-input" required autocomplete="current-password">
                    @error('current_password') <span class="cp-error">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="password" class="cp-label">New password <span class="text-rose-400">*</span></label>
                    <input type="password" id="password" wire:model="password" class="cp-input" required autocomplete="new-password">
                    @error('password') <span class="cp-error">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="cp-label">Confirm new password <span class="text-rose-400">*</span></label>
                    <input type="password" id="password_confirmation" wire:model="password_confirmation" class="cp-input" required autocomplete="new-password">
                    @error('password_confirmation') <span class="cp-error">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="cp-btn-ghost w-full sm:w-auto" wire:loading.attr="disabled" wire:target="updatePassword">
                    <span wire:loading.remove wire:target="updatePassword">Update password</span>
                    <span wire:loading wire:target="updatePassword">Updating...</span>
                </button>
            </form>
        </section>
    </div>

    <!-- Sign out -->
    <section class="cp-card p-6 sm:p-7 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="w-10 h-10 rounded-xl bg-rose-400/10 text-rose-300 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </span>
            <div>
                <h2 class="text-lg font-bold text-white">Sign out</h2>
                <p class="text-sm text-gray-400">Log out of your account on this device.</p>
            </div>
        </div>
        <button wire:click="logout" class="cp-btn-danger">Log out</button>
    </section>
</div>
