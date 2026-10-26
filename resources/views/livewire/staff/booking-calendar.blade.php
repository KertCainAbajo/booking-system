@php
    $chipStyles = [
        'pending' => 'bg-amber-400/15 text-amber-200 ring-amber-400/30 hover:bg-amber-400/25',
        'approved' => 'bg-sky-400/15 text-sky-200 ring-sky-400/30 hover:bg-sky-400/25',
        'completed' => 'bg-emerald-400/15 text-emerald-200 ring-emerald-400/30 hover:bg-emerald-400/25',
        'cancelled' => 'bg-rose-400/10 text-rose-300 ring-rose-400/25 hover:bg-rose-400/20 line-through',
        'no_show' => 'bg-orange-400/10 text-orange-300 ring-orange-400/25 hover:bg-orange-400/20',
        'not_available' => 'bg-gray-400/10 text-gray-300 ring-gray-400/25 hover:bg-gray-400/20',
    ];
    $filters = ['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
    $days = collect($calendar)->flatten(1)->filter();
    $todayKey = now()->format('Y-m-d');
@endphp

<div class="space-y-6" x-data="{ openDay: @js($days->first(fn ($d) => $d['isToday']) ? $todayKey : null) }">
    <!-- Header -->
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-emerald-300">Calendar</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">{{ $monthName }}</h1>
            <p class="mt-1 text-gray-400">{{ $stats['total'] }} {{ \Illuminate\Support\Str::plural('booking', $stats['total']) }} this month · click a day to see its bookings</p>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="previousMonth" class="w-10 h-10 rounded-xl bg-white/[0.04] ring-1 ring-white/10 text-gray-300 hover:text-white hover:bg-white/10 flex items-center justify-center" aria-label="Previous month">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button wire:click="today" class="h-10 px-4 rounded-xl bg-white/[0.04] ring-1 ring-white/10 text-sm font-semibold text-gray-200 hover:bg-white/10">Today</button>
            <button wire:click="nextMonth" class="w-10 h-10 rounded-xl bg-white/[0.04] ring-1 ring-white/10 text-gray-300 hover:text-white hover:bg-white/10 flex items-center justify-center" aria-label="Next month">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    <!-- Month summary + filter -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap gap-2">
            @foreach ($filters as $value => $label)
                <button wire:click="$set('statusFilter', '{{ $value }}')"
                        @class([
                            'h-9 px-3.5 rounded-full text-sm font-medium ring-1 transition',
                            'bg-emerald-400 text-gray-950 ring-emerald-400' => $statusFilter === $value,
                            'bg-white/[0.04] text-gray-300 ring-white/10 hover:text-white hover:bg-white/10' => $statusFilter !== $value,
                        ])>
                    {{ $label }}
                    @if ($value !== 'all' && isset($stats[$value]))
                        <span class="ml-1 opacity-70">{{ $stats[$value] }}</span>
                    @endif
                </button>
            @endforeach
        </div>
        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-400">
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-amber-400"></span>Pending</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-sky-400"></span>Approved</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-emerald-400"></span>Completed</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-rose-400"></span>Cancelled</span>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_20rem] items-start">
        <!-- Month grid -->
        <section class="cp-card overflow-hidden">
            <div class="grid grid-cols-7 border-b border-white/[0.06]">
                @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                    <div class="py-3 text-center text-xs font-semibold uppercase tracking-wider {{ $dayName === 'Sun' ? 'text-rose-300/70' : 'text-gray-500' }}">{{ $dayName }}</div>
                @endforeach
            </div>
            <div class="grid grid-cols-7">
                @foreach ($calendar as $week)
                    @for ($i = 0; $i < 7; $i++)
                        @php $day = $week[$i] ?? null; @endphp
                        @if ($day === null)
                            <div class="min-h-[5.5rem] sm:min-h-[7.5rem] border-b border-r border-white/[0.04] bg-black/20"></div>
                        @else
                            @php $key = $day['date']->format('Y-m-d'); @endphp
                            <button type="button" x-on:click="openDay = '{{ $key }}'"
                                    class="group relative min-h-[5.5rem] sm:min-h-[7.5rem] p-1.5 sm:p-2 text-left border-b border-r border-white/[0.04] transition hover:bg-white/[0.03] focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-emerald-400 {{ $day['isPast'] ? 'bg-black/10' : '' }}"
                                    :class="openDay === '{{ $key }}' && '!bg-emerald-400/[0.07] ring-1 ring-inset ring-emerald-400/40'">
                                <div class="flex items-center justify-between">
                                    <span @class([
                                        'w-7 h-7 rounded-full flex items-center justify-center text-sm font-semibold',
                                        'bg-emerald-400 text-gray-950' => $day['isToday'],
                                        'text-gray-500' => $day['isPast'] && ! $day['isToday'],
                                        'text-gray-200' => ! $day['isPast'] && ! $day['isToday'],
                                    ])>{{ $day['day'] }}</span>
                                    @if ($day['bookings']->count() > 0)
                                        <span class="text-[11px] font-semibold text-gray-400">{{ $day['bookings']->count() }}</span>
                                    @endif
                                </div>
                                <div class="mt-1 space-y-1">
                                    @foreach ($day['bookings']->sortBy('booking_time')->take(3) as $booking)
                                        <div class="hidden sm:block truncate rounded-md px-1.5 py-0.5 text-[11px] font-medium ring-1 {{ $chipStyles[$booking->status] ?? $chipStyles['pending'] }}">
                                            {{ $booking->booking_time->format('g:i') }} {{ \Illuminate\Support\Str::of($booking->customer->getDisplayName())->explode(' ')->first() }}
                                        </div>
                                        <span class="sm:hidden inline-block w-1.5 h-1.5 rounded-full {{ ['pending' => 'bg-amber-400', 'approved' => 'bg-sky-400', 'completed' => 'bg-emerald-400', 'cancelled' => 'bg-rose-400'][$booking->status] ?? 'bg-gray-400' }}"></span>
                                    @endforeach
                                    @if ($day['bookings']->count() > 3)
                                        <div class="hidden sm:block text-[11px] font-medium text-gray-400 pl-1">+{{ $day['bookings']->count() - 3 }} more</div>
                                    @endif
                                </div>
                            </button>
                        @endif
                    @endfor
                @endforeach
            </div>
        </section>

        <!-- Selected day -->
        <aside class="cp-card p-5 xl:sticky xl:top-6">
            <div x-show="!openDay" class="py-10 text-center">
                <span class="mx-auto w-12 h-12 rounded-2xl bg-white/[0.05] flex items-center justify-center text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                <p class="mt-3 font-medium text-gray-300">Pick a day</p>
                <p class="text-sm text-gray-500">Its bookings will show here.</p>
            </div>

            @foreach ($days as $day)
                @php $key = $day['date']->format('Y-m-d'); @endphp
                <div x-show="openDay === '{{ $key }}'" style="display: none;">
                    <p class="text-sm font-medium text-emerald-300">{{ $day['isToday'] ? 'Today' : $day['date']->format('l') }}</p>
                    <h2 class="text-xl font-bold text-white">{{ $day['date']->format('F j, Y') }}</h2>
                    <p class="text-sm text-gray-400">{{ $day['bookings']->count() }} {{ \Illuminate\Support\Str::plural('booking', $day['bookings']->count()) }}</p>

                    <ul class="mt-4 space-y-2">
                        @forelse ($day['bookings']->sortBy('booking_time') as $booking)
                            <li>
                                <a href="{{ route('owner.booking.detail', $booking->id) }}" wire:navigate class="flex items-start gap-3 rounded-xl p-3 bg-black/20 ring-1 ring-white/[0.06] hover:ring-white/20 transition">
                                    <span class="text-sm font-bold text-white tabular-nums w-16 flex-shrink-0">{{ $booking->booking_time->format('g:i A') }}</span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block text-sm font-medium text-white truncate">{{ $booking->customer->getDisplayName() }}</span>
                                        <span class="block text-xs text-gray-400 truncate">{{ $booking->services->pluck('name')->join(', ') ?: 'No services' }}</span>
                                        <x-status-badge :status="$booking->status" class="mt-1.5 !px-2 !py-0.5" />
                                    </span>
                                </a>
                            </li>
                        @empty
                            <li class="py-6 text-center text-sm text-gray-500">No bookings on this day.</li>
                        @endforelse
                    </ul>
                </div>
            @endforeach
        </aside>
    </div>
</div>
