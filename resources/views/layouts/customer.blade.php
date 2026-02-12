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
    <div class="min-h-screen" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 min-h-full bg-gradient-to-b from-garage-charcoal to-garage-darkgreen border-r border-garage-neon/20 shadow-garage transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col"
               :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            
            <!-- Sidebar Header -->
            <div class="relative flex items-center justify-center h-16 px-4 border-b border-garage-neon/20 flex-shrink-0">
                <a href="{{ route('customer.dashboard') }}" class="flex items-center space-x-2 text-xl font-bold text-white hover:text-garage-neon transition-colors service-tag">
                    <img src="{{ asset('images/shop.png') }}" alt="Dexter Auto Services" class="h-10 w-10 object-contain brightness-0 invert">
                    <span class="whitespace-nowrap">DEXTER</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-garage-neon absolute right-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('customer.dashboard') }}" 
                   class="flex items-center px-4 py-3 text-white hover:text-garage-neon hover:bg-garage-forest rounded-lg font-semibold transition-colors service-tag {{ request()->routeIs('customer.dashboard') ? 'bg-garage-forest text-garage-neon' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    DASHBOARD
                </a>
                <a href="{{ route('customer.book') }}" 
                   class="flex items-center px-4 py-3 text-white hover:text-garage-neon hover:bg-garage-forest rounded-lg font-semibold transition-colors service-tag {{ request()->routeIs('customer.book') ? 'bg-garage-forest text-garage-neon' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    NEW SERVICE
                </a>
                <a href="{{ route('customer.tracker') }}" 
                   class="flex items-center px-4 py-3 text-white hover:text-garage-neon hover:bg-garage-forest rounded-lg font-semibold transition-colors service-tag {{ request()->routeIs('customer.tracker') ? 'bg-garage-forest text-garage-neon' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    TRACK
                </a>
                <a href="{{ route('customer.history') }}" 
                   class="flex items-center px-4 py-3 text-white hover:text-garage-neon hover:bg-garage-forest rounded-lg font-semibold transition-colors service-tag {{ request()->routeIs('customer.history') ? 'bg-garage-forest text-garage-neon' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    HISTORY
                </a>
            </nav>

            <!-- User Section -->
            <div class="flex-shrink-0 p-4 border-t border-garage-neon/20 bg-garage-charcoal/50">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false"
                            class="flex items-center w-full px-4 py-3 text-white hover:text-garage-neon hover:bg-garage-forest rounded-lg transition-colors">
                        <div class="w-8 h-8 bg-gradient-to-br from-garage-neon to-garage-emerald rounded-full flex items-center justify-center text-garage-black font-bold shadow-neon-green flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="ml-3 text-left overflow-hidden">
                            <p class="text-sm font-bold text-white truncate service-tag">{{ strtoupper(auth()->user()->name) }}</p>
                            <p class="text-xs text-white/70 truncate">Customer</p>
                        </div>
                        <svg class="w-4 h-4 ml-auto" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                         class="absolute bottom-full left-4 right-4 mb-2 bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage py-2 border border-garage-neon/30"
                         style="display: none;">
                        <a href="{{ route('customer.profile') }}" class="block px-4 py-2 text-sm text-white hover:bg-garage-forest hover:text-garage-neon transition-colors">
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
        </aside>

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
             style="display: none;"></div>

        <!-- Main Content -->
        <div class="lg:pl-64">
            <!-- Mobile Header -->
            <div class="lg:hidden sticky top-0 z-30 flex items-center justify-between h-16 px-4 bg-gradient-to-r from-garage-charcoal to-garage-darkgreen border-b border-garage-neon/20 shadow-garage">
                <button @click="sidebarOpen = true" class="text-white hover:text-garage-neon p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <span class="text-lg font-bold text-white service-tag">DEXTER AUTO</span>
                <div class="w-10"></div> <!-- Spacer for centering -->
            </div>

            <!-- Flash Messages -->
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mx-4 mt-4">
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

            <!-- Page Content -->
            <main class="py-6 sm:py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>
