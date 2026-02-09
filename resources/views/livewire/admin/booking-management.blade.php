<div>
    <!-- Page Header -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 md:p-8 border-l-4 border-garage-neon relative overflow-hidden mb-6">
        <!-- Carbon Fiber Pattern Overlay -->
        <div class="absolute inset-0 bg-carbon-fiber opacity-50"></div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-2 md:space-x-4 mb-3">
                <!-- Clipboard Icon -->
                <svg class="w-8 h-8 md:w-12 md:h-12 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <h1 class="text-2xl md:text-4xl font-bold text-garage-offwhite service-tag tracking-wider">BOOKING MANAGEMENT</h1>
            </div>
            <p class="text-garage-steel text-sm md:text-lg ml-10 md:ml-16">View and manage all system bookings with received date and time</p>
        </div>
        
        <!-- Garage Floor Marking -->
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-white to-transparent opacity-30"></div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2 md:gap-4 mb-6">
        <div class="bg-garage-charcoal/50 rounded-lg border border-garage-neon/20 p-3 md:p-5 text-center hover:border-garage-neon/40 transition-all">
            <p class="text-xs text-garage-steel uppercase tracking-wider mb-1 md:mb-2">Total Bookings</p>
            <p class="text-2xl md:text-3xl font-bold text-garage-offwhite font-mono">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-garage-charcoal/50 rounded-lg border border-yellow-500/20 p-3 md:p-5 text-center hover:border-yellow-500/40 transition-all">
            <p class="text-xs text-garage-steel uppercase tracking-wider mb-1 md:mb-2">Pending</p>
            <p class="text-2xl md:text-3xl font-bold text-yellow-400 font-mono">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-garage-charcoal/50 rounded-lg border border-blue-500/20 p-3 md:p-5 text-center hover:border-blue-500/40 transition-all">
            <p class="text-xs text-garage-steel uppercase tracking-wider mb-1 md:mb-2">Approved</p>
            <p class="text-2xl md:text-3xl font-bold text-blue-400 font-mono">{{ $stats['approved'] }}</p>
        </div>
        <div class="bg-garage-charcoal/50 rounded-lg border border-garage-neon/20 p-3 md:p-5 text-center hover:border-garage-neon/40 transition-all">
            <p class="text-xs text-garage-steel uppercase tracking-wider mb-1 md:mb-2">Completed</p>
            <p class="text-2xl md:text-3xl font-bold text-garage-neon font-mono">{{ $stats['completed'] }}</p>
        </div>
        <div class="bg-garage-charcoal/50 rounded-lg border border-red-500/20 p-3 md:p-5 text-center hover:border-red-500/40 transition-all col-span-2 md:col-span-1">
            <p class="text-xs text-garage-steel uppercase tracking-wider mb-1 md:mb-2">Cancelled</p>
            <p class="text-2xl md:text-3xl font-bold text-red-400 font-mono">{{ $stats['cancelled'] }}</p>
        </div>
    </div>

    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-3 md:p-6 border border-garage-neon/20">
        <!-- Search and Filters -->
        <div class="mb-4 md:mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
            <div>
                <input wire:model.live="search" type="text" placeholder="Search by customer, vehicle, or booking ID..." 
                    class="w-full text-sm md:text-base rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite placeholder-garage-steel shadow-sm focus:border-garage-neon focus:ring-garage-neon">
            </div>
            <div>
                <select wire:model.live="statusFilter" class="w-full text-sm md:text-base rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <input wire:model.live="dateFrom" type="date" placeholder="From Date" 
                    class="w-full text-sm md:text-base rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon">
            </div>
        </div>
        
        <!-- Garage Floor Divider -->
        <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4 md:mb-6"></div>

        <!-- Bookings Table -->
        <div class="overflow-x-auto -mx-3 md:mx-0">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-garage-neon/20">
                        <thead class="bg-garage-charcoal/70">
                            <tr>
                                <th wire:click="sortByColumn('id')" class="px-2 md:px-4 py-2 md:py-3 text-left text-xs font-bold text-garage-neon uppercase cursor-pointer hover:bg-garage-forest/50 service-tag tracking-wider">
                                    <div class="flex items-center gap-1">
                                        <span class="hidden sm:inline">Booking</span> ID
                                        @if($sortBy === 'id')
                                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-2 md:px-4 py-2 md:py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Customer</th>
                                <th class="hidden lg:table-cell px-2 md:px-4 py-2 md:py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Vehicle</th>
                                <th wire:click="sortByColumn('booking_date')" class="px-2 md:px-4 py-2 md:py-3 text-left text-xs font-bold text-garage-neon uppercase cursor-pointer hover:bg-garage-forest/50 service-tag tracking-wider">
                                    <div class="flex items-center gap-1">
                                        <span class="hidden sm:inline">Booking</span> Date
                                        @if($sortBy === 'booking_date')
                                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </div>
                                </th>
                                <th class="hidden md:table-cell px-2 md:px-4 py-2 md:py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Time</th>
                                <th wire:click="sortByColumn('created_at')" class="hidden xl:table-cell px-2 md:px-4 py-2 md:py-3 text-left text-xs font-bold text-garage-neon uppercase cursor-pointer hover:bg-garage-forest/50 service-tag tracking-wider">
                                    <div class="flex items-center gap-1">
                                        Received At
                                        @if($sortBy === 'created_at')
                                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-2 md:px-4 py-2 md:py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Status</th>
                                <th class="hidden sm:table-cell px-2 md:px-4 py-2 md:py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Amount</th>
                                <th class="px-2 md:px-4 py-2 md:py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-garage-charcoal/30 divide-y divide-garage-neon/10">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-garage-forest/30 transition-colors">
                            <td class="px-2 md:px-4 py-3 md:py-4 whitespace-nowrap font-bold text-garage-neon font-mono text-xs md:text-sm">
                                #{{ $booking->id }}
                            </td>
                            <td class="px-2 md:px-4 py-3 md:py-4">
                                <div class="text-xs md:text-sm">
                                    <div class="font-medium text-garage-offwhite">{{ $booking->customer->getDisplayName() }}</div>
                                    <div class="text-garage-steel text-xs">{{ $booking->customer->getContactEmail() }}</div>
                                    <div class="text-garage-steel text-xs hidden sm:block">{{ $booking->customer->getContactPhone() }}</div>
                                </div>
                            </td>
                            <td class="hidden lg:table-cell px-2 md:px-4 py-3 md:py-4">
                                <div class="text-sm">
                                    <div class="font-medium text-garage-offwhite">{{ $booking->vehicle->license_plate }}</div>
                                    <div class="text-garage-steel">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</div>
                                    <div class="text-garage-steel text-xs">{{ $booking->vehicle->year }}</div>
                                </div>
                            </td>
                            <td class="px-2 md:px-4 py-3 md:py-4 whitespace-nowrap text-xs md:text-sm text-garage-offwhite">
                                <div class="hidden sm:block">{{ $booking->booking_date->format('M d, Y') }}</div>
                                <div class="sm:hidden">{{ $booking->booking_date->format('m/d/y') }}</div>
                            </td>
                            <td class="hidden md:table-cell px-2 md:px-4 py-3 md:py-4 whitespace-nowrap text-sm text-garage-offwhite">
                                {{ $booking->booking_time->format('h:i A') }}
                            </td>
                            <td class="hidden xl:table-cell px-2 md:px-4 py-3 md:py-4">
                                <div class="text-sm">
                                    <div class="font-medium text-garage-offwhite">{{ $booking->created_at->format('M d, Y') }}</div>
                                    <div class="text-garage-steel">{{ $booking->created_at->format('h:i A') }}</div>
                                    <div class="text-xs text-garage-steel/70">{{ $booking->created_at->diffForHumans() }}</div>
                                </div>
                            </td>
                            <td class="px-2 md:px-4 py-3 md:py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                        'approved' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                        'completed' => 'bg-garage-neon/20 text-garage-neon border-garage-neon/30',
                                        'cancelled' => 'bg-red-500/20 text-red-400 border-red-500/30',
                                        'no_show' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
                                    ];
                                @endphp
                                <span class="px-2 md:px-3 py-1 text-[10px] md:text-xs rounded-full border {{ $statusColors[$booking->status] ?? 'bg-garage-steel/20 text-garage-steel border-garage-steel/30' }} font-semibold">
                                    <span class="hidden sm:inline">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
                                    <span class="sm:hidden">{{ substr(ucfirst($booking->status), 0, 3) }}</span>
                                </span>
                            </td>
                            <td class="hidden sm:table-cell px-2 md:px-4 py-3 md:py-4 whitespace-nowrap text-xs md:text-sm font-bold text-white font-mono">
                                ₱{{ number_format($booking->total_amount, 2) }}
                            </td>
                            <td class="px-2 md:px-4 py-3 md:py-4 whitespace-nowrap text-xs md:text-sm">
                                <button wire:click="viewBooking({{ $booking->id }})" class="text-garage-neon hover:text-white font-semibold transition-colors">
                                    <span class="hidden sm:inline">View Details</span>
                                    <span class="sm:hidden">View</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-2 md:px-4 py-6 md:py-8 text-center text-garage-steel text-sm">
                                No bookings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
