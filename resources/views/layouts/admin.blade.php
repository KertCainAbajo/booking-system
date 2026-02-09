<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Auto Service Booking') }} - Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gradient-to-br from-garage-black via-garage-charcoal to-garage-darkgreen">
    <div class="min-h-screen" x-data="{ mobileMenuOpen: false }">
        <nav class="bg-gradient-to-r from-garage-charcoal to-garage-darkgreen shadow-garage border-b border-garage-neon/20 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center flex-shrink-0">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 sm:space-x-3 text-sm sm:text-base md:text-xl font-bold text-white hover:text-garage-neon transition-colors service-tag">
                            <!-- Shop Logo -->
                            <img src="{{ asset('images/shop.png') }}" alt="Dexter Auto Services" class="h-8 w-8 sm:h-10 sm:w-10 object-contain brightness-0 invert flex-shrink-0">
                            <span class="hidden lg:inline whitespace-nowrap">DEXTER AUTO SERVICES</span>
                            <span class="hidden sm:inline lg:hidden whitespace-nowrap">DEXTER AUTO</span>
                            <span class="sm:hidden whitespace-nowrap">DEXTER</span>
                        </a>
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="flex items-center md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" 
                                class="inline-flex items-center justify-center p-2 rounded-lg text-garage-steel hover:text-garage-neon hover:bg-garage-forest focus:outline-none focus:ring-2 focus:ring-inset focus:ring-garage-neon transition-colors">
                            <svg class="h-6 w-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg class="h-6 w-6" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="hidden md:flex items-center space-x-2 lg:space-x-4 xl:space-x-6 flex-shrink-0 overflow-x-auto">
                        <a href="{{ route('admin.dashboard') }}" class="text-garage-steel hover:text-garage-neon font-semibold transition-colors service-tag whitespace-nowrap {{ request()->routeIs('admin.dashboard') ? 'text-garage-neon' : '' }}">DASHBOARD</a>
                        <a href="{{ route('admin.users') }}" class="text-garage-steel hover:text-garage-neon font-semibold transition-colors service-tag whitespace-nowrap {{ request()->routeIs('admin.users') ? 'text-garage-neon' : '' }}">USERS</a>
                        <a href="{{ route('admin.services') }}" class="text-garage-steel hover:text-garage-neon font-semibold transition-colors service-tag whitespace-nowrap {{ request()->routeIs('admin.services') ? 'text-garage-neon' : '' }}">SERVICES</a>
                        <a href="{{ route('admin.bookings') }}" class="text-garage-steel hover:text-garage-neon font-semibold transition-colors service-tag whitespace-nowrap {{ request()->routeIs('admin.bookings') ? 'text-garage-neon' : '' }}">BOOKINGS</a>
                        <a href="{{ route('admin.monitoring') }}" class="text-garage-steel hover:text-garage-neon font-semibold transition-colors service-tag whitespace-nowrap {{ request()->routeIs('admin.monitoring') ? 'text-garage-neon' : '' }}">MONITORING</a>
                        
                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" 
                                    class="flex items-center space-x-1 lg:space-x-2 text-garage-offwhite hover:text-garage-neon focus:outline-none transition-colors flex-shrink-0">
                                <div class="w-8 h-8 bg-gradient-to-br from-garage-neon to-garage-emerald rounded-full flex items-center justify-center text-garage-black font-bold shadow-neon-green flex-shrink-0">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold service-tag hidden xl:inline truncate max-w-[120px]">{{ strtoupper(auth()->user()->name) }}</span>
                                <svg class="w-4 h-4" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage py-2 z-50 border border-garage-neon/30"
                                 style="display: none;">
                                <div class="px-4 py-3 border-b border-garage-neon/20">
                                    <p class="text-sm font-bold text-garage-offwhite service-tag">{{ strtoupper(auth()->user()->name) }}</p>
                                    <p class="text-xs text-garage-steel">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-garage-steel hover:bg-garage-forest hover:text-garage-neon transition-colors">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="service-tag font-semibold">PROFILE SETTINGS</span>
                                    </div>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-garage-forest hover:text-red-300 transition-colors">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            <span class="service-tag font-semibold">LOGOUT</span>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu panel -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 @click.away="mobileMenuOpen = false"
                 class="md:hidden border-t border-garage-neon/20 bg-gradient-to-br from-garage-charcoal to-garage-darkgreen"
                 style="display: none;">
                <div class="px-4 pt-2 pb-3 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="block px-3 py-2 rounded-lg text-sm font-semibold service-tag transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-garage-forest text-garage-neon' : 'text-garage-steel hover:bg-garage-forest hover:text-garage-neon' }}">
                        DASHBOARD
                    </a>
                    <a href="{{ route('admin.users') }}" 
                       class="block px-3 py-2 rounded-lg text-sm font-semibold service-tag transition-colors {{ request()->routeIs('admin.users') ? 'bg-garage-forest text-garage-neon' : 'text-garage-steel hover:bg-garage-forest hover:text-garage-neon' }}">
                        USERS
                    </a>
                    <a href="{{ route('admin.services') }}" 
                       class="block px-3 py-2 rounded-lg text-sm font-semibold service-tag transition-colors {{ request()->routeIs('admin.services') ? 'bg-garage-forest text-garage-neon' : 'text-garage-steel hover:bg-garage-forest hover:text-garage-neon' }}">
                        SERVICES
                    </a>
                    <a href="{{ route('admin.bookings') }}" 
                       class="block px-3 py-2 rounded-lg text-sm font-semibold service-tag transition-colors {{ request()->routeIs('admin.bookings') ? 'bg-garage-forest text-garage-neon' : 'text-garage-steel hover:bg-garage-forest hover:text-garage-neon' }}">
                        BOOKINGS
                    </a>
                    <a href="{{ route('admin.monitoring') }}" 
                       class="block px-3 py-2 rounded-lg text-sm font-semibold service-tag transition-colors {{ request()->routeIs('admin.monitoring') ? 'bg-garage-forest text-garage-neon' : 'text-garage-steel hover:bg-garage-forest hover:text-garage-neon' }}">
                        MONITORING
                    </a>
                </div>
                
                <!-- Mobile user menu -->
                <div class="pt-4 pb-3 border-t border-garage-neon/20">
                    <div class="flex items-center px-4 mb-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-br from-garage-neon to-garage-emerald rounded-full flex items-center justify-center text-garage-black font-bold shadow-neon-green">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-bold text-garage-offwhite service-tag">{{ strtoupper(auth()->user()->name) }}</div>
                            <div class="text-xs text-garage-steel">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <div class="px-4 space-y-1">
                        <a href="{{ route('admin.profile') }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-sm font-semibold service-tag text-garage-steel hover:bg-garage-forest hover:text-garage-neon transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            PROFILE SETTINGS
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center px-3 py-2 rounded-lg text-sm font-semibold service-tag text-red-400 hover:bg-garage-forest hover:text-red-300 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                LOGOUT
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
                <div class="bg-gradient-to-r from-red-900/50 to-red-800/50 border-l-4 border-red-500 p-4 rounded-lg shadow-garage backdrop-blur-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-red-200 font-semibold service-tag">{{ strtoupper(session('error')) }}</p>
                    </div>
                </div>
            </div>
        @endif

        <x-success-modal />

        <main class="py-4 sm:py-6 md:py-8 lg:py-12">
            <div class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
    @livewireScripts
</body>
</html>
