<div>
    <!-- Page Header -->
    <div class="bg-gradient-to-br from-black to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 md:p-8 border-l-4 border-garage-neon relative overflow-hidden mb-6">
        <!-- Carbon Fiber Pattern Overlay -->
        <div class="absolute inset-0 bg-carbon-fiber opacity-50"></div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-3 sm:space-x-4 mb-3">
                <!-- Dashboard Icon -->
                <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-garage-offwhite service-tag tracking-wider">BUSINESS DASHBOARD</h1>
            </div>
            <p class="text-garage-steel text-sm sm:text-base md:text-lg ml-11 sm:ml-14 md:ml-16">Overview of your business performance</p>
        </div>
        
        <!-- Garage Floor Marking -->
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-white to-transparent opacity-30"></div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-garage-steel uppercase tracking-wider mb-1">Today's Bookings</div>
                    <div class="text-3xl font-bold text-garage-offwhite font-mono">{{ $stats['today_bookings'] }}</div>
                    <div class="text-xs text-garage-neon mt-1">{{ $stats['completed_today'] }} completed</div>
                </div>
                <div class="text-4xl">📅</div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-yellow-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-garage-steel uppercase tracking-wider mb-1">Pending Bookings</div>
                    <div class="text-3xl font-bold text-yellow-400 font-mono">{{ $stats['pending_bookings'] }}</div>
                    <div class="text-xs text-garage-steel mt-1">Awaiting confirmation</div>
                </div>
                <div class="text-4xl">⏳</div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-5 md:p-6 border border-garage-neon/20">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <div class="text-xs sm:text-sm text-garage-steel uppercase tracking-wider mb-1">Today's Revenue</div>
                    <div class="text-xl sm:text-2xl font-bold text-garage-neon font-mono">₱{{ number_format($stats['today_revenue'], 2) }}</div>
                    <div class="text-xs text-garage-steel mt-1 truncate">Month: ₱{{ number_format($stats['month_revenue'], 2) }}</div>
                </div>
                <div class="text-3xl sm:text-4xl flex-shrink-0 ml-2">💰</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Recent Bookings -->
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-garage-neon/20">
            <h3 class="text-lg sm:text-xl font-bold text-garage-neon mb-4 service-tag">RECENT BOOKINGS</h3>
            
            <!-- Garage Floor Divider -->
            <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4"></div>
            
            <div class="space-y-3">
                @forelse($recentBookings as $booking)
                    <div class="border-b border-garage-neon/10 pb-3">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0 mb-1">
                            <div class="font-medium text-garage-offwhite text-sm sm:text-base">{{ $booking->customer->getDisplayName() }}</div>
                            <span class="px-2 sm:px-3 py-1 text-xs rounded-full border font-semibold inline-block w-fit
                                {{ $booking->status === 'pending' ? 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30' : '' }}
                                {{ $booking->status === 'approved' ? 'bg-blue-500/20 text-blue-400 border-blue-500/30' : '' }}
                                {{ $booking->status === 'completed' ? 'bg-garage-neon/20 text-garage-neon border-garage-neon/30' : '' }}
                                {{ $booking->status === 'cancelled' ? 'bg-red-500/20 text-red-400 border-red-500/30' : '' }}
                                {{ $booking->status === 'no_show' ? 'bg-orange-500/20 text-orange-400 border-orange-500/30' : '' }}
                            ">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                        <div class="text-xs sm:text-sm text-garage-steel">
                            {{ $booking->vehicle->make }} {{ $booking->vehicle->model }} • {{ $booking->booking_date }}
                        </div>
                        <div class="text-sm sm:text-base font-bold text-garage-neon font-mono">₱{{ number_format($booking->total_amount, 2) }}</div>
                    </div>
                @empty
                    <p class="text-garage-steel text-center py-4">No bookings yet</p>
                @endforelse
            </div>
        </div>

        <!-- Top Services -->
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-garage-neon/20">
            <h3 class="text-lg sm:text-xl font-bold text-garage-neon mb-4 service-tag">TOP SERVICES</h3>
            
            <!-- Garage Floor Divider -->
            <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4"></div>
            
            <div class="space-y-3">
                @forelse($topServices as $service)
                    <div class="flex items-center justify-between border-b border-garage-neon/10 pb-3 gap-2">
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-garage-offwhite text-sm sm:text-base truncate">{{ $service->name }}</div>
                            <div class="text-xs sm:text-sm text-garage-steel truncate">{{ $service->category->name }}</div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="font-bold text-garage-neon font-mono text-lg sm:text-xl">{{ $service->booking_services_count }}</div>
                            <div class="text-xs text-garage-steel">bookings</div>
                        </div>
                    </div>
                @empty
                    <p class="text-garage-steel text-center py-4">No service data yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Pending Payments Alert -->
    @if($stats['pending_payments'] > 0)
        <div class="mt-6 bg-gradient-to-r from-orange-500/20 to-garage-charcoal border border-orange-500/30 rounded-lg p-4">
            <div class="flex items-center">
                <div class="text-orange-400 mr-3">⚠️</div>
                <div>
                    <h4 class="font-semibold text-orange-400">Pending Payments</h4>
                    <p class="text-sm text-garage-steel">
                        ₱{{ number_format($stats['pending_payments'], 2) }} in pending payments need attention
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
