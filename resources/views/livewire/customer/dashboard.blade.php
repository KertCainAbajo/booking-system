@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $firstName = \Illuminate\Support\Str::of(auth()->user()->name)->explode(' ')->first();

    $statusStyles = [
        'pending' => 'bg-amber-400/10 text-amber-300 ring-amber-400/30',
        'approved' => 'bg-sky-400/10 text-sky-300 ring-sky-400/30',
        'completed' => 'bg-emerald-400/10 text-emerald-300 ring-emerald-400/30',
        'cancelled' => 'bg-gray-400/10 text-gray-300 ring-gray-400/30',
        'no_show' => 'bg-orange-400/10 text-orange-300 ring-orange-400/30',
        'not_available' => 'bg-rose-400/10 text-rose-300 ring-rose-400/30',
    ];
@endphp

<div class="space-y-6 lg:space-y-8">
    <!-- Hero -->
    <section class="relative overflow-hidden rounded-3xl ring-1 ring-white/10 bg-gray-950">
        <img src="{{ asset('images/7.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-40" aria-hidden="true">
        <div class="absolute inset-0 bg-gradient-to-r from-gray-950 via-gray-950/85 to-gray-950/30" aria-hidden="true"></div>
        <div class="absolute -top-24 -left-24 w-80 h-80 rounded-full bg-emerald-500/20 blur-3xl" aria-hidden="true"></div>

        <div class="relative grid gap-8 p-6 sm:p-8 lg:p-10 lg:grid-cols-[1fr_minmax(0,22rem)] lg:items-center">
            <div>
                <p class="text-sm font-medium text-emerald-300">{{ now()->format('l, F j') }}</p>
                <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold tracking-tight text-white">
                    {{ $greeting }}, {{ $firstName }}!
                </h1>
                <p class="mt-3 max-w-lg text-base sm:text-lg text-gray-300">
                    Ready when your car is. Book a service, follow the repair, and review past work, all in one place.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('customer.book') }}" wire:navigate class="inline-flex items-center gap-2 h-12 px-6 rounded-xl bg-gradient-to-b from-emerald-500 to-emerald-600 text-white font-semibold shadow-lg shadow-emerald-900/50 hover:from-emerald-400 hover:to-emerald-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Book a service
                    </a>
                    <a href="{{ route('customer.tracker') }}" wire:navigate class="inline-flex items-center gap-2 h-12 px-6 rounded-xl bg-white/10 text-white font-semibold ring-1 ring-white/15 backdrop-blur hover:bg-white/15 transition">
                        Track my car
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>

            <!-- Next appointment -->
            <div class="rounded-2xl bg-white/[0.06] ring-1 ring-white/10 backdrop-blur-md p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Next appointment</p>
                @if ($nextBooking)
                    <div class="mt-4 flex items-center gap-4">
                        <div class="w-16 flex-shrink-0 rounded-xl bg-emerald-500 text-gray-950 text-center py-2">
                            <div class="text-[11px] font-bold uppercase">{{ $nextBooking->booking_date->format('M') }}</div>
                            <div class="text-2xl font-extrabold leading-none">{{ $nextBooking->booking_date->format('d') }}</div>
                        </div>
                        <div class="min-w-0">
                            <p class="text-lg font-bold text-white">{{ $nextBooking->booking_time->format('g:i A') }}</p>
                            <p class="text-sm text-gray-300 truncate">{{ $nextBooking->booking_date->format('l') }} · {{ $nextBooking->vehicle?->make }} {{ $nextBooking->vehicle?->model }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $statusStyles[$nextBooking->status] ?? $statusStyles['pending'] }}">
                            {{ ucwords(str_replace('_', ' ', $nextBooking->status)) }}
                        </span>
                        <a href="{{ route('customer.tracker') }}" wire:navigate class="text-sm font-semibold text-emerald-300 hover:text-emerald-200">View details →</a>
                    </div>
                @else
                    <p class="mt-4 text-lg font-semibold text-white">Nothing scheduled</p>
                    <p class="mt-1 text-sm text-gray-400">Pick an open slot below to book your next visit.</p>
                @endif
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        @foreach ([
            ['label' => 'Active bookings', 'value' => $stats['active'], 'hint' => 'Pending or approved', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'tone' => 'text-sky-300 bg-sky-400/10'],
            ['label' => 'Services done', 'value' => $stats['completed'], 'hint' => 'Completed visits', 'icon' => 'M5 13l4 4L19 7', 'tone' => 'text-emerald-300 bg-emerald-400/10'],
            ['label' => 'Total spent', 'value' => '₱' . number_format($stats['spent'], 0), 'hint' => 'On completed work', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'tone' => 'text-amber-300 bg-amber-400/10'],
            ['label' => 'My vehicles', 'value' => $stats['vehicles'], 'hint' => 'Saved to your account', 'icon' => 'M8 17a2 2 0 11-4 0 2 2 0 014 0zM20 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0', 'tone' => 'text-violet-300 bg-violet-400/10'],
        ] as $stat)
            <div class="rounded-2xl bg-white/[0.03] ring-1 ring-white/[0.07] p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-400">{{ $stat['label'] }}</p>
                    <span class="w-9 h-9 rounded-xl flex items-center justify-center {{ $stat['tone'] }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $stat['icon'] }}"/></svg>
                    </span>
                </div>
                <p class="mt-3 text-2xl sm:text-3xl font-bold text-white">{{ $stat['value'] }}</p>
                <p class="mt-1 text-xs sm:text-sm text-gray-500">{{ $stat['hint'] }}</p>
            </div>
        @endforeach
    </section>

    <!-- Quick actions -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4">
        @foreach ([
            ['route' => 'customer.book', 'title' => 'Book a service', 'text' => 'Choose a date, time and the work you need.', 'icon' => 'M12 4v16m8-8H4'],
            ['route' => 'customer.tracker', 'title' => 'Track my car', 'text' => 'See live progress on your current repair.', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ['route' => 'customer.history', 'title' => 'Service history', 'text' => 'Look back at past visits and invoices.', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] as $action)
            <a href="{{ route($action['route']) }}" wire:navigate class="group flex items-center gap-4 rounded-2xl bg-white/[0.03] ring-1 ring-white/[0.07] p-5 hover:bg-white/[0.06] hover:ring-emerald-400/30 transition">
                <span class="w-12 h-12 flex-shrink-0 rounded-xl bg-emerald-500/10 ring-1 ring-emerald-400/20 flex items-center justify-center text-emerald-300 group-hover:bg-emerald-500 group-hover:text-gray-950 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $action['icon'] }}"/></svg>
                </span>
                <span class="flex-1 min-w-0">
                    <span class="block text-base font-semibold text-white">{{ $action['title'] }}</span>
                    <span class="block text-sm text-gray-400">{{ $action['text'] }}</span>
                </span>
                <svg class="w-5 h-5 text-gray-600 group-hover:text-emerald-300 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        @endforeach
    </section>

    <!-- Available Slots Section -->
    <livewire:customer.available-slots />

    <!-- Recent bookings -->
    <section class="rounded-3xl bg-white/[0.03] ring-1 ring-white/[0.07] p-5 sm:p-7">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
            <div>
                <h2 class="text-xl font-bold text-white">Recent bookings</h2>
                <p class="text-sm text-gray-400">Your last {{ $allBookings->count() ?: 10 }} service requests</p>
            </div>
            @if ($allBookings->count() > 0)
                <a href="{{ route('customer.history') }}" wire:navigate class="text-sm font-semibold text-emerald-300 hover:text-emerald-200">View all history →</a>
            @endif
        </div>

        <div class="space-y-3">
            @forelse ($allBookings as $booking)
                <div class="rounded-2xl bg-black/20 ring-1 ring-white/[0.06] p-4 sm:p-5 hover:ring-white/15 transition">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <!-- Date tile -->
                        <div class="flex sm:block items-center gap-3 sm:w-16 sm:text-center flex-shrink-0">
                            <div class="w-14 sm:w-16 rounded-xl bg-white/[0.05] ring-1 ring-white/10 text-center py-2">
                                <div class="text-[11px] font-semibold uppercase text-gray-400">{{ $booking->booking_date->format('M') }}</div>
                                <div class="text-xl font-bold text-white leading-none">{{ $booking->booking_date->format('d') }}</div>
                            </div>
                            <div class="sm:mt-1.5 text-sm sm:text-xs font-medium text-gray-400">{{ $booking->booking_time->format('g:i A') }}</div>
                        </div>

                        <!-- Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-semibold text-white">Service #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 {{ $statusStyles[$booking->status] ?? $statusStyles['pending'] }}">
                                    {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </div>
                            <div class="mt-1.5 flex flex-wrap items-center gap-2 text-sm text-gray-300">
                                <span>{{ $booking->vehicle?->make }} {{ $booking->vehicle?->model }}</span>
                                @if ($booking->vehicle?->plate_number)
                                    <span class="plate-number text-xs">{{ $booking->vehicle->plate_number }}</span>
                                @endif
                            </div>
                            @if ($booking->services->isNotEmpty())
                                <div class="mt-2.5 flex flex-wrap gap-1.5">
                                    @foreach ($booking->services as $service)
                                        <span class="rounded-full bg-white/[0.05] px-2.5 py-0.5 text-xs text-gray-300 ring-1 ring-white/[0.08]">{{ $service->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Amount -->
                        <div class="flex sm:flex-col items-center sm:items-end justify-between gap-2 sm:text-right">
                            <span class="text-xl font-bold text-white">₱{{ number_format($booking->total_amount, 2) }}</span>
                            @if (in_array($booking->status, ['pending', 'approved']))
                                <a href="{{ route('customer.tracker') }}" wire:navigate class="inline-flex items-center gap-1 text-sm font-semibold text-emerald-300 hover:text-emerald-200">
                                    Track
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-14 rounded-2xl border border-dashed border-white/10">
                    <span class="mx-auto w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </span>
                    <p class="mt-4 text-lg font-semibold text-white">No bookings yet</p>
                    <p class="mt-1 text-sm text-gray-400">Your service requests will show up here.</p>
                    <a href="{{ route('customer.book') }}" wire:navigate class="mt-6 inline-flex items-center gap-2 h-11 px-5 rounded-xl bg-emerald-500 text-gray-950 font-semibold hover:bg-emerald-400 transition">
                        Book your first service
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            @endforelse
        </div>
    </section>
</div>
