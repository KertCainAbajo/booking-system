<div>
    <!-- Page Header -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 md:p-8 border-l-4 border-garage-neon relative overflow-hidden mb-4 sm:mb-6">
        <!-- Carbon Fiber Pattern Overlay -->
        <div class="absolute inset-0 bg-carbon-fiber opacity-50"></div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-3 sm:space-x-4 mb-2 sm:mb-3">
                <!-- History Icon -->
                <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-garage-offwhite service-tag tracking-wider">BOOKING HISTORY</h1>
            </div>
            <p class="text-garage-steel text-sm sm:text-base md:text-lg ml-11 sm:ml-14 md:ml-16">Archived completed and cancelled bookings</p>
        </div>
        
        <!-- Garage Floor Marking -->
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-white to-transparent opacity-30"></div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">
        <div class="bg-garage-charcoal/50 rounded-lg border border-garage-neon/20 p-4 sm:p-5 text-center hover:border-garage-neon/40 transition-all">
            <p class="text-xs text-garage-steel uppercase tracking-wider mb-1 sm:mb-2">Total Archived</p>
            <p class="text-2xl sm:text-3xl font-bold text-garage-offwhite font-mono">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-garage-charcoal/50 rounded-lg border border-garage-neon/20 p-4 sm:p-5 text-center hover:border-garage-neon/40 transition-all">
            <p class="text-xs text-garage-steel uppercase tracking-wider mb-1 sm:mb-2">Completed</p>
            <p class="text-2xl sm:text-3xl font-bold text-garage-neon font-mono">{{ $stats['completed'] }}</p>
        </div>
        <div class="bg-garage-charcoal/50 rounded-lg border border-red-500/20 p-4 sm:p-5 text-center hover:border-red-500/40 transition-all">
            <p class="text-xs text-garage-steel uppercase tracking-wider mb-1 sm:mb-2">Cancelled</p>
            <p class="text-2xl sm:text-3xl font-bold text-red-400 font-mono">{{ $stats['cancelled'] }}</p>
        </div>
    </div>

    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-garage-neon/20">
        <!-- Search and Filters -->
        <div class="mb-4 sm:mb-6 grid grid-cols-1 gap-3 sm:gap-4">
            <div>
                <input wire:model.live="search" type="text" placeholder="Search by customer, vehicle, or reference..." 
                    class="w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite placeholder-garage-steel shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm sm:text-base px-3 sm:px-4 py-2 sm:py-3">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <select wire:model.live="statusFilter" class="w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm sm:text-base px-3 sm:px-4 py-2 sm:py-3">
                    <option value="">All Status</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="no_show">Not Arriving</option>
                </select>
                <input wire:model.live="dateFrom" type="date" placeholder="Booking Date"
                    class="w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm sm:text-base px-3 sm:px-4 py-2 sm:py-3">
            </div>
        </div>
        
        <!-- Garage Floor Divider -->
        <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4 sm:mb-6"></div>

        <!-- Bookings Table -->
        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-garage-neon/20">
                        <thead class="bg-garage-charcoal/70">
                            <tr>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Reference</th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Customer</th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-bold text-garage-neon uppercase service-tag tracking-wider hidden md:table-cell">Vehicle</th>
                                <th wire:click="sortByColumn('booking_date')" class="px-3 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-bold text-garage-neon uppercase cursor-pointer hover:bg-garage-forest/50 service-tag tracking-wider">
                                    <div class="flex items-center gap-1">
                                        <span class="hidden sm:inline">Booking Date</span>
                                        <span class="sm:hidden">Date</span>
                                        @if($sortBy === 'booking_date')
                                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Status</th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-bold text-garage-neon uppercase service-tag tracking-wider hidden sm:table-cell">Amount</th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-center text-[10px] sm:text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-garage-charcoal/30 divide-y divide-garage-neon/10">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-garage-forest/30 transition-colors">
                        <td class="px-3 sm:px-4 py-3 sm:py-4">
                            <span class="text-garage-offwhite font-mono font-semibold text-xs sm:text-sm">{{ $booking->booking_reference }}</span>
                        </td>
                        <td class="px-3 sm:px-4 py-3 sm:py-4">
                            <div class="text-garage-offwhite font-medium text-xs sm:text-sm">{{ $booking->customer->name }}</div>
                            <div class="text-garage-steel text-[10px] sm:text-xs">{{ $booking->customer->email }}</div>
                        </td>
                        <td class="px-3 sm:px-4 py-3 sm:py-4 hidden md:table-cell">
                            <div class="text-garage-offwhite text-sm">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</div>
                            <div class="text-garage-steel text-xs">{{ $booking->vehicle->registration_number }}</div>
                        </td>
                        <td class="px-3 sm:px-4 py-3 sm:py-4">
                            <div class="text-garage-offwhite text-xs sm:text-sm">{{ $booking->booking_date->format('M d, Y') }}</div>
                            <div class="text-garage-steel text-[10px] sm:text-xs">{{ $booking->booking_time->format('h:i A') }}</div>
                        </td>
                        <td class="px-3 sm:px-4 py-3 sm:py-4">
                            @if($booking->status === 'completed')
                                <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-bold bg-garage-neon/20 text-garage-neon border border-garage-neon/30 service-tag">
                                    COMPLETED
                                </span>
                            @elseif($booking->status === 'cancelled')
                                <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-bold bg-red-500/20 text-red-400 border border-red-500/30 service-tag">
                                    CANCELLED
                                </span>
                            @elseif($booking->status === 'no_show')
                                <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-bold bg-orange-500/20 text-orange-400 border border-orange-500/30 service-tag">
                                    NOT ARRIVING
                                </span>
                            @elseif($booking->status === 'not_available')
                                <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-bold bg-gray-500/20 text-gray-400 border border-gray-500/30 service-tag">
                                    NOT AVAILABLE
                                </span>
                            @endif
                        </td>
                        <td class="px-3 sm:px-4 py-3 sm:py-4 hidden sm:table-cell">
                            <span class="text-garage-offwhite font-mono font-bold text-sm">₱{{ number_format($booking->total_amount, 2) }}</span>
                        </td>
                        <td class="px-3 sm:px-4 py-3 sm:py-4 text-center">
                            <button wire:click="viewBooking({{ $booking->id }})"
                                class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-1.5 bg-garage-neon/20 text-garage-neon border border-garage-neon/30 rounded hover:bg-garage-neon hover:text-garage-black font-semibold transition-all text-xs sm:text-sm service-tag">
                                VIEW
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-3 sm:px-4 py-6 sm:py-8 text-center">
                            <div class="text-garage-steel">
                                <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-base sm:text-lg font-semibold">No archived bookings found</p>
                                <p class="text-xs sm:text-sm mt-1">Try adjusting your search or filters</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4 sm:mt-6">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
