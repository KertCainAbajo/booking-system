<div>
    <!-- Page Header -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-8 border-l-4 border-garage-neon relative overflow-hidden mb-6">
        <!-- Carbon Fiber Pattern Overlay -->
        <div class="absolute inset-0 bg-carbon-fiber opacity-50"></div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-4 mb-3">
                <!-- Monitoring Icon -->
                <svg class="w-12 h-12 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h1 class="text-4xl font-bold text-garage-offwhite service-tag tracking-wider">SYSTEM MONITORING</h1>
            </div>
            <p class="text-garage-steel text-lg ml-16">Overview of system performance and activity</p>
        </div>
        
        <!-- Garage Floor Marking -->
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-white to-transparent opacity-30"></div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
            <div class="text-sm text-garage-steel uppercase tracking-wider mb-2">Total Users</div>
            <div class="text-3xl font-bold text-garage-offwhite font-mono">{{ $stats['total_users'] }}</div>
        </div>
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
            <div class="text-sm text-garage-steel uppercase tracking-wider mb-2">Total Bookings</div>
            <div class="text-3xl font-bold text-garage-offwhite font-mono">{{ $stats['total_bookings'] }}</div>
            <div class="text-xs text-orange-400 mt-1">{{ $stats['pending_bookings'] }} pending</div>
        </div>
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
            <div class="text-sm text-garage-steel uppercase tracking-wider mb-2">Active Services</div>
            <div class="text-3xl font-bold text-garage-offwhite font-mono">{{ $stats['active_services'] }}</div>
            <div class="text-xs text-garage-steel mt-1">of {{ $stats['total_services'] }} total</div>
        </div>
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
            <div class="text-sm text-garage-steel uppercase tracking-wider mb-2">Total Revenue</div>
            <div class="text-2xl font-bold text-white font-mono">₱{{ number_format($stats['total_revenue'], 2) }}</div>
            <div class="text-xs text-white mt-1">₱{{ number_format($stats['pending_payments'], 2) }} pending</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Bookings by Status -->
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
            <h3 class="text-xl font-bold text-garage-neon mb-4 service-tag">BOOKINGS BY STATUS</h3>
            
            <!-- Garage Floor Divider -->
            <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4"></div>
            
            <div class="space-y-3">
                @foreach($bookingsByStatus as $status)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @if($status->status === 'pending')
                                <svg class="w-5 h-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($status->status === 'completed')
                                <svg class="w-5 h-5 text-garage-neon mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($status->status === 'no_show')
                                <svg class="w-5 h-5 text-orange-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($status->status === 'approved')
                                <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            @elseif($status->status === 'cancelled')
                                <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <span class="inline-block w-3 h-3 rounded-full mr-2 bg-garage-steel"></span>
                            @endif
                            <span class="text-garage-offwhite">{{ $status->status === 'no_show' ? 'Not Arriving' : ucfirst(str_replace('_', ' ', $status->status)) }}</span>
                        </div>
                        <span class="font-bold font-mono {{ in_array($status->status, ['completed', 'no_show', 'pending']) ? 'text-white' : 'text-garage-neon' }}">{{ $status->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
            <h3 class="text-xl font-bold text-garage-neon mb-4 service-tag">RECENT USERS</h3>
            
            <!-- Garage Floor Divider -->
            <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4"></div>
            
            <div class="space-y-3">
                @foreach($recentUsers as $user)
                    <div class="flex items-center justify-between border-b border-garage-neon/10 pb-2">
                        <div>
                            <div class="font-medium text-garage-offwhite">{{ $user->name }}</div>
                            <div class="text-sm text-garage-steel">{{ $user->email }}</div>
                        </div>
                        <span class="px-3 py-1 text-xs rounded-full bg-garage-neon/20 text-garage-neon border border-garage-neon/30 font-semibold">
                            {{ ucfirst(str_replace('_', ' ', $user->role->name)) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
        <h3 class="text-xl font-bold text-garage-neon mb-4 service-tag">RECENT BOOKINGS</h3>
        
        <!-- Garage Floor Divider -->
        <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4"></div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-garage-neon/20">
                <thead class="bg-garage-charcoal/70">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Vehicle</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-garage-charcoal/30 divide-y divide-garage-neon/10">
                    @foreach($recentBookings as $booking)
                        <tr class="hover:bg-garage-forest/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-garage-neon font-mono">#{{ $booking->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-garage-offwhite">{{ $booking->customer->getDisplayName() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-garage-steel">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-garage-steel">{{ $booking->booking_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs rounded-full border font-semibold
                                    {{ $booking->status === 'pending' ? 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30' : '' }}
                                    {{ $booking->status === 'approved' ? 'bg-blue-500/20 text-blue-400 border-blue-500/30' : '' }}
                                    {{ $booking->status === 'completed' ? 'bg-garage-neon/20 text-garage-neon border-garage-neon/30' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-red-500/20 text-red-400 border-red-500/30' : '' }}
                                    {{ $booking->status === 'no_show' ? 'bg-orange-500/20 text-orange-400 border-orange-500/30' : '' }}
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-white font-mono">₱{{ number_format($booking->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
