<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('images/shop.png') }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-950 text-gray-900" style="font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;">
        <!-- Blurred workshop backdrop behind the card -->
        <div class="fixed inset-0 -z-0 overflow-hidden" aria-hidden="true">
            <img src="{{ asset('images/7.jpg') }}" alt="" class="w-full h-full object-cover scale-110 blur-2xl opacity-40">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-950/80 via-gray-950/70 to-emerald-950/80"></div>
            <div class="absolute -top-40 -left-40 w-[36rem] h-[36rem] rounded-full bg-emerald-500/20 blur-3xl"></div>
            <div class="absolute -bottom-48 -right-32 w-[32rem] h-[32rem] rounded-full bg-emerald-400/10 blur-3xl"></div>
        </div>

        <div class="relative min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-10">
        <!-- Login card -->
        <div class="w-full max-w-6xl overflow-hidden rounded-[28px] bg-white shadow-[0_40px_100px_-20px_rgba(0,0,0,0.85)] ring-1 ring-white/10 lg:grid lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1fr)] lg:min-h-[660px]">
            <!-- Hero: workshop photo -->
            <section class="relative h-52 sm:h-64 lg:h-auto overflow-hidden bg-gray-950 text-white">
                <img src="{{ asset('images/7.jpg') }}" alt="" class="auth-hero-img absolute inset-0 w-full h-full object-cover" aria-hidden="true">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/70 to-gray-950/30" aria-hidden="true"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-900/50 via-transparent to-transparent" aria-hidden="true"></div>

                <div class="relative h-full flex flex-col justify-between gap-8 p-6 sm:p-8 xl:p-10">
                    <!-- Brand -->
                    <div class="flex items-center justify-between gap-4">
                        <a href="{{ url('/') }}" class="flex items-center gap-3">
                            <span class="w-12 h-12 rounded-2xl bg-white/95 p-1 shadow-lg ring-1 ring-white/20">
                                <img src="{{ asset('images/shop.png') }}" alt="Dexter Auto Services logo" class="w-full h-full object-contain">
                            </span>
                            <span class="leading-tight">
                                <span class="block text-lg font-bold tracking-tight">Dexter Auto Services</span>
                                <span class="block text-sm text-white/70">Auto repair &amp; maintenance</span>
                            </span>
                        </a>
                        <span class="hidden sm:inline-flex items-center gap-2 rounded-full bg-white/10 backdrop-blur-md ring-1 ring-white/15 px-3.5 py-1.5 text-sm font-medium">
                            <span class="relative flex w-2 h-2">
                                <span class="absolute inline-flex w-full h-full rounded-full bg-emerald-400 opacity-75 animate-ping"></span>
                                <span class="relative inline-flex w-2 h-2 rounded-full bg-emerald-400"></span>
                            </span>
                            Mon–Sat · 8AM–6PM
                        </span>
                    </div>

                    <!-- Pitch -->
                    <div class="hidden lg:block max-w-xl">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-400">Online Service Booking</p>
                        <h1 class="mt-3 text-4xl xl:text-5xl font-extrabold leading-[1.05] tracking-tight">
                            Your car,<br>in <span class="bg-gradient-to-r from-emerald-300 to-emerald-500 bg-clip-text text-transparent">expert hands.</span>
                        </h1>
                        <p class="mt-4 text-lg text-white/75 max-w-md">
                            Book a service, follow your repair step by step, and see the price before any work begins.
                        </p>

                        <div class="mt-7 grid grid-cols-3 gap-3">
                            <div class="rounded-2xl bg-white/[0.07] backdrop-blur-md ring-1 ring-white/10 p-4">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="mt-3 font-semibold">Book online</p>
                                <p class="text-sm text-white/60">Pick your time</p>
                            </div>
                            <div class="rounded-2xl bg-white/[0.07] backdrop-blur-md ring-1 ring-white/10 p-4">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                <p class="mt-3 font-semibold">Live tracking</p>
                                <p class="text-sm text-white/60">Follow every step</p>
                            </div>
                            <div class="rounded-2xl bg-white/[0.07] backdrop-blur-md ring-1 ring-white/10 p-4">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                                <p class="mt-3 font-semibold">Upfront prices</p>
                                <p class="text-sm text-white/60">No bill surprises</p>
                            </div>
                        </div>

                        <div class="mt-7 flex items-center gap-4 border-t border-white/10 pt-6">
                            <span class="w-11 h-11 rounded-full bg-emerald-500/15 ring-1 ring-emerald-400/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </span>
                            <div>
                                <p class="text-sm text-white/60">Prefer to talk? Call the shop</p>
                                <a href="tel:+639270071538" class="text-lg font-semibold hover:text-emerald-300 transition-colors">+63 927 007 1538</a>
                            </div>
                        </div>
                    </div>

                    <!-- Compact pitch on small screens -->
                    <h1 class="lg:hidden text-2xl sm:text-3xl font-extrabold tracking-tight">
                        Your car, in <span class="text-emerald-400">expert hands.</span>
                    </h1>
                </div>
            </section>

            <!-- Form -->
            <main class="relative flex items-center justify-center px-5 sm:px-10 xl:px-14 py-10 lg:py-12">
                <div class="w-full max-w-[420px]">
                    {{ $slot }}

                    <p class="lg:hidden mt-8 text-center text-[15px] text-gray-600">
                        Need help? Call <a href="tel:+639270071538" class="font-semibold text-emerald-700">+63 927 007 1538</a>
                    </p>

                    <nav class="mt-8 flex flex-wrap justify-center gap-x-4 gap-y-1 text-sm text-gray-500" aria-label="Policies">
                        <a href="{{ route('privacy.policy') }}" class="hover:text-gray-900 hover:underline underline-offset-4">Privacy</a>
                        <a href="{{ route('terms.conditions') }}" class="hover:text-gray-900 hover:underline underline-offset-4">Terms</a>
                        <a href="{{ route('cookie.policy') }}" class="hover:text-gray-900 hover:underline underline-offset-4">Cookies</a>
                        <a href="{{ route('refund.policy') }}" class="hover:text-gray-900 hover:underline underline-offset-4">Refunds</a>
                    </nav>
                </div>
            </main>
        </div>
        </div>
    </body>
</html>
