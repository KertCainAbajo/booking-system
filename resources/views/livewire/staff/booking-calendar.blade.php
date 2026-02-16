<div>
    <!-- Page Header -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 md:p-8 border-l-4 border-garage-neon relative overflow-hidden mb-4 sm:mb-6">
        <!-- Carbon Fiber Pattern Overlay -->
        <div class="absolute inset-0 bg-carbon-fiber opacity-50"></div>
        
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-3 sm:space-x-4 mb-2 sm:mb-3">
                        <!-- Calendar Icon -->
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-garage-offwhite service-tag tracking-wider">BOOKING CALENDAR</h1>
                    </div>
                    <p class="text-garage-steel text-sm sm:text-base md:text-lg ml-11 sm:ml-14 md:ml-16">View customer appointments and bookings</p>
                </div>
                <a href="{{ route('staff.dashboard') }}" 
                    class="flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 bg-garage-forest hover:bg-garage-darkgreen text-white rounded-lg transition-all border border-garage-neon/30 hover:border-garage-neon/50 service-tag text-sm sm:text-base flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="hidden sm:inline">BACK TO DASHBOARD</span>
                    <span class="sm:hidden">BACK</span>
                </a>
            </div>
        </div>
        
        <!-- Garage Floor Marking -->
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-white to-transparent opacity-30"></div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
        <div class="bg-garage-charcoal/50 rounded-lg border border-garage-neon/20 p-3 sm:p-4 md:p-5 text-center hover:border-garage-neon/40 transition-all">
            <div class="text-2xl sm:text-3xl font-bold text-garage-offwhite mb-1 sm:mb-2 font-mono">{{ $stats['total'] }}</div>
            <div class="text-xs text-garage-steel uppercase tracking-wider">Total This Month</div>
        </div>
        <div class="bg-garage-charcoal/50 rounded-lg border border-yellow-500/20 p-3 sm:p-4 md:p-5 text-center hover:border-yellow-500/40 transition-all">
            <div class="text-2xl sm:text-3xl font-bold text-yellow-400 mb-1 sm:mb-2 font-mono">{{ $stats['pending'] }}</div>
            <div class="text-xs text-garage-steel uppercase tracking-wider">Pending</div>
        </div>
        <div class="bg-garage-charcoal/50 rounded-lg border border-blue-500/20 p-3 sm:p-4 md:p-5 text-center hover:border-blue-500/40 transition-all">
            <div class="text-2xl sm:text-3xl font-bold text-blue-400 mb-1 sm:mb-2 font-mono">{{ $stats['approved'] }}</div>
            <div class="text-xs text-garage-steel uppercase tracking-wider">Approved</div>
        </div>
        <div class="bg-garage-charcoal/50 rounded-lg border border-garage-neon/20 p-3 sm:p-4 md:p-5 text-center hover:border-garage-neon/40 transition-all">
            <div class="text-2xl sm:text-3xl font-bold text-garage-neon mb-1 sm:mb-2 font-mono">{{ $stats['completed'] }}</div>
            <div class="text-xs text-garage-steel uppercase tracking-wider">Completed</div>
        </div>
    </div>

    <!-- Calendar Controls -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 mb-4 sm:mb-6 border border-garage-neon/20">
        <div class="flex flex-col gap-4">
            <!-- Month Navigation -->
            <div class="flex items-center justify-center gap-2 sm:gap-4">
                <button wire:click="previousMonth" 
                    class="p-2 sm:p-3 bg-garage-forest hover:bg-garage-neon/20 rounded-lg transition-all border border-garage-neon/20 hover:border-garage-neon/50 text-white flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-garage-offwhite text-center service-tag tracking-wider flex-shrink-0">{{ $monthName }}</h3>
                <button wire:click="nextMonth" 
                    class="p-2 sm:p-3 bg-garage-forest hover:bg-garage-neon/20 rounded-lg transition-all border border-garage-neon/20 hover:border-garage-neon/50 text-white flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <button wire:click="today" 
                    class="px-4 sm:px-6 py-2 sm:py-3 bg-garage-neon hover:bg-garage-neon/80 text-garage-charcoal font-bold rounded-lg transition-all service-tag text-sm sm:text-base flex-shrink-0">
                    TODAY
                </button>
            </div>

            <!-- Filter -->
            <div class="w-full">
                <select wire:model.live="statusFilter" 
                    class="w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm sm:text-base px-3 sm:px-4 py-2 sm:py-3">
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage overflow-hidden border border-garage-neon/20">
        <!-- Day Headers -->
        <div class="grid grid-cols-7 gap-px bg-garage-forest/50">
            <div class="bg-garage-charcoal/70 p-1.5 sm:p-2 md:p-3 text-center font-bold text-garage-neon text-xs sm:text-sm service-tag tracking-wider">SUN</div>
            <div class="bg-garage-charcoal/70 p-1.5 sm:p-2 md:p-3 text-center font-bold text-garage-neon text-xs sm:text-sm service-tag tracking-wider">MON</div>
            <div class="bg-garage-charcoal/70 p-1.5 sm:p-2 md:p-3 text-center font-bold text-garage-neon text-xs sm:text-sm service-tag tracking-wider">TUE</div>
            <div class="bg-garage-charcoal/70 p-1.5 sm:p-2 md:p-3 text-center font-bold text-garage-neon text-xs sm:text-sm service-tag tracking-wider">WED</div>
            <div class="bg-garage-charcoal/70 p-1.5 sm:p-2 md:p-3 text-center font-bold text-garage-neon text-xs sm:text-sm service-tag tracking-wider">THU</div>
            <div class="bg-garage-charcoal/70 p-1.5 sm:p-2 md:p-3 text-center font-bold text-garage-neon text-xs sm:text-sm service-tag tracking-wider">FRI</div>
            <div class="bg-garage-charcoal/70 p-1.5 sm:p-2 md:p-3 text-center font-bold text-garage-neon text-xs sm:text-sm service-tag tracking-wider">SAT</div>
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7 gap-px bg-garage-forest/50">
            @foreach($calendar as $week)
                @foreach($week as $day)
                    @if($day === null)
                        <div class="bg-garage-charcoal/30 min-h-[80px] sm:min-h-[100px] md:min-h-[120px] p-1 sm:p-1.5 md:p-2"></div>
                    @else
                        <div class="bg-garage-charcoal/50 min-h-[80px] sm:min-h-[100px] md:min-h-[120px] p-1 sm:p-1.5 md:p-2 {{ $day['isToday'] ? 'ring-2 ring-garage-neon' : '' }} {{ $day['isPast'] ? 'bg-garage-charcoal/20' : '' }} hover:bg-garage-forest/30 transition">
                            <!-- Day Number -->
                            <div class="flex items-center justify-between mb-0.5 sm:mb-1">
                                <span class="text-xs sm:text-sm font-bold {{ $day['isToday'] ? 'bg-garage-neon text-garage-charcoal rounded-full w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center text-xs' : 'text-garage-offwhite' }}">
                                    {{ $day['day'] }}
                                </span>
                                @if($day['bookings']->count() > 0)
                                    <span class="text-[10px] sm:text-xs bg-garage-neon text-garage-charcoal rounded-full px-1.5 sm:px-2 py-0.5 font-bold font-mono leading-none">
                                        {{ $day['bookings']->count() }}
                                    </span>
                                @endif
                            </div>

                            <!-- Bookings -->
                            <div class="space-y-0.5 sm:space-y-1">
                                @foreach($day['bookings']->take(2) as $booking)
                                    <a href="{{ route('staff.booking.detail', $booking->id) }}" 
                                        class="block text-[10px] sm:text-xs p-1 sm:p-1.5 rounded border
                                        {{ $booking->status === 'pending' ? 'bg-yellow-400/20 border-yellow-400/30 hover:bg-yellow-400/30' : '' }}
                                        {{ $booking->status === 'approved' ? 'bg-blue-400/20 border-blue-400/30 hover:bg-blue-400/30' : '' }}
                                        {{ $booking->status === 'completed' ? 'bg-garage-neon/20 border-garage-neon/30 hover:bg-garage-neon/30' : '' }}
                                        {{ $booking->status === 'cancelled' ? 'bg-red-500/20 border-red-500/30 hover:bg-red-500/30' : '' }}
                                        {{ $booking->status === 'no_show' ? 'bg-orange-500/20 border-orange-500/30 hover:bg-orange-500/30' : '' }}
                                        {{ $booking->status === 'not_available' ? 'bg-gray-500/20 border-gray-500/30 hover:bg-gray-500/30' : '' }}">
                                        <div class="font-bold text-white leading-tight">{{ $booking->booking_time }}</div>
                                        <div class="truncate text-white leading-tight hidden sm:block">{{ $booking->customer->getDisplayName() }}</div>
                                    </a>
                                @endforeach
                                @if($day['bookings']->count() > 2)
                                    <div class="text-[10px] sm:text-xs text-garage-steel font-bold pl-0.5 sm:pl-1 font-mono">
                                        +{{ $day['bookings']->count() - 2 }} more
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>

    <!-- Legend -->
    <div class="mt-4 sm:mt-6 bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-garage-neon/20">
        <h4 class="font-bold text-garage-offwhite mb-3 sm:mb-4 service-tag tracking-wider flex items-center gap-2 text-sm sm:text-base">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
            </svg>
            STATUS LEGEND:
        </h4>
        
        <!-- Garage Floor Divider -->
        <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-3 sm:mb-4"></div>
        
        <div class="flex flex-wrap gap-3 sm:gap-4">
            <div class="flex items-center gap-1.5 sm:gap-2">
                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-yellow-400/20 border-2 border-yellow-400/50 rounded flex-shrink-0"></div>
                <span class="text-xs sm:text-sm text-garage-steel font-semibold">Pending</span>
            </div>
            <div class="flex items-center gap-1.5 sm:gap-2">
                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-blue-400/20 border-2 border-blue-400/50 rounded flex-shrink-0"></div>
                <span class="text-xs sm:text-sm text-garage-steel font-semibold">Approved</span>
            </div>
            <div class="flex items-center gap-1.5 sm:gap-2">
                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-garage-neon/20 border-2 border-garage-neon/50 rounded flex-shrink-0"></div>
                <span class="text-xs sm:text-sm text-garage-steel font-semibold">Completed</span>
            </div>
            <div class="flex items-center gap-1.5 sm:gap-2">
                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-red-500/20 border-2 border-red-500/50 rounded flex-shrink-0"></div>
                <span class="text-xs sm:text-sm text-garage-steel font-semibold">Cancelled</span>
            </div>
            <div class="flex items-center gap-1.5 sm:gap-2">
                <div class="w-5 h-5 sm:w-6 sm:h-6 bg-garage-neon rounded-full flex-shrink-0"></div>
                <span class="text-xs sm:text-sm text-garage-steel font-semibold">Today</span>
            </div>
        </div>
    </div>
</div>
