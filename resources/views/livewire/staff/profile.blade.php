<div class="space-y-6">
    <!-- Back to Dashboard Button -->
    <div class="mb-4">
        <a href="{{ route('staff.dashboard') }}" wire:navigate class="inline-flex items-center px-6 py-3 bg-garage-forest hover:bg-garage-darkgreen text-white font-bold rounded-lg transition-all border border-garage-neon/30 hover:border-garage-neon/50 service-tag">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            BACK TO DASHBOARD
        </a>
    </div>

    <!-- Profile Header -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-8 border-l-4 border-garage-neon relative overflow-hidden">
        <!-- Carbon Fiber Pattern Overlay -->
        <div class="absolute inset-0 bg-carbon-fiber opacity-50"></div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-4 mb-3">
                <!-- User Icon -->
                <svg class="w-12 h-12 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <h1 class="text-4xl font-bold text-garage-offwhite service-tag tracking-wider">STAFF PROFILE</h1>
            </div>
            <p class="text-garage-steel text-lg ml-16">Manage your personal information</p>
        </div>
        
        <!-- Garage Floor Marking -->
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-white to-transparent opacity-30"></div>
    </div>

    <!-- Profile Information Form -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
        <div class="flex items-center space-x-3 mb-6">
            <svg class="w-6 h-6 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            <h3 class="text-xl font-bold text-garage-offwhite service-tag tracking-wider">PROFILE INFORMATION</h3>
        </div>
        
        <!-- Garage Floor Divider -->
        <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-6"></div>
        
        @if($successMessage)
            <div class="mb-6 p-4 bg-garage-neon/20 border-l-4 border-garage-neon text-white rounded font-semibold">
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
                    class="px-8 py-3 bg-garage-neon hover:bg-garage-neon/80 text-garage-charcoal font-bold rounded-lg transition-all service-tag tracking-wider"
                >
                    SAVE CHANGES
                </button>
            </div>
        </form>
    </div>

    <!-- Logout Section -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-red-500/20">
        <div class="flex items-center space-x-3 mb-6">
            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            <h3 class="text-xl font-bold text-garage-offwhite service-tag tracking-wider">ACCOUNT ACTIONS</h3>
        </div>
        
        <!-- Garage Floor Divider -->
        <div class="h-px bg-gradient-to-r from-transparent via-red-500/30 to-transparent mb-6"></div>
        
        <div class="flex items-center justify-between">
            <div>
                <p class="text-garage-offwhite font-bold mb-1">Sign Out</p>
                <p class="text-sm text-garage-steel">Securely logout from your account</p>
            </div>
            <button 
                wire:click="logout" 
                class="px-8 py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-lg transition-all service-tag tracking-wider"
            >
                LOGOUT
            </button>
        </div>
    </div>
</div>
