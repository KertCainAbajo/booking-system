<div>
    <!-- Header with Back Button -->
    <div class="mb-6 flex justify-between items-start">
        <div>
            <h2 class="text-3xl font-bold text-garage-offwhite service-tag mb-2">MAINTENANCE LOG</h2>
            <p class="text-garage-steel">Complete history of your vehicle service records</p>
        </div>
        <a href="{{ route('customer.dashboard') }}" wire:navigate 
           class="inline-flex items-center px-5 py-2.5 bg-garage-forest hover:bg-garage-darkgreen text-garage-offwhite font-semibold rounded-lg transition-all border border-garage-neon/20 hover:border-garage-neon/40">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="service-tag">BACK</span>
        </a>
    </div>

    @forelse($historyBookings as $booking)
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage mb-6 overflow-hidden border border-garage-steel/30 hover:border-garage-neon/40 transition-all">
            <!-- Header - Maintenance Record Style -->
            <div class="bg-gradient-to-r {{ $booking->status === 'completed' ? 'from-garage-emerald/20 to-garage-forest' : 'from-garage-steel/20 to-garage-darkgreen' }} border-b border-garage-steel/30 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-3 mb-1">
                            <svg class="w-6 h-6 {{ $booking->status === 'completed' ? 'text-garage-emerald' : 'text-garage-steel' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <h3 class="text-xl font-bold text-garage-offwhite service-tag">SERVICE #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</h3>
                        </div>
                        <p class="text-garage-steel text-sm font-mono ml-9">
                            Completed: {{ $booking->booking_date->format('M d, Y') }} at {{ $booking->booking_time->format('h:i A') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="service-tag px-4 py-2 text-sm rounded-lg font-bold
                            {{ $booking->status === 'completed' ? 'bg-garage-emerald/20 text-garage-emerald border border-garage-emerald/50' : 'bg-garage-steel/20 text-garage-steel border border-garage-steel/50' }}
                        ">
                            {{ strtoupper(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Vehicle Info with License Plate -->
                <div class="mb-5">
                    <h4 class="font-semibold text-garage-steel text-xs mb-3 service-tag flex items-center">
                        <svg class="w-4 h-4 mr-2 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        VEHICLE
                    </h4>
                    <div class="flex items-center justify-between bg-garage-charcoal/50 rounded-lg p-4 border border-garage-neon/20">
                        <div class="flex items-center flex-1">
                            <svg class="w-10 h-10 text-garage-steel mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <div>
                                <div class="font-bold text-garage-offwhite text-lg">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</div>
                                <div class="text-sm text-garage-steel">{{ $booking->vehicle->year }} Model</div>
                            </div>
                        </div>
                        <div class="plate-number text-sm ml-4">{{ $booking->vehicle->plate_number }}</div>
                    </div>
                </div>

                <!-- Services Performed - Maintenance Record Cards -->
                <div class="mb-5">
                    <h4 class="font-semibold text-garage-steel text-xs mb-3 service-tag flex items-center">
                        <svg class="w-4 h-4 mr-2 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        SERVICES PERFORMED
                    </h4>
                    <div class="space-y-2">
                        @foreach($booking->services as $service)
                            <div class="group flex items-center justify-between bg-garage-charcoal/50 border border-garage-steel/20 rounded-lg p-3 hover:border-garage-emerald/30 transition-colors">
                                <div class="flex items-center flex-1">
                                    <div class="w-1.5 h-1.5 bg-garage-emerald rounded-full mr-3 group-hover:shadow-[0_0_8px_rgba(45,212,191,0.6)]"></div>
                                    <div>
                                        <div class="font-semibold text-garage-offwhite">{{ $service->name }}</div>
                                        <div class="text-xs text-garage-steel flex items-center mt-0.5">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $service->estimated_duration }} minutes
                                        </div>
                                    </div>
                                </div>
                                <div class="font-bold text-white group-hover:text-white font-mono transition-colors">₱{{ number_format($service->pivot->price, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Status -->
                @if($booking->payment)
                    <div class="mb-5 bg-garage-emerald/10 rounded-lg p-4 border border-garage-emerald/30">
                        <h4 class="font-semibold text-garage-steel text-xs mb-3 service-tag flex items-center">
                            <svg class="w-4 h-4 mr-2 text-garage-emerald" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            PAYMENT
                        </h4>
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <div class="text-sm text-garage-steel">
                                    <span class="text-garage-offwhite font-semibold">Method:</span> 
                                    <span class="font-mono">{{ strtoupper($booking->payment->payment_method) }}</span>
                                </div>
                                <div class="text-sm text-garage-steel">
                                    <span class="text-garage-offwhite font-semibold">Date:</span> 
                                    <span class="font-mono">{{ $booking->payment->payment_date->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <span class="service-tag px-3 py-1.5 text-xs rounded-lg font-bold bg-garage-emerald/20 text-garage-emerald border border-garage-emerald/40">
                                {{ strtoupper($booking->payment->payment_status) }}
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Notes -->
                @if($booking->notes)
                    <div class="mb-5">
                        <h4 class="font-semibold text-garage-steel text-xs mb-2 service-tag flex items-center">
                            <svg class="w-4 h-4 mr-2 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            TECHNICIAN NOTES
                        </h4>
                        <p class="text-garage-offwhite bg-garage-charcoal/50 rounded-lg p-3 border border-garage-neon/20 text-sm italic">{{ $booking->notes }}</p>
                    </div>
                @endif

                <!-- Total - Receipt Footer Style -->
                <div class="border-t-2 border-garage-steel/30 pt-4">
                    <div class="flex items-center justify-between bg-garage-charcoal/70 rounded-lg p-4 border border-{{ $booking->status === 'completed' ? 'garage-emerald' : 'garage-steel' }}/40">
                        <span class="font-bold text-garage-offwhite text-lg service-tag">TOTAL PAID</span>
                        <span class="font-bold {{ $booking->status === 'completed' ? 'text-garage-emerald' : 'text-garage-steel' }} text-2xl font-mono">₱{{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Archive Indicator -->
            <div class="h-1 bg-gradient-to-r from-transparent via-{{ $booking->status === 'completed' ? 'garage-emerald' : 'garage-steel' }}/30 to-transparent"></div>
        </div>
    @empty
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen shadow-garage rounded-lg p-16 text-center border border-garage-neon/20">
            <svg class="w-32 h-32 mx-auto text-garage-steel/30 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-2xl font-bold text-garage-offwhite mb-3 service-tag">NO SERVICE HISTORY</h3>
            <p class="text-garage-steel text-lg mb-8">Your maintenance log is empty. Start building your service history today.</p>
            <a href="{{ route('customer.book') }}" wire:navigate
                class="inline-flex items-center bg-garage-neon hover:bg-garage-emerald text-garage-black font-bold px-8 py-4 rounded-lg transition-all shadow-neon-green service-tag">
                <span>BOOK FIRST SERVICE</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    @endforelse
</div>
