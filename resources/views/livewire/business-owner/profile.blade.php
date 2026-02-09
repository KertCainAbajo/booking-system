<div class="space-y-6">
    <!-- Back to Dashboard Button -->
    <div class="mb-4">
        <a href="{{ route('owner.dashboard') }}" wire:navigate class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-garage-forest hover:bg-garage-darkgreen text-white text-sm sm:text-base font-bold rounded-lg transition-all border border-garage-neon/30 hover:border-garage-neon/50 service-tag">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="hidden sm:inline">BACK TO DASHBOARD</span>
            <span class="sm:hidden">BACK</span>
        </a>
    </div>

    <!-- Profile Header -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 md:p-8 border-l-4 border-garage-neon relative overflow-hidden">
        <!-- Carbon Fiber Pattern Overlay -->
        <div class="absolute inset-0 bg-carbon-fiber opacity-50"></div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-3 sm:space-x-4 mb-3">
                <!-- User Icon -->
                <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-garage-offwhite service-tag tracking-wider">BUSINESS OWNER PROFILE</h1>
            </div>
            <p class="text-garage-steel text-sm sm:text-base md:text-lg ml-11 sm:ml-14 md:ml-16">Manage your personal information</p>
        </div>
        
        <!-- Garage Floor Marking -->
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-white to-transparent opacity-30"></div>
    </div>

    <!-- Profile Information Form -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-garage-neon/20">
        <div class="flex items-center space-x-3 mb-4 sm:mb-6">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            <h3 class="text-lg sm:text-xl font-bold text-garage-offwhite service-tag tracking-wider">PROFILE INFORMATION</h3>
        </div>
        
        <!-- Garage Floor Divider -->
        <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-6"></div>
        
        @if($successMessage)
            <div x-data="{ show: true }" 
                 x-show="show"
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transition ease-out duration-400"
                 x-transition:enter-start="opacity-0 transform -translate-x-8 scale-95"
                 x-transition:enter-end="opacity-100 transform translate-x-0 scale-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform translate-x-0"
                 x-transition:leave-end="opacity-0 transform translate-x-8"
                 class="mb-6 p-4 bg-garage-neon/20 border-l-4 border-garage-neon text-white rounded font-semibold">
                {{ $successMessage }}
            </div>
        @endif

        <form wire:submit.prevent="updateProfile" class="space-y-5">
            <div>
                <label for="name" class="block text-sm font-bold text-garage-neon mb-2 service-tag tracking-wider">
                    NAME <span class="text-red-400">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    wire:model="name" 
                    class="w-full px-4 py-3 bg-garage-forest border border-garage-neon/30 rounded-lg text-garage-offwhite placeholder-garage-steel focus:ring-2 focus:ring-garage-neon focus:border-garage-neon transition-all"
                    required
                >
                @error('name') 
                    <span class="text-red-400 text-sm font-semibold mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-bold text-garage-neon mb-2 service-tag tracking-wider">
                    EMAIL <span class="text-red-400">*</span>
                </label>
                <input 
                    type="email" 
                    id="email" 
                    wire:model="email" 
                    class="w-full px-4 py-3 bg-garage-forest border border-garage-neon/30 rounded-lg text-garage-offwhite placeholder-garage-steel focus:ring-2 focus:ring-garage-neon focus:border-garage-neon transition-all"
                    required
                >
                @error('email') 
                    <span class="text-red-400 text-sm font-semibold mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-bold text-garage-neon mb-2 service-tag tracking-wider">
                    PHONE NUMBER
                </label>
                <input 
                    type="text" 
                    id="phone" 
                    wire:model="phone" 
                    class="w-full px-4 py-3 bg-garage-forest border border-garage-neon/30 rounded-lg text-garage-offwhite placeholder-garage-steel focus:ring-2 focus:ring-garage-neon focus:border-garage-neon transition-all"
                    placeholder="Enter your phone number"
                >
                @error('phone') 
                    <span class="text-red-400 text-sm font-semibold mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            <div class="pt-4">
                <button 
                    type="submit" 
                    class="w-full sm:w-auto px-6 sm:px-8 py-2.5 sm:py-3 bg-garage-neon hover:bg-garage-neon/80 text-garage-charcoal text-sm sm:text-base font-bold rounded-lg transition-all service-tag tracking-wider"
                >
                    SAVE CHANGES
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password Section -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-garage-neon/20">
        <div class="flex items-center space-x-3 mb-4 sm:mb-6">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            <h3 class="text-lg sm:text-xl font-bold text-garage-offwhite service-tag tracking-wider">CHANGE PASSWORD</h3>
        </div>
        
        <!-- Garage Floor Divider -->
        <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-6"></div>
        
        @if($passwordSuccessMessage)
            <div x-data="{ show: true }" 
                 x-show="show"
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transition ease-out duration-400"
                 x-transition:enter-start="opacity-0 transform -translate-x-8 scale-95"
                 x-transition:enter-end="opacity-100 transform translate-x-0 scale-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform translate-x-0"
                 x-transition:leave-end="opacity-0 transform translate-x-8"
                 class="mb-6 p-4 bg-garage-neon/20 border-l-4 border-garage-neon text-white rounded font-semibold">
                {{ $passwordSuccessMessage }}
            </div>
        @endif

        <form wire:submit.prevent="updatePassword" class="space-y-5">
            <div>
                <label for="current_password" class="block text-sm font-bold text-garage-neon mb-2 service-tag tracking-wider">
                    CURRENT PASSWORD <span class="text-red-400">*</span>
                </label>
                <input 
                    type="password" 
                    id="current_password" 
                    wire:model="current_password" 
                    class="w-full px-4 py-3 bg-garage-forest border border-garage-neon/30 rounded-lg text-garage-offwhite placeholder-garage-steel focus:ring-2 focus:ring-garage-neon focus:border-garage-neon transition-all"
                    required
                    autocomplete="current-password"
                >
                @error('current_password') 
                    <span class="text-red-400 text-sm font-semibold mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-bold text-garage-neon mb-2 service-tag tracking-wider">
                    NEW PASSWORD <span class="text-red-400">*</span>
                </label>
                <input 
                    type="password" 
                    id="password" 
                    wire:model="password" 
                    class="w-full px-4 py-3 bg-garage-forest border border-garage-neon/30 rounded-lg text-garage-offwhite placeholder-garage-steel focus:ring-2 focus:ring-garage-neon focus:border-garage-neon transition-all"
                    required
                    autocomplete="new-password"
                >
                @error('password') 
                    <span class="text-red-400 text-sm font-semibold mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-garage-neon mb-2 service-tag tracking-wider">
                    CONFIRM PASSWORD <span class="text-red-400">*</span>
                </label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    wire:model="password_confirmation" 
                    class="w-full px-4 py-3 bg-garage-forest border border-garage-neon/30 rounded-lg text-garage-offwhite placeholder-garage-steel focus:ring-2 focus:ring-garage-neon focus:border-garage-neon transition-all"
                    required
                    autocomplete="new-password"
                >
                @error('password_confirmation') 
                    <span class="text-red-400 text-sm font-semibold mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            <div class="pt-4">
                <button 
                    type="submit" 
                    class="w-full sm:w-auto px-6 sm:px-8 py-2.5 sm:py-3 bg-garage-neon hover:bg-garage-neon/80 text-garage-charcoal text-sm sm:text-base font-bold rounded-lg transition-all service-tag tracking-wider"
                >
                    UPDATE PASSWORD
                </button>
            </div>
        </form>
    </div>

    <!-- Logout Section -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-red-500/20">
        <div class="flex items-center space-x-3 mb-4 sm:mb-6">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            <h3 class="text-lg sm:text-xl font-bold text-garage-offwhite service-tag tracking-wider">ACCOUNT ACTIONS</h3>
        </div>
        
        <!-- Garage Floor Divider -->
        <div class="h-px bg-gradient-to-r from-transparent via-red-500/30 to-transparent mb-4 sm:mb-6"></div>
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
            <div>
                <p class="text-garage-offwhite font-bold mb-1">Sign Out</p>
                <p class="text-xs sm:text-sm text-garage-steel">Securely logout from your account</p>
            </div>
            <button 
                wire:click="logout" 
                class="w-full sm:w-auto px-6 sm:px-8 py-2.5 sm:py-3 bg-red-500 hover:bg-red-600 text-white text-sm sm:text-base font-bold rounded-lg transition-all service-tag tracking-wider"
            >
                LOGOUT
            </button>
        </div>
    </div>
</div>
