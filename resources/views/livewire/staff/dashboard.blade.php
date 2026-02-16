<div wire:poll.10s>
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 md:p-8 border-l-4 border-garage-neon relative overflow-hidden">
            <!-- Carbon Fiber Pattern Overlay -->
            <div class="absolute inset-0 bg-carbon-fiber opacity-50"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3 sm:space-x-4">
                        <!-- Wrench + Clipboard Icon -->
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-garage-offwhite service-tag tracking-wider">STAFF PANEL</h1>
                    </div>
                    <!-- Live Update Indicator -->
                    <div class="flex items-center gap-2">
                        <button wire:click="refreshDashboard" class="flex items-center gap-2 bg-garage-charcoal/50 px-3 py-1.5 rounded-lg border border-garage-neon/20 hover:border-garage-neon/40 transition-all group">
                            <svg class="w-3 h-3 text-garage-neon group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span class="text-xs text-garage-steel font-mono">REFRESH</span>
                        </button>
                        <div class="flex items-center gap-2 bg-garage-charcoal/50 px-3 py-1.5 rounded-lg border border-garage-neon/20">
                            <div class="w-2 h-2 bg-garage-neon rounded-full animate-pulse"></div>
                            <span class="text-xs text-garage-steel font-mono">LIVE</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-garage-steel text-sm sm:text-base md:text-lg ml-11 sm:ml-14 md:ml-16">Welcome back! Here's your overview for today</p>
                    @if($lastUpdateTime ?? false)
                        <span class="text-xs text-garage-steel/70 font-mono">Updated: {{ $lastUpdateTime }}</span>
                    @endif
                </div>
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
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4 sm:mb-6">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <h3 class="text-lg sm:text-xl font-bold text-garage-offwhite service-tag">TODAY'S OVERVIEW</h3>
                </div>
                
                <!-- Comparison with Yesterday -->
                @if($yesterdayTotal > 0 || $todayStats['total'] > 0)
                    <div class="flex items-center gap-2 bg-garage-charcoal/50 px-3 py-2 rounded-lg border {{ $todayStats['change_direction'] === 'up' ? 'border-garage-neon/30' : ($todayStats['change_direction'] === 'down' ? 'border-red-500/30' : 'border-garage-steel/30') }}">
                        @if($todayStats['change_direction'] === 'up')
                            <svg class="w-4 h-4 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span class="text-xs font-bold text-garage-neon">+{{ $todayStats['change_percent'] }}% vs Yesterday</span>
                        @elseif($todayStats['change_direction'] === 'down')
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            </svg>
                            <span class="text-xs font-bold text-red-400">{{ $todayStats['change_percent'] }}% vs Yesterday</span>
                        @else
                            <svg class="w-4 h-4 text-garage-steel" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"></path>
                            </svg>
                            <span class="text-xs font-bold text-garage-steel">Same as Yesterday</span>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Garage Floor Divider -->
            <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-6"></div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Bar Chart Section -->
                <div class="bg-garage-charcoal/30 rounded-lg border border-garage-neon/20 p-4">
                    <h4 class="text-sm font-bold text-garage-offwhite mb-4 uppercase tracking-wider">Booking Status Distribution</h4>
                    <div class="space-y-3">
                        <!-- Total Bar -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs text-garage-steel uppercase">Total</span>
                                <span class="text-xs font-bold text-garage-offwhite">{{ $todayStats['total'] }}</span>
                            </div>
                            <div class="h-8 bg-garage-charcoal/50 rounded-lg overflow-hidden border border-garage-neon/10">
                                <div class="h-full bg-gradient-to-r from-garage-neon to-garage-neon/70 flex items-center justify-end pr-2 transition-all duration-1000" style="width: 100%">
                                    <span class="text-xs font-bold text-garage-charcoal">100%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Bar -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs text-garage-steel uppercase">Pending</span>
                                <span class="text-xs font-bold text-yellow-400">{{ $todayStats['pending'] }} ({{ $todayStats['pending_percent'] }}%)</span>
                            </div>
                            <div class="h-8 bg-garage-charcoal/50 rounded-lg overflow-hidden border border-yellow-500/10">
                                <div class="h-full bg-gradient-to-r from-yellow-400 to-yellow-500 flex items-center justify-end pr-2 transition-all duration-1000" style="width: {{ min($todayStats['pending_percent'], 100) }}%">
                                    @if($todayStats['pending_percent'] > 10)
                                        <span class="text-xs font-bold text-garage-charcoal">{{ $todayStats['pending_percent'] }}%</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Approved Bar -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs text-garage-steel uppercase">Approved</span>
                                <span class="text-xs font-bold text-blue-400">{{ $todayStats['approved'] }} ({{ $todayStats['approved_percent'] }}%)</span>
                            </div>
                            <div class="h-8 bg-garage-charcoal/50 rounded-lg overflow-hidden border border-blue-500/10">
                                <div class="h-full bg-gradient-to-r from-blue-400 to-blue-500 flex items-center justify-end pr-2 transition-all duration-1000" style="width: {{ min($todayStats['approved_percent'], 100) }}%">
                                    @if($todayStats['approved_percent'] > 10)
                                        <span class="text-xs font-bold text-white">{{ $todayStats['approved_percent'] }}%</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Completed Bar -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs text-garage-steel uppercase">Completed</span>
                                <span class="text-xs font-bold text-garage-neon">{{ $todayStats['completed'] }} ({{ $todayStats['completed_percent'] }}%)</span>
                            </div>
                            <div class="h-8 bg-garage-charcoal/50 rounded-lg overflow-hidden border border-garage-neon/10">
                                <div class="h-full bg-gradient-to-r from-garage-neon to-green-400 flex items-center justify-end pr-2 transition-all duration-1000" style="width: {{ min($todayStats['completed_percent'], 100) }}%">
                                    @if($todayStats['completed_percent'] > 10)
                                        <span class="text-xs font-bold text-garage-charcoal">{{ $todayStats['completed_percent'] }}%</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Cancelled Bar -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs text-garage-steel uppercase">Cancelled</span>
                                <span class="text-xs font-bold text-red-400">{{ $todayStats['cancelled'] }} ({{ $todayStats['cancelled_percent'] }}%)</span>
                            </div>
                            <div class="h-8 bg-garage-charcoal/50 rounded-lg overflow-hidden border border-red-500/10">
                                <div class="h-full bg-gradient-to-r from-red-400 to-red-500 flex items-center justify-end pr-2 transition-all duration-1000" style="width: {{ min($todayStats['cancelled_percent'], 100) }}%">
                                    @if($todayStats['cancelled_percent'] > 10)
                                        <span class="text-xs font-bold text-white">{{ $todayStats['cancelled_percent'] }}%</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Donut Chart & KPIs Section -->
                <div class="space-y-4">
                    <!-- Donut Chart -->
                    <div class="bg-garage-charcoal/30 rounded-lg border border-garage-neon/20 p-4">
                        <h4 class="text-sm font-bold text-garage-offwhite mb-4 uppercase tracking-wider">Status Breakdown</h4>
                        <div class="flex items-center justify-center">
                            <div class="relative w-48 h-48">
                                <!-- SVG Donut Chart -->
                                @php
                                    $total = $todayStats['total'] ?: 1;
                                    $pending = $todayStats['pending'];
                                    $approved = $todayStats['approved'];
                                    $completed = $todayStats['completed'];
                                    $cancelled = $todayStats['cancelled'];
                                    
                                    $radius = 70;
                                    $circumference = 2 * pi() * $radius;
                                    
                                    $pendingAngle = ($pending / $total) * 360;
                                    $approvedAngle = ($approved / $total) * 360;
                                    $completedAngle = ($completed / $total) * 360;
                                    $cancelledAngle = ($cancelled / $total) * 360;
                                    
                                    $pendingDash = ($pending / $total) * $circumference;
                                    $approvedDash = ($approved / $total) * $circumference;
                                    $completedDash = ($completed / $total) * $circumference;
                                    $cancelledDash = ($cancelled / $total) * $circumference;
                                    
                                    $pendingOffset = 0;
                                    $approvedOffset = $pendingDash;
                                    $completedOffset = $pendingDash + $approvedDash;
                                    $cancelledOffset = $pendingDash + $approvedDash + $completedDash;
                                @endphp
                                
                                <svg viewBox="0 0 160 160" class="transform -rotate-90">
                                    <!-- Pending -->
                                    <circle cx="80" cy="80" r="{{ $radius }}" fill="none" stroke="#facc15" stroke-width="20" 
                                            stroke-dasharray="{{ $pendingDash }} {{ $circumference }}"
                                            stroke-dashoffset="-{{ $pendingOffset }}" class="transition-all duration-1000"/>
                                    <!-- Approved -->
                                    <circle cx="80" cy="80" r="{{ $radius }}" fill="none" stroke="#60a5fa" stroke-width="20" 
                                            stroke-dasharray="{{ $approvedDash }} {{ $circumference }}"
                                            stroke-dashoffset="-{{ $approvedOffset }}" class="transition-all duration-1000"/>
                                    <!-- Completed -->
                                    <circle cx="80" cy="80" r="{{ $radius }}" fill="none" stroke="#39ff14" stroke-width="20" 
                                            stroke-dasharray="{{ $completedDash }} {{ $circumference }}"
                                            stroke-dashoffset="-{{ $completedOffset }}" class="transition-all duration-1000"/>
                                    <!-- Cancelled -->
                                    <circle cx="80" cy="80" r="{{ $radius }}" fill="none" stroke="#f87171" stroke-width="20" 
                                            stroke-dasharray="{{ $cancelledDash }} {{ $circumference }}"
                                            stroke-dashoffset="-{{ $cancelledOffset }}" class="transition-all duration-1000"/>
                                </svg>
                                
                                <!-- Center Text -->
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <div class="text-3xl font-bold text-garage-neon font-mono">{{ $todayStats['total'] }}</div>
                                    <div class="text-xs text-garage-steel uppercase">Total</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Key Metrics Grid -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gradient-to-br from-garage-forest/30 to-garage-charcoal/50 rounded-lg border border-garage-neon/30 p-3">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-3 h-3 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-[10px] text-garage-steel uppercase tracking-wider">Success</span>
                            </div>
                            <div class="text-xl font-bold text-garage-neon font-mono">{{ $todayStats['success_rate'] }}%</div>
                        </div>

                        <div class="bg-gradient-to-br from-garage-forest/30 to-garage-charcoal/50 rounded-lg border border-blue-500/30 p-3">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-3 h-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-[10px] text-garage-steel uppercase tracking-wider">Complete</span>
                            </div>
                            <div class="text-xl font-bold text-blue-400 font-mono">{{ $todayStats['completion_rate'] }}%</div>
                        </div>

                        <div class="bg-gradient-to-br from-garage-forest/30 to-garage-charcoal/50 rounded-lg border border-purple-500/30 p-3">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-3 h-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-[10px] text-garage-steel uppercase tracking-wider">Per Hour</span>
                            </div>
                            <div class="text-xl font-bold text-purple-400 font-mono">{{ $todayStats['avg_per_hour'] }}</div>
                        </div>

                        <div class="bg-gradient-to-br from-garage-forest/30 to-garage-charcoal/50 rounded-lg border border-orange-500/30 p-3">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-3 h-3 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                                <span class="text-[10px] text-garage-steel uppercase tracking-wider">Yesterday</span>
                            </div>
                            <div class="text-xl font-bold text-orange-400 font-mono">{{ $yesterdayTotal }}</div>
                        </div>
                    </div>
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
                                    {{ $booking->status === 'no_show' ? 'bg-orange-500/20 text-orange-400 border border-orange-500/30' : '' }}
                                    {{ $booking->status === 'not_available' ? 'bg-gray-500/20 text-gray-400 border border-gray-500/30' : '' }}">
                                    {{ $booking->status === 'no_show' ? 'NOT ARRIVING' : ($booking->status === 'not_available' ? 'NOT AVAILABLE' : strtoupper($booking->status)) }}
                                </span>
                                @if($booking->marked_as_late)
                                    <span class="service-tag px-2 sm:px-3 py-1 text-[10px] sm:text-xs rounded font-bold flex-shrink-0 bg-orange-500/20 text-orange-400 border border-orange-500/30 animate-pulse flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        RUNNING LATE
                                        @if($booking->estimated_arrival_time)
                                            - ETA: {{ $booking->estimated_arrival_time->format('h:i A') }}
                                        @endif
                                    </span>
                                @endif
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
                                    @if($booking->marked_as_late)
                                        <span class="service-tag px-2 sm:px-3 py-1 text-[10px] sm:text-xs rounded font-bold flex-shrink-0 bg-orange-500/20 text-orange-400 border border-orange-500/30 animate-pulse flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            RUNNING LATE
                                            @if($booking->estimated_arrival_time)
                                                - ETA: {{ $booking->estimated_arrival_time->format('h:i A') }}
                                            @endif
                                        </span>
                                    @endif
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
