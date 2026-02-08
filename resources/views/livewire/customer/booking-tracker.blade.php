<div>
    <!-- Flash Messages -->
    <x-success-modal />

    @if (session()->has('error'))
        <div class="mb-6 bg-red-900/50 border-l-4 border-red-500 px-6 py-4 rounded-lg shadow-lg backdrop-blur-sm">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="font-semibold text-red-200">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Header with Back Button -->
    <div class="mb-6 flex justify-between items-start">
        <div>
            <h2 class="text-3xl font-bold text-garage-offwhite service-tag mb-2">SERVICE BAY TRACKER</h2>
            <p class="text-garage-steel">Real-time monitoring of your active service bookings</p>
        </div>
        <a href="{{ route('customer.dashboard') }}" wire:navigate 
           class="inline-flex items-center px-5 py-2.5 bg-garage-forest hover:bg-garage-darkgreen text-garage-offwhite font-semibold rounded-lg transition-all border border-garage-neon/20 hover:border-garage-neon/40">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="service-tag">BACK</span>
        </a>
    </div>

    @forelse($activeBookings as $booking)
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage mb-6 overflow-hidden border border-garage-neon/30 pulse-neon">
            <!-- Header - Service Tag Style -->
            <div class="bg-gradient-to-r from-garage-forest to-garage-darkgreen border-b-2 border-garage-neon/40 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-3 mb-1">
                            <svg class="w-6 h-6 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="text-2xl font-bold text-garage-offwhite service-tag">SERVICE ORDER #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</h3>
                        </div>
                        <p class="text-garage-steel text-sm font-mono ml-9">
                            {{ $booking->booking_date }} at {{ $booking->booking_time }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="service-tag px-4 py-2 text-sm rounded-lg font-bold
                            {{ $booking->status === 'pending' ? 'bg-amber-400/20 text-amber-300 border-2 border-amber-400/50' : '' }}
                            {{ $booking->status === 'approved' ? 'bg-garage-neon/20 text-garage-neon border-2 border-garage-neon/50' : '' }}
                            {{ $booking->status === 'completed' ? 'bg-garage-emerald/20 text-garage-emerald border-2 border-garage-emerald/50' : '' }}
                            {{ $booking->status === 'no_show' ? 'bg-orange-500/20 text-orange-400 border-2 border-orange-500/50' : '' }}
                            {{ $booking->status === 'cancelled' ? 'bg-red-500/20 text-red-400 border-2 border-red-500/50' : '' }}
                        ">
                            {{ strtoupper(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Vehicle Info - License Plate Highlighted -->
                <div class="mb-6">
                    <h4 class="font-semibold text-garage-steel text-sm mb-3 service-tag flex items-center">
                        <svg class="w-5 h-5 mr-2 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        VEHICLE INFORMATION
                    </h4>
                    <div class="flex items-center text-garage-offwhite bg-garage-charcoal/50 rounded-lg p-4 border border-garage-neon/20">
                        <svg class="w-12 h-12 text-garage-neon mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        <div class="flex-1">
                            <div class="text-xl font-bold">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</div>
                            <div class="text-garage-steel text-sm">{{ $booking->vehicle->year }} Model</div>
                        </div>
                        <div class="plate-number text-base ml-4">{{ $booking->vehicle->plate_number }}</div>
                    </div>
                </div>

                <!-- Services - Service Receipt Style -->
                <div class="mb-6">
                    <h4 class="font-semibold text-garage-steel text-sm mb-3 service-tag flex items-center">
                        <svg class="w-5 h-5 mr-2 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        SERVICES REQUESTED
                    </h4>
                    <div class="space-y-2">
                        @foreach($booking->services as $service)
                            <div class="flex items-center justify-between bg-garage-charcoal/50 border border-garage-neon/20 rounded-lg p-4 hover:border-garage-neon/40 transition-colors">
                                <div class="flex items-center flex-1">
                                    <div class="w-2 h-2 bg-garage-neon rounded-full mr-3"></div>
                                    <div>
                                        <div class="font-semibold text-garage-offwhite">{{ $service->name }}</div>
                                        <div class="text-sm text-garage-steel flex items-center mt-1">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $service->estimated_duration }} min
                                        </div>
                                    </div>
                                </div>
                                <div class="font-bold text-white font-mono text-lg">₱{{ number_format($service->pivot->price, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Progress Tracker - Garage Bay Style -->
                <div class="mb-6 bg-garage-charcoal/50 rounded-lg p-5 border border-garage-neon/20">
                    <h4 class="font-semibold text-garage-steel text-sm mb-6 service-tag flex items-center">
                        <svg class="w-5 h-5 mr-2 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        SERVICE PROGRESS
                    </h4>
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-lg
                                {{ in_array($booking->status, ['pending', 'approved', 'completed']) ? 'bg-amber-400 text-garage-black shadow-[0_0_20px_rgba(251,191,36,0.5)]' : 'bg-garage-steel/20 text-garage-steel' }}">
                                1
                            </div>
                            <div class="text-xs mt-2 text-center font-semibold service-tag {{ in_array($booking->status, ['pending', 'approved', 'completed']) ? 'text-amber-300' : 'text-garage-steel' }}">
                                PENDING
                            </div>
                        </div>
                        <div class="h-1 flex-1 mx-2 
                            {{ in_array($booking->status, ['approved', 'completed']) ? 'bg-gradient-to-r from-amber-400 to-garage-neon' : 'bg-garage-steel/20' }}">
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-lg
                                {{ in_array($booking->status, ['approved', 'completed']) ? 'bg-garage-neon text-garage-black shadow-neon-green' : 'bg-garage-steel/20 text-garage-steel' }}">
                                2
                            </div>
                            <div class="text-xs mt-2 text-center font-semibold service-tag {{ in_array($booking->status, ['approved', 'completed']) ? 'text-garage-neon' : 'text-garage-steel' }}">
                                IN SERVICE
                            </div>
                        </div>
                        <div class="h-1 flex-1 mx-2 
                            {{ $booking->status === 'completed' ? 'bg-gradient-to-r from-garage-neon to-garage-emerald' : 'bg-garage-steel/20' }}">
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-lg
                                {{ $booking->status === 'completed' ? 'bg-garage-emerald text-garage-black shadow-[0_0_20px_rgba(45,212,191,0.5)]' : 'bg-garage-steel/20 text-garage-steel' }}">
                                3
                            </div>
                            <div class="text-xs mt-2 text-center font-semibold service-tag {{ $booking->status === 'completed' ? 'text-garage-emerald' : 'text-garage-steel' }}">
                                COMPLETED
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Staff Assignment -->
                @if($booking->assignedStaff)
                    <div class="mb-6 bg-garage-neon/10 rounded-lg p-4 border border-garage-neon/30">
                        <h4 class="font-semibold text-garage-steel text-sm mb-3 service-tag flex items-center">
                            <svg class="w-5 h-5 mr-2 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            ASSIGNED TECHNICIAN
                        </h4>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-garage-forest to-garage-neon rounded-full flex items-center justify-center text-white font-bold text-xl mr-4 ring-2 ring-garage-neon/50">
                                {{ strtoupper(substr($booking->assignedStaff->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-bold text-garage-offwhite text-lg">{{ $booking->assignedStaff->name }}</div>
                                <div class="text-sm text-garage-steel font-mono">{{ $booking->assignedStaff->phone }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Notes -->
                @if($booking->notes)
                    <div class="mb-6">
                        <h4 class="font-semibold text-garage-steel text-sm mb-2 service-tag">SERVICE NOTES</h4>
                        <p class="text-garage-offwhite bg-garage-charcoal/50 rounded-lg p-4 border border-garage-neon/20 italic">{{ $booking->notes }}</p>
                    </div>
                @endif

                <!-- Total - Receipt Style -->
                <div class="border-t-2 border-garage-neon/30 pt-5">
                    <div class="flex items-center justify-between bg-garage-charcoal/50 rounded-lg p-5 border-2 border-garage-neon/40">
                        <span class="font-bold text-garage-offwhite text-xl service-tag">TOTAL AMOUNT</span>
                        <span class="font-bold text-garage-neon text-3xl font-mono">₱{{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                </div>

                <!-- Cancel Button -->
                @if(in_array($booking->status, ['pending', 'approved']))
                    <div class="mt-6 pt-5 border-t border-garage-steel/20 flex justify-center">
                        <button 
                            wire:click="cancelBooking({{ $booking->id }})" 
                            wire:confirm="Are you sure you want to cancel this service booking?"
                            class="bg-red-600/80 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-lg transition-all shadow-lg hover:shadow-[0_0_20px_rgba(239,68,68,0.4)] flex items-center service-tag border border-red-500/50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            CANCEL SERVICE
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-16 text-center border border-garage-neon/20">
            <svg class="w-32 h-32 mx-auto text-garage-steel/30 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            <h3 class="text-2xl font-bold text-garage-offwhite mb-3 service-tag">NO ACTIVE SERVICES</h3>
            <p class="text-garage-steel text-lg mb-8">All service bays are clear. No active bookings at the moment.</p>
            <a href="{{ route('customer.booking') }}" wire:navigate
                class="inline-flex items-center bg-garage-neon hover:bg-garage-emerald text-garage-black font-bold px-8 py-4 rounded-lg transition-all shadow-neon-green service-tag">
                <span>SCHEDULE NEW SERVICE</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    @endforelse
</div>
