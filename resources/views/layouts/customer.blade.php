<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Auto Service Booking') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('customer.dashboard') }}" class="text-xl font-bold text-blue-600">
                            🚗 Auto Service Booking
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('customer.booking') }}" class="text-gray-700 hover:text-blue-600">New Booking</a>
                        <a href="{{ route('customer.tracker') }}" class="text-gray-700 hover:text-blue-600">Track Booking</a>
                        <a href="{{ route('customer.history') }}" class="text-gray-700 hover:text-blue-600">History</a>
                        <div class="relative">
                            <button class="flex items-center text-gray-700">
                                <span>{{ auth()->user()->name }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

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
