<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/shop.png') }}">

    <title>{{ config('app.name', 'Auto Service Booking') }} - Super Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|rajdhani:600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
            background-color: #090b0d;
            background-image:
                radial-gradient(60rem 40rem at -10% -10%, rgba(56, 189, 248, 0.08), transparent 60%),
                radial-gradient(50rem 40rem at 110% 110%, rgba(16, 185, 129, 0.08), transparent 60%);
            background-attachment: fixed;
        }
        .service-tag {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            letter-spacing: 1px;
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
        }
    </style>
</head>
<body class="antialiased text-gray-100">
    @php
        $navItems = [
            ['route' => 'admin.dashboard', 'active' => ['admin.dashboard'], 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['route' => 'admin.bookings', 'active' => ['admin.bookings', 'admin.booking.detail'], 'label' => 'Bookings', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ['route' => 'admin.users', 'active' => ['admin.users'], 'label' => 'Users', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
            ['route' => 'admin.services', 'active' => ['admin.services'], 'label' => 'Services', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
            ['route' => 'admin.monitoring', 'active' => ['admin.monitoring'], 'label' => 'Monitoring', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['route' => 'admin.profile', 'active' => ['admin.profile'], 'label' => 'My Profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ];
    @endphp

    <div class="min-h-screen" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-72 flex flex-col bg-[#0b0e10]/95 backdrop-blur-xl border-r border-white/[0.06] transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0"
               :class="{ '!translate-x-0': sidebarOpen }">
            <div class="flex items-center justify-between h-20 px-6 flex-shrink-0">
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-3">
                    <span class="w-11 h-11 rounded-xl bg-white p-1 shadow-lg shadow-sky-900/30">
                        <img src="{{ asset('images/shop.png') }}" alt="Dexter Auto Services" class="w-full h-full object-contain">
                    </span>
                    <span class="leading-tight">
                        <span class="block text-base font-bold text-white">Dexter Auto</span>
                        <span class="block text-xs text-sky-300/80">Super Admin console</span>
                    </span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden p-2 -mr-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5" aria-label="Close menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <nav class="flex-1 px-4 pb-6 overflow-y-auto">
                <p class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-gray-500">Administration</p>
                <ul class="space-y-1">
                    @foreach ($navItems as $item)
                        @php $active = request()->routeIs(...$item['active']); @endphp
                        <li>
                            <a href="{{ route($item['route']) }}" wire:navigate
                               @class([
                                   'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-[15px] font-medium transition-colors',
                                   'bg-sky-400/10 text-sky-200' => $active,
                                   'text-gray-300 hover:text-white hover:bg-white/[0.04]' => ! $active,
                               ])>
                                @if ($active)
                                    <span class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-sky-400"></span>
                                @endif
                                <svg @class(['w-5 h-5 flex-shrink-0', 'text-sky-300' => $active, 'text-gray-500 group-hover:text-gray-300' => ! $active]) fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}"></path>
                                </svg>
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-8 rounded-2xl bg-white/[0.03] ring-1 ring-white/[0.06] p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Environment</p>
                    <p class="mt-2 flex items-center gap-2 text-sm text-gray-300">
                        <span @class(['w-2 h-2 rounded-full', 'bg-emerald-400' => app()->isProduction(), 'bg-amber-400' => ! app()->isProduction()])></span>
                        {{ ucfirst(app()->environment()) }}
                        @if (config('app.debug'))
                            <span class="cp-badge !py-0 bg-amber-400/10 text-amber-300 ring-amber-400/30">debug on</span>
                        @endif
                    </p>
                </div>
            </nav>

            <div class="flex-shrink-0 p-4 border-t border-white/[0.06]">
                <a href="{{ route('admin.profile') }}" wire:navigate class="flex items-center gap-3 p-2 group">
                    <span class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-sm font-bold text-gray-950 flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <span class="min-w-0">
                        <span class="block text-sm font-semibold text-white truncate group-hover:text-sky-200">{{ auth()->user()->name }}</span>
                        <span class="block text-xs text-gray-400 truncate">Super Admin · {{ auth()->user()->email }}</span>
                    </span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-sm font-semibold text-gray-300 bg-white/[0.04] ring-1 ring-white/[0.06] hover:text-rose-300 hover:bg-rose-500/10 hover:ring-rose-500/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Log out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden" style="display: none;"></div>

        <div class="lg:pl-72">
            <!-- Mobile Header -->
            <div class="lg:hidden sticky top-0 z-30 flex items-center justify-between h-16 px-4 bg-[#090b0d]/80 backdrop-blur-xl border-b border-white/[0.06]">
                <button @click="sidebarOpen = true" class="p-2 -ml-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/5" aria-label="Open menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <span class="font-bold text-white">Dexter Auto · Admin</span>
                <span class="w-9 h-9 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-sm font-bold text-gray-950">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
            </div>

            <!-- Flash Messages -->
            @foreach (['message' => 'emerald', 'error' => 'rose'] as $key => $tone)
                @if (session($key))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" x-transition class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 mt-6">
                        <div class="flex items-center gap-3 rounded-2xl p-4 ring-1 {{ $tone === 'emerald' ? 'bg-emerald-500/10 ring-emerald-500/30 text-emerald-100' : 'bg-rose-500/10 ring-rose-500/30 text-rose-100' }}">
                            <svg class="w-5 h-5 flex-shrink-0 {{ $tone === 'emerald' ? 'text-emerald-400' : 'text-rose-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tone === 'emerald' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' }}"/>
                            </svg>
                            <p class="font-medium flex-1">{{ session($key) }}</p>
                            <button @click="show = false" class="text-current/70 hover:text-white" aria-label="Dismiss">✕</button>
                        </div>
                    </div>
                @endif
            @endforeach

            <x-success-modal />

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
