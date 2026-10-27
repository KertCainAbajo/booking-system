<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="vapid-public-key" content="{{ config('webpush.vapid.public_key') }}" data-signed-in>
    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#090c0b">
    <link rel="apple-touch-icon" href="{{ asset('images/shop.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="icon" type="image/png" href="{{ asset('images/shop.png') }}">

    <title>{{ config('app.name', 'Auto Service Booking') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|rajdhani:600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
            background-color: #090c0b;
            background-image:
                radial-gradient(60rem 40rem at -10% -10%, rgba(16, 185, 129, 0.12), transparent 60%),
                radial-gradient(50rem 40rem at 110% 110%, rgba(16, 185, 129, 0.08), transparent 60%);
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
            border: 2px solid #1a1a1a;
            border-radius: 4px;
            padding: 2px 10px;
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
<body class="antialiased text-gray-100">
    @php
        $navItems = [
            ['route' => 'customer.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['route' => 'customer.book', 'label' => 'Book a Service', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['route' => 'customer.tracker', 'label' => 'Track My Car', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ['route' => 'customer.history', 'label' => 'Service History', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['route' => 'customer.profile', 'label' => 'My Profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ];
    @endphp

    <div class="min-h-screen" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-72 flex flex-col bg-[#0c100f]/95 backdrop-blur-xl border-r border-white/[0.06] transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0"
               :class="{ '!translate-x-0': sidebarOpen }">

            <!-- Brand -->
            <div class="flex items-center justify-between h-20 px-6 flex-shrink-0">
                <a href="{{ route('customer.dashboard') }}" wire:navigate class="flex items-center gap-3">
                    <span class="w-11 h-11 rounded-xl bg-white p-1 shadow-lg shadow-emerald-900/40">
                        <img src="{{ asset('images/shop.png') }}" alt="Dexter Auto Services" class="w-full h-full object-contain">
                    </span>
                    <span class="leading-tight">
                        <span class="block text-base font-bold text-white">Dexter Auto</span>
                        <span class="block text-xs text-gray-400">Customer portal</span>
                    </span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden p-2 -mr-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5" aria-label="Close menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 pb-6 overflow-y-auto">
                <p class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-gray-500">Menu</p>
                <ul class="space-y-1">
                    @foreach ($navItems as $item)
                        @php $active = request()->routeIs($item['route']); @endphp
                        <li>
                            <a href="{{ route($item['route']) }}" wire:navigate
                               @class([
                                   'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-[15px] font-medium transition-colors',
                                   'bg-emerald-500/10 text-emerald-300' => $active,
                                   'text-gray-300 hover:text-white hover:bg-white/[0.04]' => ! $active,
                               ])>
                                @if ($active)
                                    <span class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-emerald-400"></span>
                                @endif
                                <svg @class(['w-5 h-5 flex-shrink-0', 'text-emerald-400' => $active, 'text-gray-500 group-hover:text-gray-300' => ! $active]) fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}"></path>
                                </svg>
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <!-- Shop info -->
                <div class="mt-8 rounded-2xl bg-white/[0.03] ring-1 ring-white/[0.06] p-4 space-y-3.5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Visit the shop</p>
                    <a href="tel:+639270071538" class="flex items-start gap-3 group">
                        <svg class="w-4 h-4 mt-0.5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>
                            <span class="block text-sm font-semibold text-white group-hover:text-emerald-300">+63 927 007 1538</span>
                            <span class="block text-xs text-gray-400">Dexter S. Abajo</span>
                        </span>
                    </a>
                    <div class="flex items-start gap-3">
                        <svg class="w-4 h-4 mt-0.5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-sm text-gray-300 leading-snug">Don Luis Village, Adelfa St<br>City of Mati</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-4 h-4 mt-0.5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-sm text-gray-300 leading-snug">
                            Mon–Fri 8AM–6PM<br>Sat 8AM–5PM<br><span class="text-rose-400">Sun closed</span>
                        </span>
                    </div>
                </div>
            </nav>

            <!-- User -->
            <div class="flex-shrink-0 p-4 border-t border-white/[0.06]">
                <div class="flex items-center gap-3 p-2">
                    <a href="{{ route('customer.profile') }}" wire:navigate class="flex items-center gap-3 min-w-0 flex-1 group">
                        <span class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-sm font-bold text-gray-950 flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <span class="min-w-0">
                            <span class="block text-sm font-semibold text-white truncate group-hover:text-emerald-300">{{ auth()->user()->name }}</span>
                            <span class="block text-xs text-gray-400 truncate">{{ auth()->user()->email }}</span>
                        </span>
                    </a>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-sm font-semibold text-gray-300 bg-white/[0.04] ring-1 ring-white/[0.06] hover:text-rose-300 hover:bg-rose-500/10 hover:ring-rose-500/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Log out
                    </button>
                </form>
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
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden"
             style="display: none;"></div>

        <!-- Main Content -->
        <div class="lg:pl-72">
            <!-- Notifications bell: in the mobile header on phones, top-right of the page on desktop -->
            <div class="fixed top-3 right-4 z-40 lg:relative lg:top-0 lg:right-0 lg:flex lg:justify-end lg:max-w-7xl lg:mx-auto lg:px-10 lg:pt-6 lg:-mb-6">
                <livewire:notification-bell />
            </div>
            <!-- Mobile Header -->
            <div class="lg:hidden sticky top-0 z-30 flex items-center justify-between h-16 px-4 bg-[#090c0b]/80 backdrop-blur-xl border-b border-white/[0.06]">
                <button @click="sidebarOpen = true" class="p-2 -ml-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/5" aria-label="Open menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <a href="{{ route('customer.dashboard') }}" wire:navigate class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-white p-0.5"><img src="{{ asset('images/shop.png') }}" alt="" class="w-full h-full object-contain"></span>
                    <span class="font-bold text-white">Dexter Auto</span>
                </a>
                <span class="w-10 h-10" aria-hidden="true"></span><!-- space for the notifications bell -->
            </div>

            <!-- Flash Messages -->
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 mt-6">
                    <div class="flex items-center gap-3 rounded-2xl bg-rose-500/10 ring-1 ring-rose-500/30 p-4">
                        <svg class="w-5 h-5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-rose-200 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <x-success-modal />

            <!-- Page Content -->
            <main class="py-6 sm:py-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>
