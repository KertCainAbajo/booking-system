<div>
    <div class="space-y-4 sm:space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 md:p-8 border-l-4 border-garage-neon relative overflow-hidden">
            <!-- Carbon Fiber Pattern Overlay -->
            <div class="absolute inset-0 bg-carbon-fiber opacity-50"></div>
            
            <div class="relative z-10">
                <div class="flex items-center space-x-2 sm:space-x-4 mb-3">
                    <!-- Admin Icon -->
                    <svg class="w-8 h-8 sm:w-12 sm:h-12 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-garage-offwhite service-tag tracking-wider">IT ADMIN DASHBOARD</h1>
                </div>
                <p class="text-garage-steel text-sm sm:text-base md:text-lg ml-0 sm:ml-16">System administration and monitoring</p>
            </div>
            
            <!-- Garage Floor Marking -->
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-white to-transparent opacity-30"></div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 sm:gap-6">
            <!-- Total Users -->
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-blue-500/20 hover:border-blue-500/40 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-xs text-garage-steel uppercase tracking-wider mb-2">Total Users</div>
                        <div class="text-3xl font-bold text-blue-400 font-mono">{{ $stats['total_users'] }}</div>
                    </div>
                    <svg class="w-12 h-12 text-blue-400/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-blue-500/30 to-transparent"></div>
            </div>

            <!-- Total Bookings -->
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-garage-neon/20 hover:border-garage-neon/40 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-xs text-garage-steel uppercase tracking-wider mb-2">Total Bookings</div>
                        <div class="text-3xl font-bold text-garage-neon font-mono">{{ $stats['total_bookings'] }}</div>
                    </div>
                    <svg class="w-12 h-12 text-garage-neon/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent"></div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-garage-neon/20 hover:border-garage-neon/40 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-xs text-garage-steel uppercase tracking-wider mb-2">Total Revenue</div>
                        <div class="text-3xl font-bold text-garage-neon font-mono">₱{{ number_format($stats['total_revenue'], 2) }}</div>
                    </div>
                    <svg class="w-12 h-12 text-garage-neon/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent"></div>
            </div>

            <!-- Services Available -->
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-purple-500/20 hover:border-purple-500/40 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-xs text-garage-steel uppercase tracking-wider mb-2">Services Available</div>
                        <div class="text-3xl font-bold text-purple-400 font-mono">{{ $stats['total_services'] }}</div>
                    </div>
                    <svg class="w-12 h-12 text-purple-400/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-purple-500/30 to-transparent"></div>
            </div>

            <!-- Inventory Items -->
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-orange-500/20 hover:border-orange-500/40 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-xs text-garage-steel uppercase tracking-wider mb-2">Inventory Items</div>
                        <div class="text-3xl font-bold text-orange-400 font-mono">{{ $stats['total_inventory'] }}</div>
                    </div>
                    <svg class="w-12 h-12 text-orange-400/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-orange-500/30 to-transparent"></div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-garage-neon/20">
            <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <h3 class="text-lg sm:text-xl font-bold text-garage-offwhite service-tag">QUICK ACTIONS</h3>
            </div>

            <!-- Garage Floor Divider -->
            <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4 sm:mb-6"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                <!-- Manage Users -->
                <a href="{{ route('admin.users') }}" 
                   class="group bg-gradient-to-br from-garage-forest to-garage-darkgreen hover:from-garage-neon/20 hover:to-garage-forest rounded-lg p-4 sm:p-6 transition-all duration-300 shadow-garage hover:shadow-neon-green border border-garage-neon/20 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-garage-neon/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                    
                    <div class="relative z-10 text-center">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-white/40 group-hover:text-white transition-colors mx-auto mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <div class="font-bold text-sm sm:text-base text-garage-offwhite service-tag">MANAGE USERS</div>
                    </div>
                </a>

                <!-- Manage Services -->
                <a href="{{ route('admin.services') }}" 
                   class="group bg-gradient-to-br from-garage-forest to-garage-darkgreen hover:from-garage-neon/20 hover:to-garage-forest rounded-lg p-4 sm:p-6 transition-all duration-300 shadow-garage hover:shadow-neon-green border border-garage-neon/20 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-garage-neon/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                    
                    <div class="relative z-10 text-center">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-white/40 group-hover:text-white transition-colors mx-auto mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div class="font-bold text-sm sm:text-base text-garage-offwhite service-tag">MANAGE SERVICES</div>
                    </div>
                </a>

                <!-- System Monitoring -->
                <a href="{{ route('admin.monitoring') }}" 
                   class="group bg-gradient-to-br from-garage-forest to-garage-darkgreen hover:from-garage-neon/20 hover:to-garage-forest rounded-lg p-4 sm:p-6 transition-all duration-300 shadow-garage hover:shadow-neon-green border border-garage-neon/20 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-garage-neon/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                    
                    <div class="relative z-10 text-center">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-white/40 group-hover:text-white transition-colors mx-auto mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <div class="font-bold text-sm sm:text-base text-garage-offwhite service-tag">SYSTEM MONITORING</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
