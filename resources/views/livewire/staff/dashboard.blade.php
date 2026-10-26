@php
    $activeToday = $todayBookings->whereNotIn('status', ['cancelled', 'no_show', 'not_available']);
    $expectedRevenue = $activeToday->sum('total_amount');
    $nextUp = $todayBookings->whereIn('status', ['pending', 'approved'])
        ->first(fn ($b) => $b->booking_time->format('H:i') >= now()->format('H:i'));

    $mix = [
        ['key' => 'pending', 'label' => 'Waiting', 'color' => 'bg-amber-400'],
        ['key' => 'approved', 'label' => 'Approved', 'color' => 'bg-sky-400'],
        ['key' => 'completed', 'label' => 'Completed', 'color' => 'bg-emerald-400'],
        ['key' => 'cancelled', 'label' => 'Cancelled', 'color' => 'bg-rose-400'],
    ];
@endphp

<div wire:poll.10s class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-emerald-300">{{ now()->format('l, F j, Y') }}</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">Today's work</h1>
            <p class="mt-1 text-gray-400">
                @if ($nextUp)
                    Next up: <span class="font-semibold text-white">{{ $nextUp->booking_time->format('g:i A') }}</span>
                    · {{ $nextUp->customer->getDisplayName() }} · {{ $nextUp->vehicle?->make }} {{ $nextUp->vehicle?->model }}
                @elseif ($todayStats['total'] > 0)
                    No more open jobs scheduled for the rest of today.
                @else
                    No bookings today. A good day to catch up.
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-2 h-10 px-3.5 rounded-xl bg-white/[0.04] ring-1 ring-white/10 text-sm text-gray-300">
                <span class="relative flex w-2 h-2">
                    <span class="absolute inline-flex w-full h-full rounded-full bg-emerald-400 opacity-75 animate-ping"></span>
                    <span class="relative inline-flex w-2 h-2 rounded-full bg-emerald-400"></span>
                </span>
                Live · {{ \Carbon\Carbon::createFromFormat('H:i:s', $lastUpdateTime)->format('g:i:s A') }}
            </span>
            <button wire:click="refreshDashboard" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white/[0.04] ring-1 ring-white/10 text-gray-300 hover:text-white hover:bg-white/10 transition" aria-label="Refresh now">
                <svg class="w-5 h-5" wire:loading.class="animate-spin" wire:target="refreshDashboard" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
            <a href="{{ route('owner.calendar') }}" wire:navigate class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-emerald-400 text-gray-950 text-sm font-semibold hover:bg-emerald-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Calendar
            </a>
        </div>
    </div>

    <!-- KPIs -->
    <section class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
        <div class="cp-card p-4 sm:p-5">
            <p class="text-sm font-medium text-gray-400">Jobs today</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ $todayStats['total'] }}</p>
            <p class="mt-1 text-xs sm:text-sm">
                @if ($yesterdayTotal === 0)
                    <span class="text-gray-500">None yesterday</span>
                @elseif ($todayStats['change_direction'] === 'same')
                    <span class="text-gray-500">Same as yesterday ({{ $yesterdayTotal }})</span>
                @else
                    <span class="{{ $todayStats['change_direction'] === 'up' ? 'text-emerald-300' : 'text-rose-300' }}">
                        {{ $todayStats['change_direction'] === 'up' ? '▲' : '▼' }} {{ abs($todayStats['change_percent']) }}%
                    </span>
                    <span class="text-gray-500">vs yesterday ({{ $yesterdayTotal }})</span>
                @endif
            </p>
        </div>

        <a href="{{ route('owner.bookings') }}" wire:navigate @class([
            'rounded-3xl p-4 sm:p-5 ring-1 transition',
            'bg-amber-400/10 ring-amber-400/40 hover:ring-amber-400/70' => $todayStats['pending'] > 0,
            'bg-white/[0.03] ring-white/[0.07] hover:ring-white/20' => $todayStats['pending'] === 0,
        ])>
            <p @class(['text-sm font-medium', 'text-amber-200' => $todayStats['pending'] > 0, 'text-gray-400' => $todayStats['pending'] === 0])>Waiting approval</p>
            <p @class(['mt-2 text-3xl font-bold', 'text-amber-300' => $todayStats['pending'] > 0, 'text-white' => $todayStats['pending'] === 0])>{{ $todayStats['pending'] }}</p>
            <p class="mt-1 text-xs sm:text-sm text-gray-400">{{ $todayStats['pending'] > 0 ? 'Needs your action →' : 'All caught up' }}</p>
        </a>

        <div class="cp-card p-4 sm:p-5">
            <p class="text-sm font-medium text-gray-400">Approved</p>
            <p class="mt-2 text-3xl font-bold text-sky-300">{{ $todayStats['approved'] }}</p>
            <p class="mt-1 text-xs sm:text-sm text-gray-500">Booked in, not done yet</p>
        </div>

        <div class="cp-card p-4 sm:p-5">
            <p class="text-sm font-medium text-gray-400">Completed</p>
            <p class="mt-2 text-3xl font-bold text-emerald-300">{{ $todayStats['completed'] }}</p>
            <p class="mt-1 text-xs sm:text-sm text-gray-500">{{ $todayStats['completion_rate'] }}% of today's jobs</p>
        </div>

        <div class="cp-card p-4 sm:p-5 col-span-2 lg:col-span-1">
            <p class="text-sm font-medium text-gray-400">Expected revenue</p>
            <p class="mt-2 text-3xl font-bold text-white">₱{{ number_format($expectedRevenue, 0) }}</p>
            <p class="mt-1 text-xs sm:text-sm text-gray-500">From {{ $activeToday->count() }} active {{ \Illuminate\Support\Str::plural('job', $activeToday->count()) }}</p>
        </div>
    </section>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_22rem] items-start">
        <!-- Today's schedule -->
        <section class="cp-card p-5 sm:p-7">
            <div class="flex items-center justify-between gap-3 mb-5">
                <div>
                    <h2 class="text-xl font-bold text-white">Today's schedule</h2>
                    <p class="text-sm text-gray-400">In order of appointment time</p>
                </div>
                <a href="{{ route('owner.bookings') }}" wire:navigate class="text-sm font-semibold text-emerald-300 hover:text-emerald-200">All bookings →</a>
            </div>

            @forelse ($todayBookings as $booking)
                @php
                    $isPast = $booking->booking_time->format('H:i') < now()->format('H:i');
                    $isNext = $nextUp && $nextUp->id === $booking->id;
                    $isClosed = in_array($booking->status, ['completed', 'cancelled', 'no_show', 'not_available']);
                @endphp
                <div class="relative flex gap-4 {{ $loop->last ? '' : 'pb-4' }}">
                    <!-- Timeline rail -->
                    <div class="flex flex-col items-center w-16 flex-shrink-0">
                        <span @class(['text-sm font-bold tabular-nums', 'text-emerald-300' => $isNext, 'text-gray-500' => $isClosed, 'text-white' => ! $isNext && ! $isClosed])>
                            {{ $booking->booking_time->format('g:i') }}
                        </span>
                        <span class="text-[11px] uppercase text-gray-500">{{ $booking->booking_time->format('A') }}</span>
                        @unless ($loop->last)
                            <span class="mt-2 w-px flex-1 bg-white/10"></span>
                        @endunless
                    </div>

                    <!-- Job card -->
                    <div @class([
                        'flex-1 min-w-0 rounded-2xl p-4 ring-1 transition',
                        'bg-emerald-400/[0.07] ring-emerald-400/40' => $isNext,
                        'bg-amber-400/[0.05] ring-amber-400/30' => ! $isNext && $booking->status === 'pending',
                        'bg-black/20 ring-white/[0.06] opacity-70' => ! $isNext && $isClosed,
                        'bg-black/20 ring-white/[0.08]' => ! $isNext && ! $isClosed && $booking->status !== 'pending',
                    ])>
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-semibold text-white">{{ $booking->customer->getDisplayName() }}</span>
                                    <x-status-badge :status="$booking->status" />
                                    @if ($isNext)
                                        <span class="cp-badge bg-emerald-400 text-gray-950 ring-emerald-400">Next up</span>
                                    @endif
                                    @if ($booking->marked_as_late)
                                        <span class="cp-badge bg-orange-400/10 text-orange-300 ring-orange-400/40">
                                            Running late{{ $booking->estimated_arrival_time ? ' · ETA ' . $booking->estimated_arrival_time->format('g:i A') : '' }}
                                        </span>
                                    @elseif ($isPast && in_array($booking->status, ['pending', 'approved']))
                                        <span class="cp-badge bg-rose-400/10 text-rose-300 ring-rose-400/30">Overdue</span>
                                    @endif
                                </div>
                                <div class="mt-1.5 flex flex-wrap items-center gap-2 text-sm text-gray-300">
                                    <span>{{ $booking->vehicle?->make }} {{ $booking->vehicle?->model }}</span>
                                    @if ($booking->vehicle?->plate_number)
                                        <span class="plate-number text-xs">{{ $booking->vehicle->plate_number }}</span>
                                    @endif
                                    <span class="text-gray-500">· Mechanic: <span class="{{ $booking->mechanic_name ? 'text-gray-300' : 'text-gray-500 italic' }}">{{ $booking->mechanic_name ?: 'not set' }}</span></span>
                                </div>
                                @if ($booking->services->isNotEmpty())
                                    <div class="mt-2.5 flex flex-wrap gap-1.5">
                                        @foreach ($booking->services as $service)
                                            <span class="rounded-full bg-white/[0.05] px-2.5 py-0.5 text-xs text-gray-300 ring-1 ring-white/[0.08]">{{ $service->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="flex sm:flex-col items-center sm:items-end justify-between gap-2 flex-shrink-0">
                                <span class="text-lg font-bold text-white tabular-nums">₱{{ number_format($booking->total_amount, 2) }}</span>
                                <a href="{{ route('owner.booking.detail', $booking->id) }}" wire:navigate
                                   @class([
                                       'inline-flex items-center gap-1.5 h-9 px-3.5 rounded-lg text-sm font-semibold transition',
                                       'bg-amber-400 text-gray-950 hover:bg-amber-300' => $booking->status === 'pending',
                                       'bg-white/[0.06] text-white ring-1 ring-white/10 hover:bg-white/10' => $booking->status !== 'pending',
                                   ])>
                                    {{ $booking->status === 'pending' ? 'Review' : 'Open' }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-14 rounded-2xl border border-dashed border-white/10">
                    <span class="mx-auto w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                    <p class="mt-4 text-lg font-semibold text-white">No bookings for today</p>
                    <p class="mt-1 text-sm text-gray-400">New bookings show up here automatically.</p>
                </div>
            @endforelse
        </section>

        <!-- Side column -->
        <div class="space-y-6">
            <!-- Status mix -->
            <section class="cp-card p-5 sm:p-6">
                <h2 class="text-base font-bold text-white">Today's status</h2>
                <div class="mt-4 flex h-3 w-full overflow-hidden rounded-full bg-white/[0.06]">
                    @foreach ($mix as $part)
                        @if ($todayStats[$part['key']] > 0)
                            <span class="{{ $part['color'] }} h-full" style="width: {{ $todayStats[$part['key'] . '_percent'] }}%"></span>
                        @endif
                    @endforeach
                </div>
                <ul class="mt-4 space-y-2.5">
                    @foreach ($mix as $part)
                        <li class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-gray-300"><span class="w-2.5 h-2.5 rounded-full {{ $part['color'] }}"></span>{{ $part['label'] }}</span>
                            <span class="font-semibold text-white tabular-nums">{{ $todayStats[$part['key']] }} <span class="text-gray-500 font-normal">({{ $todayStats[$part['key'] . '_percent'] }}%)</span></span>
                        </li>
                    @endforeach
                </ul>
            </section>

            <!-- This week -->
            <section class="cp-card p-5 sm:p-6">
                <div class="flex items-baseline justify-between">
                    <h2 class="text-base font-bold text-white">This week</h2>
                    <span class="text-xs text-gray-500">{{ now()->startOfWeek()->format('M j') }} – {{ now()->endOfWeek()->format('M j') }}</span>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2.5">
                    <div class="cp-panel p-3 col-span-2 flex items-center justify-between">
                        <span class="text-sm text-gray-400">Total bookings</span>
                        <span class="text-2xl font-bold text-white">{{ $weekStats['total'] }}</span>
                    </div>
                    <div class="cp-panel p-3"><p class="text-xs text-gray-400">Waiting</p><p class="text-xl font-bold text-amber-300">{{ $weekStats['pending'] }}</p></div>
                    <div class="cp-panel p-3"><p class="text-xs text-gray-400">Approved</p><p class="text-xl font-bold text-sky-300">{{ $weekStats['approved'] }}</p></div>
                    <div class="cp-panel p-3"><p class="text-xs text-gray-400">Completed</p><p class="text-xl font-bold text-emerald-300">{{ $weekStats['completed'] }}</p></div>
                    <div class="cp-panel p-3"><p class="text-xs text-gray-400">Cancelled</p><p class="text-xl font-bold text-rose-300">{{ $weekStats['cancelled'] }}</p></div>
                </div>
            </section>

            <!-- Coming up -->
            <section class="cp-card p-5 sm:p-6">
                <div class="flex items-baseline justify-between">
                    <h2 class="text-base font-bold text-white">Next 7 days</h2>
                    <a href="{{ route('owner.calendar') }}" wire:navigate class="text-xs font-semibold text-emerald-300 hover:text-emerald-200">Calendar →</a>
                </div>
                <ul class="mt-4 space-y-2">
                    @forelse ($upcomingBookings as $booking)
                        <li>
                            <a href="{{ route('owner.booking.detail', $booking->id) }}" wire:navigate class="flex items-center gap-3 rounded-xl p-2.5 -mx-2.5 hover:bg-white/[0.04] transition">
                                <span class="w-11 flex-shrink-0 rounded-lg bg-white/[0.05] ring-1 ring-white/10 text-center py-1">
                                    <span class="block text-[10px] font-semibold uppercase text-gray-400">{{ $booking->booking_date->format('M') }}</span>
                                    <span class="block text-base font-bold text-white leading-none">{{ $booking->booking_date->format('d') }}</span>
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-sm font-medium text-white truncate">{{ $booking->customer->getDisplayName() }}</span>
                                    <span class="block text-xs text-gray-400 truncate">{{ $booking->booking_time->format('g:i A') }} · {{ $booking->vehicle?->make }} {{ $booking->vehicle?->model }}</span>
                                </span>
                                <x-status-badge :status="$booking->status" class="!px-2 !py-0.5" />
                            </a>
                        </li>
                    @empty
                        <li class="text-sm text-gray-500">Nothing booked yet for the coming week.</li>
                    @endforelse
                </ul>
            </section>
        </div>
    </div>
</div>
