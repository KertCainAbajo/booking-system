<div class="min-h-screen bg-gradient-to-br from-black to-green-900 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Garage Header -->
        <div class="text-center mb-10">
            <div class="flex items-center justify-center mb-4">
                <img src="{{ asset('images/shop.png') }}" alt="Dexter Auto Services" class="h-16 w-16 mr-4 brightness-0 invert">
                <h1 class="text-5xl font-black text-white">
                    DEXTER AUTO SERVICES
                </h1>
            </div>
            <p class="text-white text-xl font-semibold">Professional Auto Service Platform</p>
        </div>

        <div class="text-center mb-8">
            <h2 class="text-4xl font-black text-white mb-3 service-tag">TRACK YOUR BOOKING</h2>
            <p class="text-white text-lg">Enter your booking reference number to view the status</p>
        </div>

        <!-- Search Form -->
        <div class="service-bay-card carbon-texture p-8 mb-6">
            <form wire:submit.prevent="trackBooking">
                <div class="flex gap-4">
                    <input type="text" 
                           wire:model="bookingReference" 
                           placeholder="Enter booking reference (e.g., BK12345678)" 
                           class="flex-1 px-6 py-4 bg-garage-charcoal border-2 border-garage-steel/30 rounded-lg text-white placeholder-garage-steel focus:ring-2 focus:ring-garage-neon focus:border-garage-neon text-lg font-semibold"
                           required>
                    <button type="submit" 
                            class="px-10 py-4 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition service-tag text-lg">
                        TRACK
                    </button>
                </div>
            </form>
            
            <!-- Back to Booking Button -->
            <div class="mt-4 text-center">
                <a href="{{ route('guest.home') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-800 to-green-900 text-white font-semibold rounded-lg border-2 border-garage-neon/30 hover:border-garage-neon hover:shadow-lg hover:shadow-garage-neon/50 transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Home
                </a>
            </div>
        </div>

        <!-- Not Found Message -->
        @if ($notFound)
            <div class="service-bay-card carbon-texture p-8 text-center border-2 border-red-500/50">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-red-500/20 border-4 border-red-500 rounded-full mb-4">
                    <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-white mb-3 service-tag">
                    @if(session()->has('error'))
                        REFERENCE NOT AVAILABLE
                    @else
                        BOOKING NOT FOUND
                    @endif
                </h3>
                @if(session()->has('error'))
                    <p class="text-white mb-4">{{ session('error') }}</p>
                    <p class="text-red-400 font-bold text-xl mb-4">{{ $bookingReference }}</p>
                    <p class="text-white">The booking reference is no longer accessible once the service is completed.</p>
                @else
                    <p class="text-white mb-2">We couldn't find a booking with reference number:</p>
                    <p class="text-red-400 font-bold text-xl mb-4">{{ $bookingReference }}</p>
                    <p class="text-white">Please check the reference number and try again.</p>
                @endif
                
                <!-- Action Button -->
                <div class="mt-6">
                    <a href="{{ route('guest.booking') }}" 
                       class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-all shadow-lg border-2 border-green-400 service-tag">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        MAKE A NEW BOOKING
                    </a>
                </div>
            </div>
        @endif

        <!-- Booking Found -->
        @if ($booking)
            <!-- Booking Summary Card -->
            <div class="service-bay-card carbon-texture p-8 mb-6 border-2 border-garage-neon/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white mb-2 font-semibold service-tag">BOOKING REFERENCE</p>
                        <p class="text-4xl font-black text-white tracking-wider" style="text-shadow: 0 0 15px rgba(255, 255, 255, 0.5);">{{ $booking->booking_reference }}</p>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-6 py-3 rounded-lg text-base font-bold service-tag
                            {{ $booking->status === 'completed' ? 'bg-green-600 text-white border-2 border-green-400' : 
                               ($booking->status === 'approved' ? 'bg-blue-600 text-white border-2 border-blue-400' : 
                               ($booking->status === 'cancelled' ? 'bg-red-600 text-white border-2 border-red-400' : 'bg-yellow-600 text-white border-2 border-yellow-400')) }}">
                            {{ strtoupper($booking->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Details Card -->
            <div class="service-bay-card carbon-texture overflow-hidden">
                <div class="p-8">
                    <!-- Status Progress -->
                    <div class="mb-8">
                        <h3 class="font-bold text-xl mb-6 text-white service-tag">BOOKING PROGRESS</h3>
                        <div class="flex items-center justify-between">
                            <!-- Submitted -->
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-14 h-14 rounded-full bg-green-600 text-white flex items-center justify-center mb-2 border-4 border-green-200">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-center font-bold text-white">Submitted</span>
                            </div>
                            <div class="flex-1 h-2 {{ in_array($booking->status, ['approved', 'completed']) ? 'bg-green-600' : 'bg-garage-steel/30' }} rounded"></div>
                            
                            <!-- Approved -->
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-14 h-14 rounded-full {{ in_array($booking->status, ['approved', 'completed']) ? 'bg-green-600 border-green-200' : 'bg-garage-steel/30 border-garage-steel/20' }} text-white flex items-center justify-center mb-2 border-4">
                                    @if(in_array($booking->status, ['approved', 'completed']))
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <span class="text-sm text-center font-bold {{ in_array($booking->status, ['approved', 'completed']) ? 'text-white' : 'text-garage-steel' }}">Approved</span>
                            </div>
                            <div class="flex-1 h-2 {{ $booking->status === 'completed' ? 'bg-green-600' : 'bg-garage-steel/30' }} rounded"></div>
                            
                            <!-- Completed -->
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-14 h-14 rounded-full {{ $booking->status === 'completed' ? 'bg-green-600 border-green-200' : 'bg-garage-steel/30 border-garage-steel/20' }} text-white flex items-center justify-center mb-2 border-4">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-center font-bold {{ $booking->status === 'completed' ? 'text-white' : 'text-garage-steel' }}">Completed</span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="mb-6 pb-6 border-b border-garage-steel/30">
                        <h3 class="font-bold text-xl mb-4 text-white service-tag">CUSTOMER INFORMATION</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-semibold text-white mb-1 service-tag">NAME</p>
                                <p class="font-bold text-white text-lg">{{ $booking->customer->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white mb-1 service-tag">EMAIL</p>
                                <p class="font-bold text-white text-lg">{{ $booking->customer->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white mb-1 service-tag">PHONE</p>
                                <p class="font-bold text-white text-lg">{{ $booking->customer->phone }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Info -->
                    <div class="mb-6 pb-6 border-b border-garage-steel/30">
                        <h3 class="font-bold text-xl mb-4 text-white service-tag">VEHICLE INFORMATION</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-semibold text-white mb-1 service-tag">VEHICLE</p>
                                <p class="font-bold text-white text-lg">{{ $booking->vehicle->year }} {{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white mb-1 service-tag">PLATE NUMBER</p>
                                <p class="font-bold text-white text-lg">{{ $booking->vehicle->plate_number }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Info -->
                    <div class="mb-6 pb-6 border-b border-garage-steel/30">
                        <h3 class="font-bold text-xl mb-4 text-white service-tag">APPOINTMENT DETAILS</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-semibold text-white mb-1 service-tag">DATE</p>
                                <p class="font-bold text-white text-lg">{{ $booking->booking_date->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white mb-1 service-tag">TIME</p>
                                <p class="font-bold text-white text-lg">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</p>
                            </div>
                            @if($booking->notes)
                                <div class="md:col-span-2">
                                    <p class="text-sm font-semibold text-white mb-1 service-tag">NOTES</p>
                                    <p class="font-bold text-white text-lg">{{ $booking->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Services -->
                    <div class="mb-6">
                        <h3 class="font-bold text-xl mb-4 text-white service-tag">SERVICES</h3>
                        <div class="space-y-3">
                            @foreach ($booking->services as $service)
                                <div class="flex justify-between items-start p-5 bg-garage-charcoal/30 border border-garage-steel/20 rounded-lg hover:border-garage-neon/30 transition">
                                    <div class="flex-1">
                                        <p class="font-bold text-white text-lg mb-1">{{ $service->name }}</p>
                                        <p class="text-sm text-white">{{ $service->description }}</p>
                                    </div>
                                    <span class="font-bold text-white text-lg ml-4">₱{{ number_format($service->pivot->price, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6 pt-6 border-t-2 border-garage-neon/50 flex justify-between items-center">
                            <span class="text-2xl font-black text-white service-tag">TOTAL AMOUNT</span>
                            <span class="text-3xl font-black text-white" style="text-shadow: 0 0 15px rgba(255, 255, 255, 0.5);">₱{{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <!-- Assigned Staff (if any) -->
                    @if ($booking->assigned_staff_id)
                        <div class="bg-garage-charcoal/50 border-2 border-blue-500/50 rounded-lg p-5 mb-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-white service-tag">ASSIGNED STAFF</p>
                                    <p class="font-bold text-white text-lg">{{ $booking->assignedStaff->name }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Success/Error Messages -->
            <x-success-modal />

            @if (session()->has('error'))
                <div class="mt-6 bg-red-600 border-2 border-red-400 text-white px-6 py-4 rounded-lg flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-6 space-y-3">
                @if(in_array($booking->status, ['pending', 'approved']))
                    <button 
                        wire:click="cancelBooking" 
                        wire:confirm="Are you sure you want to cancel this booking? This action cannot be undone."
                        class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-6 rounded-lg transition-all shadow-lg border-2 border-red-400 flex items-center justify-center service-tag">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        CANCEL BOOKING
                    </button>
                @endif
                
                <a href="{{ route('guest.home') }}" 
                   class="block w-full text-center bay-back flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    BACK TO HOME
                </a>
            </div>
        @endif
    </div>
</div>
