<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/shop.png') }}">

    <title>{{ config('app.name', 'Auto Service Booking') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|rajdhani:600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <style>
        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #0d2818 100%);
            background-attachment: fixed;
        }
        .garage-texture {
            background-image: 
                repeating-linear-gradient(
                    45deg,
                    transparent,
                    transparent 2px,
                    rgba(255,255,255,.02) 2px,
                    rgba(255,255,255,.02) 4px
                ),
                linear-gradient(135deg, #0a0a0a 0%, #0d2818 100%);
        }
        .plate-number {
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            font-weight: bold;
            background: linear-gradient(180deg, #ffffff 0%, #f0f0f0 50%, #ffffff 100%);
            border: 3px solid #1a1a1a;
            border-radius: 4px;
            padding: 4px 12px;
            color: #1a1a1a;
            box-shadow: 0 2px 8px rgba(0,0,0,0.6);
        }
        .service-tag {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            letter-spacing: 1px;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 15px rgba(16, 185, 129, 0.4); }
            50% { box-shadow: 0 0 25px rgba(16, 185, 129, 0.6); }
        }
        .pulse-neon {
            animation: pulse-glow 2s ease-in-out infinite;
        }
    </style>
</head>
<body class="font-sans antialiased garage-texture">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-gradient-to-r from-garage-charcoal to-garage-darkgreen border-b-2 border-garage-neon/20 shadow-garage" x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('customer.dashboard') }}" class="flex items-center space-x-2 text-base sm:text-xl font-bold text-garage-neon hover:text-garage-emerald transition-colors">
                            <!-- Automotive Icon -->
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                            <span class="service-tag hidden sm:inline">MIDNIGHT GARAGE</span>
                            <span class="service-tag sm:hidden">GARAGE</span>
                        </a>
                    </div>
                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('customer.booking') }}" class="px-3 lg:px-4 py-2 text-garage-steel hover:text-garage-neon hover:bg-garage-darkgreen/50 rounded-lg font-medium transition-all duration-200">
                            New Service
                        </a>
                        <a href="{{ route('customer.tracker') }}" class="px-3 lg:px-4 py-2 text-garage-steel hover:text-garage-neon hover:bg-garage-darkgreen/50 rounded-lg font-medium transition-all duration-200">
                            Track
                        </a>
                        <a href="{{ route('customer.history') }}" class="px-3 lg:px-4 py-2 text-garage-steel hover:text-garage-neon hover:bg-garage-darkgreen/50 rounded-lg font-medium transition-all duration-200">
                            History
                        </a>
                        
                        <!-- User Dropdown -->
                        <div class="relative ml-3" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" 
                                    class="flex items-center space-x-2 text-garage-steel hover:text-garage-neon focus:outline-none px-3 py-2 rounded-lg hover:bg-garage-darkgreen/50 transition-all duration-200">
                                <div class="w-8 h-8 bg-gradient-to-br from-garage-forest to-garage-neon rounded-full flex items-center justify-center text-white font-bold ring-2 ring-garage-neon/30">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="font-medium hidden sm:inline">{{ auth()->user()->name }}</span>
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
                                 class="absolute right-0 mt-2 w-56 bg-garage-charcoal rounded-lg shadow-garage py-1 z-50 border border-garage-neon/30"
                                 style="display: none;">
                                <div class="px-4 py-3 border-b border-garage-forest">
                                    <p class="text-sm font-semibold text-garage-offwhite">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-garage-steel truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('customer.profile') }}" class="block px-4 py-2 text-sm text-garage-steel hover:bg-garage-forest hover:text-garage-neon transition-all">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Profile Settings
                                    </div>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-garage-forest/50 hover:text-red-300 transition-all">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Logout
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="flex md:hidden items-center">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-garage-steel hover:text-garage-neon p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Mobile Navigation Menu -->
                <div x-show="mobileMenuOpen" x-transition class="md:hidden pb-4 space-y-2">
                    <a href="{{ route('customer.booking') }}" class="block px-4 py-2 text-garage-steel hover:text-garage-neon hover:bg-garage-darkgreen/50 rounded-lg font-medium">New Service</a>
                    <a href="{{ route('customer.tracker') }}" class="block px-4 py-2 text-garage-steel hover:text-garage-neon hover:bg-garage-darkgreen/50 rounded-lg font-medium">Track</a>
                    <a href="{{ route('customer.history') }}" class="block px-4 py-2 text-garage-steel hover:text-garage-neon hover:bg-garage-darkgreen/50 rounded-lg font-medium">History</a>
                    <div class="border-t border-garage-neon/20 mt-2 pt-2">
                        <div class="px-4 py-2">
                            <p class="text-sm font-semibold text-garage-offwhite">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-garage-steel truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('customer.profile') }}" class="block px-4 py-2 text-garage-steel hover:text-garage-neon hover:bg-garage-forest rounded-lg">Profile Settings</a>
                        <form method="POST" action="{{ route('logout') }}" class="px-4">
                            @csrf
                            <button type="submit" class="w-full text-left py-2 text-red-400 hover:text-red-300">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-900/50 border-l-4 border-red-500 p-4 rounded-lg shadow-lg backdrop-blur-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-red-200 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <x-success-modal />

        <!-- Page Content -->
        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
    @livewireScripts
</body>
</html>
