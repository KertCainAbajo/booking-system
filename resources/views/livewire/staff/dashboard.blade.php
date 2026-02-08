<div>
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 md:p-8 border-l-4 border-garage-neon relative overflow-hidden">
            <!-- Carbon Fiber Pattern Overlay -->
            <div class="absolute inset-0 bg-carbon-fiber opacity-50"></div>
            
            <div class="relative z-10">
                <div class="flex items-center space-x-3 sm:space-x-4 mb-3">
                    <!-- Wrench + Clipboard Icon -->
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-garage-offwhite service-tag tracking-wider">STAFF PANEL</h1>
                </div>
                <p class="text-garage-steel text-sm sm:text-base md:text-lg ml-11 sm:ml-14 md:ml-16">Welcome back! Here's your overview for today</p>
            </div>
            
            <!-- Garage Floor Marking -->
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-white to-transparent opacity-30"></div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <!-- View Calendar Card -->
            <a href="{{ route('staff.calendar') }}" 
               class="group bg-gradient-to-br from-garage-forest to-garage-darkgreen hover:from-garage-neon/20 hover:to-garage-forest rounded-lg p-4 sm:p-6 transition-all duration-300 shadow-garage hover:shadow-neon-green border border-garage-neon/20 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-garage-neon/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg sm:text-xl font-bold text-garage-offwhite mb-2 service-tag">VIEW CALENDAR</h3>
                            <p class="text-garage-steel text-xs sm:text-sm">See all bookings in calendar view</p>
                        </div>
                        <!-- Calendar Icon -->
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-white/40 group-hover:text-white transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    
                    <div class="flex items-center text-white font-semibold group-hover:translate-x-2 transition-transform">
                        <span class="service-tag text-sm">OPEN CALENDAR</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- My Profile Card -->
            <a href="{{ route('staff.profile') }}" 
               class="group bg-gradient-to-br from-garage-forest to-garage-darkgreen hover:from-garage-neon/20 hover:to-garage-forest rounded-lg p-4 sm:p-6 transition-all duration-300 shadow-garage hover:shadow-neon-green border border-garage-neon/20 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg sm:text-xl font-bold text-garage-offwhite mb-2 service-tag">MY PROFILE</h3>
                            <p class="text-garage-steel text-xs sm:text-sm">View and update your profile</p>
                        </div>
                        <!-- User Icon -->
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-white/40 group-hover:text-white transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    
                    <div class="flex items-center text-white font-semibold group-hover:translate-x-2 transition-transform">
                        <span class="service-tag text-sm">EDIT PROFILE</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <!-- Today's Stats -->
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-garage-neon/20">
            <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg sm:text-xl font-bold text-garage-offwhite service-tag">TODAY'S OVERVIEW</h3>
            </div>

            <!-- Garage Floor Divider -->
            <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4 sm:mb-6"></div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                <div class="bg-garage-charcoal/50 rounded-lg border border-garage-neon/20 p-3 sm:p-4 md:p-5 text-center hover:border-garage-neon/40 transition-all">
                    <div class="text-2xl sm:text-3xl font-bold text-garage-offwhite mb-1 sm:mb-2 font-mono">{{ $todayStats['total'] }}</div>
                    <div class="text-xs text-garage-steel uppercase tracking-wider">Total</div>
                </div>
                <div class="bg-garage-charcoal/50 rounded-lg border border-yellow-500/20 p-3 sm:p-4 md:p-5 text-center hover:border-yellow-500/40 transition-all">
                    <div class="text-2xl sm:text-3xl font-bold text-yellow-400 mb-1 sm:mb-2 font-mono">{{ $todayStats['pending'] }}</div>
                    <div class="text-xs text-garage-steel uppercase tracking-wider">Pending</div>
                </div>
                <div class="bg-garage-charcoal/50 rounded-lg border border-blue-500/20 p-3 sm:p-4 md:p-5 text-center hover:border-blue-500/40 transition-all">
                    <div class="text-2xl sm:text-3xl font-bold text-blue-400 mb-1 sm:mb-2 font-mono">{{ $todayStats['approved'] }}</div>
                    <div class="text-xs text-garage-steel uppercase tracking-wider">Approved</div>
                </div>
                <div class="bg-garage-charcoal/50 rounded-lg border border-garage-neon/20 p-5 text-center hover:border-garage-neon/40 transition-all">
                    <div class="text-3xl font-bold text-garage-neon mb-2 font-mono">{{ $todayStats['completed'] }}</div>
                    <div class="text-xs text-garage-steel uppercase tracking-wider">Completed</div>
                </div>
                <div class="bg-garage-charcoal/50 rounded-lg border border-red-500/20 p-5 text-center hover:border-red-500/40 transition-all">
                    <div class="text-3xl font-bold text-red-400 mb-2 font-mono">{{ $todayStats['cancelled'] }}</div>
                    <div class="text-xs text-garage-steel uppercase tracking-wider">Cancelled</div>
                </div>
            </div>
        </div>

        <!-- This Week's Stats -->
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
            <div class="flex items-center space-x-3 mb-6">
                <svg class="w-6 h-6 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="text-xl font-bold text-garage-offwhite service-tag">THIS WEEK'S STATS</h3>
            </div>

            <!-- Garage Floor Divider -->
            <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-6"></div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-garage-charcoal/50 rounded-lg border border-garage-neon/20 p-5 text-center hover:border-garage-neon/40 transition-all">
                    <div class="text-3xl font-bold text-garage-offwhite mb-2 font-mono">{{ $weekStats['total'] }}</div>
                    <div class="text-xs text-garage-steel uppercase tracking-wider">Total Bookings</div>
                </div>
                <div class="bg-garage-charcoal/50 rounded-lg border border-yellow-500/20 p-5 text-center hover:border-yellow-500/40 transition-all">
                    <div class="text-3xl font-bold text-yellow-400 mb-2 font-mono">{{ $weekStats['pending'] }}</div>
                    <div class="text-xs text-garage-steel uppercase tracking-wider">Pending</div>
                </div>
                <div class="bg-garage-charcoal/50 rounded-lg border border-blue-500/20 p-5 text-center hover:border-blue-500/40 transition-all">
                    <div class="text-3xl font-bold text-blue-400 mb-2 font-mono">{{ $weekStats['approved'] }}</div>
                    <div class="text-xs text-garage-steel uppercase tracking-wider">Approved</div>
                </div>
                <div class="bg-garage-charcoal/50 rounded-lg border border-garage-neon/20 p-5 text-center hover:border-garage-neon/40 transition-all">
                    <div class="text-3xl font-bold text-garage-neon mb-2 font-mono">{{ $weekStats['completed'] }}</div>
                    <div class="text-xs text-garage-steel uppercase tracking-wider">Completed</div>
                </div>
                <div class="bg-garage-charcoal/50 rounded-lg border border-red-500/20 p-5 text-center hover:border-red-500/40 transition-all">
                    <div class="text-3xl font-bold text-red-400 mb-2 font-mono">{{ $weekStats['cancelled'] }}</div>
                    <div class="text-xs text-garage-steel uppercase tracking-wider">Cancelled</div>
                </div>
            </div>
        </div>

        <!-- Today's Bookings -->
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-garage-neon/20">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0 mb-4 sm:mb-6">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <h3 class="text-lg sm:text-xl font-bold text-garage-offwhite service-tag">TODAY'S BOOKINGS</h3>
                </div>
                @if($todayBookings->count() > 0)
                    <span class="bg-garage-neon/20 text-garage-neon px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-bold border border-garage-neon/30 service-tag">
                        {{ $todayBookings->count() }} BOOKING(S)
                    </span>
                @endif
            </div>

            <!-- Garage Floor Divider -->
            <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4 sm:mb-6"></div>

            @forelse($todayBookings as $booking)
                <div class="border-l-4 {{ $booking->status === 'pending' ? 'border-yellow-400' : ($booking->status === 'approved' ? 'border-blue-400' : ($booking->status === 'completed' ? 'border-garage-neon' : 'border-red-500')) }} bg-garage-charcoal/50 rounded-r-lg p-3 sm:p-4 md:p-5 mb-3 sm:mb-4 hover:bg-garage-forest/30 transition-all duration-200">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-2 sm:mb-3">
                                <span class="text-xs sm:text-sm font-bold text-garage-offwhite font-mono bg-garage-forest/50 px-2 sm:px-3 py-1 rounded flex-shrink-0">{{ $booking->booking_time }}</span>
                                <h4 class="font-bold text-garage-offwhite service-tag text-sm sm:text-base break-words">{{ strtoupper($booking->customer->getDisplayName()) }}</h4>
                                <span class="service-tag px-2 sm:px-3 py-1 text-[10px] sm:text-xs rounded font-bold flex-shrink-0
                                    {{ $booking->status === 'pending' ? 'bg-yellow-400/20 text-yellow-400 border border-yellow-400/30' : '' }}
                                    {{ $booking->status === 'approved' ? 'bg-blue-400/20 text-blue-400 border border-blue-400/30' : '' }}
                                    {{ $booking->status === 'completed' ? 'bg-garage-neon/20 text-garage-neon border border-garage-neon/30' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : '' }}
                                    {{ $booking->status === 'no_show' ? 'bg-orange-500/20 text-orange-400 border border-orange-500/30' : '' }}">
                                    {{ $booking->status === 'no_show' ? 'NOT ARRIVING' : strtoupper($booking->status) }}
                                </span>
                            </div>
                            <div class="space-y-1 sm:space-y-2 text-xs sm:text-sm text-garage-steel mb-2 sm:mb-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-garage-steel flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <span class="text-garage-offwhite font-semibold break-words">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</span>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-1.5 sm:gap-2 text-xs sm:text-sm">
                                @foreach($booking->services as $service)
                                    <span class="bg-garage-forest/50 text-garage-steel px-2 sm:px-3 py-0.5 sm:py-1 rounded-full border border-garage-neon/20 text-xs">
                                        {{ $service->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex items-center justify-between lg:flex-col lg:text-right lg:ml-6 pt-3 lg:pt-0 border-t lg:border-t-0 border-garage-neon/10">
                            <div class="text-xl sm:text-2xl font-bold text-white font-mono">₱{{ number_format($booking->total_amount, 2) }}</div>
                            <a href="{{ route('staff.booking.detail', $booking->id) }}" 
                                class="inline-flex items-center text-xs sm:text-sm text-white hover:text-white font-semibold transition-colors service-tag lg:mt-2">
                                <span>DETAILS</span>
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 sm:py-16">
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 mx-auto text-garage-steel/30 mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-garage-steel text-base sm:text-lg mb-2 service-tag">NO BOOKINGS FOR TODAY</p>
                    <p class="text-garage-steel/70 text-xs sm:text-sm">Enjoy your day!</p>
                </div>
            @endforelse
        </div>

        <!-- Upcoming Bookings -->
        @if($upcomingBookings->count() > 0)
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-garage-neon/20">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0 mb-4 sm:mb-6">
                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-garage-offwhite service-tag inline">UPCOMING BOOKINGS</h3>
                            <span class="text-garage-steel text-xs sm:text-sm ml-2">(Next 7 Days)</span>
                        </div>
                    </div>
                    <a href="{{ route('staff.calendar') }}" class="text-white hover:text-white text-xs sm:text-sm font-semibold transition-colors service-tag flex items-center flex-shrink-0">
                        <span>VIEW IN CALENDAR</span>
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>

                <!-- Garage Floor Divider -->
                <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4 sm:mb-6"></div>

                @foreach($upcomingBookings as $booking)
                    <div class="border-l-4 {{ $booking->status === 'pending' ? 'border-yellow-400' : 'border-blue-400' }} bg-garage-charcoal/50 rounded-r-lg p-3 sm:p-4 md:p-5 mb-3 sm:mb-4 hover:bg-garage-forest/30 transition-all duration-200">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-2 sm:mb-3">
                                    <span class="text-xs sm:text-sm font-bold text-garage-steel font-mono bg-garage-forest/50 px-2 sm:px-3 py-1 rounded flex-shrink-0">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                    </span>
                                    <span class="text-xs sm:text-sm font-mono text-garage-steel flex-shrink-0">{{ $booking->booking_time }}</span>
                                    <h4 class="font-bold text-garage-offwhite service-tag text-sm sm:text-base break-words">{{ strtoupper($booking->customer->getDisplayName()) }}</h4>
                                    <span class="service-tag px-2 sm:px-3 py-1 text-[10px] sm:text-xs rounded font-bold flex-shrink-0
                                        {{ $booking->status === 'pending' ? 'bg-yellow-400/20 text-yellow-400 border border-yellow-400/30' : '' }}
                                        {{ $booking->status === 'approved' ? 'bg-blue-400/20 text-blue-400 border border-blue-400/30' : '' }}
                                        {{ $booking->status === 'no_show' ? 'bg-orange-500/20 text-orange-400 border border-orange-500/30' : '' }}">
                                        {{ $booking->status === 'no_show' ? 'NOT ARRIVING' : strtoupper($booking->status) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-xs sm:text-sm">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-garage-steel flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <span class="text-garage-offwhite font-semibold break-words">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between lg:flex-col lg:text-right lg:ml-6 pt-3 lg:pt-0 border-t lg:border-t-0 border-garage-neon/10">
                                <div class="text-xl sm:text-2xl font-bold text-white font-mono">₱{{ number_format($booking->total_amount, 2) }}</div>
                                <a href="{{ route('staff.booking.detail', $booking->id) }}" 
                                    class="inline-flex items-center text-xs sm:text-sm text-white hover:text-white font-semibold transition-colors service-tag lg:mt-2">
                                    <span>DETAILS</span>
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
