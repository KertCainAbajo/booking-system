@php
    $sortIcon = fn ($column) => $sortBy === $column ? ($sortDirection === 'asc' ? '↑' : '↓') : '';
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-emerald-300">History</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">Archived bookings</h1>
            <p class="mt-1 text-gray-400">Finished and cancelled jobs move here automatically every night.</p>
        </div>
    </div>

    <!-- Totals -->
    <section class="grid grid-cols-3 gap-3 sm:gap-4">
        <div class="cp-card p-4 sm:p-5">
            <p class="text-sm font-medium text-gray-400">Total archived</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ $stats['total'] }}</p>
        </div>
        <button wire:click="$set('statusFilter', '{{ $statusFilter === 'completed' ? '' : 'completed' }}')" @class(['rounded-3xl p-4 sm:p-5 text-left ring-1 transition', 'bg-emerald-400/10 ring-emerald-400/50' => $statusFilter === 'completed', 'bg-white/[0.03] ring-white/[0.07] hover:ring-white/20' => $statusFilter !== 'completed'])>
            <p class="text-sm font-medium text-gray-400">Completed</p>
            <p class="mt-2 text-3xl font-bold text-emerald-300">{{ $stats['completed'] }}</p>
        </button>
        <button wire:click="$set('statusFilter', '{{ $statusFilter === 'cancelled' ? '' : 'cancelled' }}')" @class(['rounded-3xl p-4 sm:p-5 text-left ring-1 transition', 'bg-rose-400/10 ring-rose-400/50' => $statusFilter === 'cancelled', 'bg-white/[0.03] ring-white/[0.07] hover:ring-white/20' => $statusFilter !== 'cancelled'])>
            <p class="text-sm font-medium text-gray-400">Cancelled</p>
            <p class="mt-2 text-3xl font-bold text-rose-300">{{ $stats['cancelled'] }}</p>
        </button>
    </section>

    <section class="cp-card">
        <!-- Filters -->
        <div class="p-4 sm:p-5 border-b border-white/[0.06] grid gap-3 lg:grid-cols-[minmax(0,1fr)_13rem_11rem_11rem_auto] items-end">
            <div>
                <label class="cp-label">Search</label>
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Name, phone, plate, car or reference" class="cp-input !pl-12">
                </div>
            </div>
            <div>
                <label class="cp-label">Status</label>
                <x-cp-select model="statusFilter" live label="Status" placeholder="All statuses" :searchable="false"
                    :options="['' => 'All statuses', 'completed' => 'Completed', 'cancelled' => 'Cancelled', 'no_show' => 'No show', 'not_available' => 'Not available']" />
            </div>
            <div>
                <label for="dateFrom" class="cp-label">Archived from</label>
                <input id="dateFrom" wire:model.live="dateFrom" type="date" class="cp-input">
            </div>
            <div>
                <label for="dateTo" class="cp-label">to</label>
                <input id="dateTo" wire:model.live="dateTo" type="date" class="cp-input">
            </div>
            <button wire:click="clearFilters" class="cp-btn-ghost whitespace-nowrap">Show all</button>
        </div>

        <!-- Table (desktop) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-gray-500 border-b border-white/[0.06]">
                        <th class="px-5 py-3 font-medium">Reference</th>
                        <th class="px-5 py-3 font-medium">Customer</th>
                        <th class="px-5 py-3 font-medium">Vehicle</th>
                        <th class="px-5 py-3 font-medium"><button wire:click="sortByColumn('booking_date')" class="uppercase tracking-wider hover:text-white">Appointment {{ $sortIcon('booking_date') }}</button></th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right"><button wire:click="sortByColumn('total_amount')" class="uppercase tracking-wider hover:text-white">Amount {{ $sortIcon('total_amount') }}</button></th>
                        <th class="px-5 py-3 font-medium"><button wire:click="sortByColumn('archived_at')" class="uppercase tracking-wider hover:text-white">Archived {{ $sortIcon('archived_at') }}</button></th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.05]">
                    @forelse ($bookings as $booking)
                        <tr wire:key="h-{{ $booking->id }}" class="hover:bg-white/[0.02] transition">
                            <td class="px-5 py-4 font-mono text-xs text-gray-300">{{ $booking->booking_reference ?? '#' . $booking->id }}</td>
                            <td class="px-5 py-4">
                                <div class="font-medium text-white">{{ $booking->customer->getDisplayName() }}</div>
                                <div class="text-xs text-gray-400">{{ $booking->customer->getContactPhone() }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="text-gray-200">{{ $booking->vehicle?->make }} {{ $booking->vehicle?->model }}</div>
                                @if ($booking->vehicle?->plate_number)
                                    <span class="plate-number text-[11px] inline-block mt-1">{{ $booking->vehicle->plate_number }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="text-white">{{ $booking->booking_date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $booking->booking_time->format('g:i A') }}</div>
                            </td>
                            <td class="px-5 py-4"><x-status-badge :status="$booking->status" /></td>
                            <td class="px-5 py-4 text-right font-semibold text-white tabular-nums">₱{{ number_format($booking->total_amount, 2) }}</td>
                            <td class="px-5 py-4 text-xs text-gray-400">{{ $booking->archived_at?->format('M d, Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('owner.booking.detail', $booking->id) }}" wire:navigate class="inline-flex items-center h-9 px-3.5 rounded-lg text-sm font-semibold bg-white/[0.06] text-white ring-1 ring-white/10 hover:bg-white/10">Open</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center">
                                <p class="text-lg font-semibold text-white">No archived bookings here</p>
                                <p class="mt-1 text-sm text-gray-400">Try a wider date range, or press "Show all".</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Cards (mobile) -->
        <ul class="md:hidden divide-y divide-white/[0.05]">
            @forelse ($bookings as $booking)
                <li wire:key="hm-{{ $booking->id }}">
                    <a href="{{ route('owner.booking.detail', $booking->id) }}" wire:navigate class="block p-4 hover:bg-white/[0.02]">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="font-semibold text-white truncate">{{ $booking->customer->getDisplayName() }}</div>
                                <div class="text-xs text-gray-400">{{ $booking->booking_date->format('M d, Y') }} · {{ $booking->vehicle?->make }} {{ $booking->vehicle?->model }}</div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <x-status-badge :status="$booking->status" />
                                <div class="mt-1 font-semibold text-white tabular-nums">₱{{ number_format($booking->total_amount, 2) }}</div>
                            </div>
                        </div>
                    </a>
                </li>
            @empty
                <li class="p-10 text-center text-gray-400">No archived bookings here.</li>
            @endforelse
        </ul>

        @if ($bookings->hasPages())
            <div class="p-4 sm:p-5 border-t border-white/[0.06]">
                {{ $bookings->links('livewire.cp-pagination') }}
            </div>
        @endif
    </section>
</div>
