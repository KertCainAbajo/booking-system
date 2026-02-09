<div>
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 md:p-8 border-l-4 border-garage-neon relative overflow-hidden">
            <!-- Carbon Fiber Pattern Overlay -->
            <div class="absolute inset-0 bg-carbon-fiber opacity-50"></div>
            
            <div class="relative z-10">
                <div class="flex items-center space-x-3 sm:space-x-4 mb-3">
                    <!-- Car + Wrench Icon -->
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                    <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-garage-offwhite service-tag tracking-wider break-words">WELCOME, {{ strtoupper(auth()->user()->name) }}</h1>
                </div>
                <p class="text-garage-steel text-sm sm:text-base md:text-lg ml-11 sm:ml-14 md:ml-16">Your premium auto service control center</p>
            </div>
            
            <!-- Garage Floor Marking -->
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-white to-transparent opacity-30"></div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            <!-- Book Service Card -->
            <a href="{{ route('customer.book') }}" wire:navigate 
               class="group bg-gradient-to-br from-garage-forest to-garage-darkgreen hover:from-garage-neon/20 hover:to-garage-forest rounded-lg p-4 sm:p-6 transition-all duration-300 shadow-garage hover:shadow-neon-green border border-garage-neon/20 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-garage-neon/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg sm:text-xl font-bold text-garage-offwhite mb-2 service-tag">BOOK SERVICE</h3>
                            <p class="text-garage-steel text-xs sm:text-sm">Schedule your next maintenance</p>
                        </div>
                        <!-- Wrench Icon -->
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-white/40 group-hover:text-white transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    
                    <div class="flex items-center text-white font-semibold group-hover:translate-x-2 transition-transform">
                        <span class="service-tag text-sm">START NOW</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Track Booking Card -->
            <a href="{{ route('customer.tracker') }}" wire:navigate 
               class="group bg-gradient-to-br from-garage-forest to-garage-darkgreen hover:from-garage-neon/20 hover:to-garage-forest rounded-lg p-6 transition-all duration-300 shadow-garage hover:shadow-neon-green border border-garage-neon/20 relative overflow-hidden pulse-neon">
                <div class="absolute top-0 right-0 w-32 h-32 bg-garage-emerald/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-garage-offwhite mb-2 service-tag">TRACK SERVICE</h3>
                            <p class="text-garage-steel text-sm">Monitor service progress live</p>
                        </div>
                        <!-- Clipboard/Status Icon -->
                        <svg class="w-12 h-12 text-white/60 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    
                    <div class="flex items-center text-white font-semibold group-hover:translate-x-2 transition-transform">
                        <span class="service-tag text-sm">CHECK STATUS</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Service History Card -->
            <a href="{{ route('customer.history') }}" wire:navigate 
               class="group bg-gradient-to-br from-garage-forest to-garage-darkgreen hover:from-garage-neon/20 hover:to-garage-forest rounded-lg p-6 transition-all duration-300 shadow-garage hover:shadow-neon-green border border-garage-neon/20 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-garage-offwhite mb-2 service-tag">SERVICE LOG</h3>
                            <p class="text-garage-steel text-sm">View maintenance records</p>
                        </div>
                        <!-- History/Clock Icon -->
                        <svg class="w-12 h-12 text-white/40 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    
                    <div class="flex items-center text-white font-semibold group-hover:translate-x-2 transition-transform">
                        <span class="service-tag text-sm">VIEW HISTORY</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <!-- All Bookings -->
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-garage-offwhite service-tag">SERVICE RECORDS</h2>
                </div>
                @if($allBookings->count() > 0)
                    <a href="{{ route('customer.tracker') }}" wire:navigate class="text-white hover:text-white text-sm font-semibold transition-colors service-tag flex items-center">
                        <span>ACTIVE SERVICES</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                @endif
            </div>

            <!-- Garage Floor Divider -->
            <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-6"></div>

            @forelse($allBookings as $booking)
                <div class="border-l-4 {{ $booking->status === 'pending' ? 'border-white' : ($booking->status === 'approved' ? 'border-white' : ($booking->status === 'completed' ? 'border-white' : 'border-red-500')) }} bg-garage-charcoal/50 rounded-r-lg p-5 mb-4 hover:bg-garage-forest/30 transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="service-tag font-bold text-garage-offwhite text-lg">SERVICE #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <span class="service-tag px-3 py-1 text-xs rounded font-bold
                                    {{ $booking->status === 'pending' ? 'bg-white/20 text-white border border-white/30' : '' }}
                                    {{ $booking->status === 'approved' ? 'bg-white/20 text-white border border-white/30' : '' }}
                                    {{ $booking->status === 'completed' ? 'bg-white/20 text-white border border-white/30' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : '' }}
                                ">
                                    {{ strtoupper(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </div>
                            
                            <!-- Service Receipt Style -->
                            <div class="space-y-2 text-sm text-garage-steel mb-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-mono">{{ $booking->booking_date->format('M d, Y') }}</span>
                                    <span class="text-garage-neon">|</span>
                                    <svg class="w-4 h-4 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-mono">{{ $booking->booking_time->format('h:i A') }}</span>
                                </div>
                            </div>
                            
                            <!-- License Plate Style Vehicle Info -->
                            <div class="mb-3">
                                <div class="inline-flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-garage-steel" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <span class="text-garage-offwhite font-semibold">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</span>
                                    <span class="plate-number text-sm">{{ $booking->vehicle->plate_number }}</span>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap gap-2 text-sm">
                                @foreach($booking->services as $service)
                                    <span class="bg-garage-forest/50 text-garage-steel px-3 py-1 rounded-full border border-garage-neon/20">
                                        {{ $service->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="text-right ml-6">
                            <div class="text-2xl font-bold text-white mb-2 font-mono">₱{{ number_format($booking->total_amount, 2) }}</div>
                            @if(in_array($booking->status, ['pending', 'approved']))
                                <a href="{{ route('customer.tracker') }}" wire:navigate 
                                   class="inline-flex items-center text-sm text-white hover:text-white font-semibold transition-colors service-tag">
                                    <span>TRACK</span>
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <svg class="w-24 h-24 mx-auto text-garage-steel/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-garage-steel text-lg mb-6">No service records found</p>
                    <a href="{{ route('customer.book') }}" wire:navigate 
                       class="inline-flex items-center px-6 py-3 bg-garage-neon hover:bg-garage-emerald text-garage-black font-bold rounded-lg transition-all shadow-neon-green service-tag">
                        <span>BOOK FIRST SERVICE</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
