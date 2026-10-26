@php
    $tiles = [
        '' => ['label' => 'All active', 'value' => $stats['total'], 'tone' => 'text-white'],
        'pending' => ['label' => 'Pending', 'value' => $stats['pending'], 'tone' => 'text-amber-300'],
        'approved' => ['label' => 'Approved', 'value' => $stats['approved'], 'tone' => 'text-sky-300'],
        'completed' => ['label' => 'Completed', 'value' => $stats['completed'], 'tone' => 'text-emerald-300'],
        'cancelled' => ['label' => 'Cancelled', 'value' => $stats['cancelled'], 'tone' => 'text-rose-300'],
        'advance' => ['label' => 'Upcoming', 'value' => $stats['advance'], 'tone' => 'text-violet-300'],
    ];
    $sortIcon = fn ($column) => $sortBy === $column ? ($sortDirection === 'asc' ? '↑' : '↓') : '';
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-emerald-300">Bookings</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">All bookings</h1>
            <p class="mt-1 text-gray-400">Review, approve and follow every job that isn't archived yet.</p>
        </div>
        <a href="{{ route('owner.calendar') }}" wire:navigate class="inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-white/[0.05] ring-1 ring-white/10 text-sm font-semibold text-gray-200 hover:bg-white/10 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Calendar view
        </a>
    </div>

    <!-- Status tiles (also filters) -->
    <section class="grid grid-cols-3 lg:grid-cols-6 gap-3">
        @foreach ($tiles as $value => $tile)
            <button wire:click="$set('statusFilter', '{{ $value }}')"
                    @class([
                        'rounded-2xl p-3.5 text-left ring-1 transition',
                        'bg-emerald-400/10 ring-emerald-400/50' => $statusFilter === $value,
                        'bg-white/[0.03] ring-white/[0.07] hover:ring-white/20' => $statusFilter !== $value,
                    ])>
                <p class="text-xs font-medium text-gray-400">{{ $tile['label'] }}</p>
                <p class="mt-1 text-2xl font-bold {{ $tile['tone'] }}">{{ $tile['value'] }}</p>
            </button>
        @endforeach
    </section>

    <section class="cp-card">
        <!-- Filters -->
        <div class="p-4 sm:p-5 border-b border-white/[0.06] flex flex-col lg:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search name, phone, plate, car or booking #" class="cp-input !pl-12">
            </div>
            <x-cp-select model="statusFilter" live class="lg:w-52" label="Status" placeholder="All statuses" :searchable="false"
                :options="['' => 'All statuses', 'pending' => 'Pending', 'approved' => 'Approved', 'completed' => 'Completed', 'cancelled' => 'Cancelled', 'no_show' => 'No show', 'not_available' => 'Not available', 'advance' => 'Upcoming (future dates)']" />
            <input wire:model.live="bookingDate" type="date" class="cp-input lg:w-48" aria-label="Booking date">
            @if ($search || $statusFilter || $bookingDate)
                <button wire:click="clearFilters" class="cp-btn-ghost !h-12 whitespace-nowrap">Clear</button>
            @endif
        </div>

        <!-- Table (desktop) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-gray-500 border-b border-white/[0.06]">
                        <th class="px-5 py-3 font-medium"><button wire:click="sortByColumn('id')" class="uppercase tracking-wider hover:text-white">Booking {{ $sortIcon('id') }}</button></th>
                        <th class="px-5 py-3 font-medium">Customer</th>
                        <th class="px-5 py-3 font-medium">Vehicle</th>
                        <th class="px-5 py-3 font-medium"><button wire:click="sortByColumn('booking_date')" class="uppercase tracking-wider hover:text-white">Appointment {{ $sortIcon('booking_date') }}</button></th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right"><button wire:click="sortByColumn('total_amount')" class="uppercase tracking-wider hover:text-white">Amount {{ $sortIcon('total_amount') }}</button></th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.05]">
                    @forelse ($bookings as $booking)
                        <tr wire:key="b-{{ $booking->id }}" class="hover:bg-white/[0.02] transition">
                            <td class="px-5 py-4 align-top">
                                <div class="font-semibold text-white">#{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</div>
                                <div class="text-xs text-gray-500" title="{{ $booking->created_at->format('M d, Y g:i A') }}">Received {{ $booking->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-5 py-4 align-top">
                                <div class="font-medium text-white">{{ $booking->customer->getDisplayName() }}</div>
                                <div class="text-xs text-gray-400">{{ $booking->customer->getContactPhone() }}</div>
                            </td>
                            <td class="px-5 py-4 align-top">
                                <div class="text-gray-200">{{ $booking->vehicle?->make }} {{ $booking->vehicle?->model }}</div>
                                @if ($booking->vehicle?->plate_number)
                                    <span class="plate-number text-[11px] inline-block mt-1">{{ $booking->vehicle->plate_number }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 align-top">
                                <div class="font-medium text-white">{{ $booking->booking_date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">
                                    {{ $booking->booking_time->format('g:i A') }}
                                    @if ($booking->booking_date->isToday())
                                        · <span class="text-emerald-300">Today</span>
                                    @elseif ($booking->booking_date->isFuture())
                                        · <span class="text-violet-300">in {{ (int) now()->startOfDay()->diffInDays($booking->booking_date) }}d</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4 align-top"><x-status-badge :status="$booking->status" /></td>
                            <td class="px-5 py-4 align-top text-right font-semibold text-white tabular-nums">₱{{ number_format($booking->total_amount, 2) }}</td>
                            <td class="px-5 py-4 align-top text-right">
                                <a href="{{ route('owner.booking.detail', $booking->id) }}" wire:navigate
                                   @class([
                                       'inline-flex items-center gap-1 h-9 px-3.5 rounded-lg text-sm font-semibold transition',
                                       'bg-amber-400 text-gray-950 hover:bg-amber-300' => $booking->status === 'pending',
                                       'bg-white/[0.06] text-white ring-1 ring-white/10 hover:bg-white/10' => $booking->status !== 'pending',
                                   ])>
                                    {{ $booking->status === 'pending' ? 'Review' : 'Open' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <p class="text-lg font-semibold text-white">No bookings found</p>
                                <p class="mt-1 text-sm text-gray-400">{{ ($search || $statusFilter || $bookingDate) ? 'Try a different search or clear the filters.' : 'New bookings will appear here.' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Cards (mobile) -->
        <ul class="md:hidden divide-y divide-white/[0.05]">
            @forelse ($bookings as $booking)
                <li wire:key="m-{{ $booking->id }}">
                    <a href="{{ route('owner.booking.detail', $booking->id) }}" wire:navigate class="block p-4 hover:bg-white/[0.02]">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="font-semibold text-white truncate">{{ $booking->customer->getDisplayName() }}</div>
                                <div class="text-xs text-gray-400">{{ $booking->booking_date->format('M d, Y') }} · {{ $booking->booking_time->format('g:i A') }}</div>
                                <div class="mt-1 text-sm text-gray-300">{{ $booking->vehicle?->make }} {{ $booking->vehicle?->model }}</div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <x-status-badge :status="$booking->status" />
                                <div class="mt-1 font-semibold text-white tabular-nums">₱{{ number_format($booking->total_amount, 2) }}</div>
                            </div>
                        </div>
                    </a>
                </li>
            @empty
                <li class="p-10 text-center text-gray-400">No bookings found.</li>
            @endforelse
        </ul>

        @if ($bookings->hasPages())
            <div class="p-4 sm:p-5 border-t border-white/[0.06]">
                {{ $bookings->links('livewire.cp-pagination') }}
            </div>
        @endif
    </section>
</div>
