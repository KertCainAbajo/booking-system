<div class="min-h-screen bg-gradient-to-br from-black to-green-900 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Garage Header -->
        <div class="text-center mb-10">
            <!-- Logo and Brand -->
            <div class="flex items-center justify-center mb-4">
                <img src="{{ asset('images/shop.png') }}" alt="Dexter Auto Services" class="h-16 w-16 mr-4 brightness-0 invert">
                <h1 class="text-5xl font-black text-white">
                    DEXTER AUTO SERVICES
                </h1>
            </div>
            <p class="text-white text-xl font-semibold">Professional Auto Service Platform</p>
        </div>

        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-garage-neon/20 border-4 border-garage-neon rounded-full mb-4 animate-pulse">
                <svg class="w-14 h-14 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-4xl font-black text-white mb-2 service-tag">BOOKING CONFIRMED!</h2>
            <p class="text-lg text-white">Your appointment has been successfully scheduled</p>
        </div>

        <!-- Booking Reference Card -->
        <div class="service-bay-card carbon-texture p-8 mb-6 border-2 border-garage-neon/50">
            <div class="text-center">
                <p class="text-sm text-white mb-3 service-tag">YOUR BOOKING REFERENCE NUMBER</p>
                <div class="bg-garage-charcoal/50 border-2 border-garage-neon/30 rounded-lg p-6">
                    <p class="text-5xl font-black text-white mb-4 tracking-wider" style="text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);">{{ $bookingReference }}</p>
                </div>
                <p class="text-sm text-white mt-4">
                    <svg class="w-5 h-5 inline mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Please save this reference number. You can use it to track your booking status.
                </p>
            </div>
        </div>

        <!-- Booking Details Card -->
        <div class="service-bay-card carbon-texture p-8 mb-6">
            <h2 class="text-3xl font-black text-white service-tag mb-6 flex items-center">
                <svg class="w-8 h-8 mr-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                BOOKING DETAILS
            </h2>
            
            <!-- Customer Info -->
            <div class="mb-6 pb-6 border-b border-garage-steel/30">
                <h3 class="font-bold text-white mb-3 service-tag flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    CUSTOMER INFORMATION
                </h3>
                <div class="text-white space-y-2 ml-7">
                    <p class="flex items-start">
                        <span class="text-white min-w-[100px] service-tag">NAME:</span>
                        <span class="font-semibold">{{ $booking->customer->name }}</span>
                    </p>
                    <p class="flex items-start">
                        <span class="text-white min-w-[100px] service-tag">EMAIL:</span>
                        <span class="font-semibold">{{ $booking->customer->email }}</span>
                    </p>
                    <p class="flex items-start">
                        <span class="text-white min-w-[100px] service-tag">PHONE:</span>
                        <span class="font-semibold">{{ $booking->customer->phone }}</span>
                    </p>
                </div>
            </div>

            <!-- Vehicle Info -->
            <div class="mb-6 pb-6 border-b border-garage-steel/30">
                <h3 class="font-bold text-white mb-3 service-tag flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                    </svg>
                    VEHICLE DETAILS
                </h3>
                <div class="text-white ml-7">
                    <p class="text-lg font-bold mb-2">{{ $booking->vehicle->year }} {{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</p>
                    <p class="flex items-center text-white">
                        <span class="service-tag">PLATE NUMBER:</span>
                        <span class="ml-2 px-3 py-1 bg-garage-charcoal border border-garage-neon/30 rounded font-bold text-white">
                            {{ $booking->vehicle->plate_number }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Appointment Info -->
            <div class="mb-6 pb-6 border-b border-garage-steel/30">
                <h3 class="font-bold text-white mb-3 service-tag flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    APPOINTMENT SCHEDULE
                </h3>
                <div class="text-white space-y-2 ml-7">
                    <p class="flex items-center">
                        <span class="text-white min-w-[100px] service-tag">DATE:</span>
                        <span class="font-semibold">{{ $booking->booking_date->format('F d, Y') }}</span>
                    </p>
                    <p class="flex items-center">
                        <span class="text-white min-w-[100px] service-tag">TIME:</span>
                        <span class="font-semibold">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</span>
                    </p>
                    <p class="flex items-center">
                        <span class="text-white min-w-[100px] service-tag">STATUS:</span>
                        <span class="inline-flex items-center px-3 py-1 bg-yellow-500/20 border border-yellow-500/50 text-white text-sm rounded font-bold service-tag">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ strtoupper($booking->status) }}
                        </span>
                    </p>
                </div>
                @if($booking->notes)
                    <div class="mt-3 ml-7 p-3 bg-garage-charcoal/30 border border-garage-steel/20 rounded">
                        <p class="text-white text-sm service-tag mb-1">NOTES:</p>
                        <p class="text-white">{{ $booking->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Services -->
            <div>
                <h3 class="font-bold text-white mb-4 service-tag flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    SELECTED SERVICES
                </h3>
                <div class="space-y-2">
                    @foreach ($booking->services as $service)
                        <div class="flex justify-between items-center p-4 bg-garage-charcoal/30 border border-garage-steel/20 rounded hover:border-garage-neon/30 transition-colors">
                            <span class="text-white font-medium">{{ $service->name }}</span>
                            <span class="font-bold text-white bay-price">₱{{ number_format($service->pivot->price, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 pt-6 border-t-2 border-garage-neon/50 flex justify-between items-center">
                    <span class="text-2xl font-black text-white service-tag">TOTAL AMOUNT</span>
                    <span class="text-4xl font-black text-white" style="text-shadow: 0 0 15px rgba(255, 255, 255, 0.5);">₱{{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Confirmation Email Notice -->
        <div class="service-bay-card carbon-texture p-6 mb-6 border-l-4 border-garage-neon">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-12 h-12 bg-garage-neon/20 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-white mb-2 service-tag">EMAIL CONFIRMATION SENT</h4>
                    <p class="text-white">
                        A confirmation email has been sent to <span class="font-bold text-white">{{ $booking->customer->email }}</span> with your booking details and reference number.
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mb-8">
            <a href="{{ route('guest.home') }}" 
               class="flex-1 text-center bay-back flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                BACK TO HOME
            </a>
            <a href="{{ route('guest.booking.track') }}?reference={{ $bookingReference }}" 
               class="flex-1 text-center bay-cta flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                TRACK THIS BOOKING
            </a>
        </div>

        <!-- What's Next Section -->
        <div class="service-bay-card carbon-texture p-8">
            <h3 class="text-2xl font-black text-white mb-6 service-tag flex items-center">
                <svg class="w-7 h-7 mr-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                WHAT HAPPENS NEXT?
            </h3>
            <div class="space-y-4">
                <div class="flex items-start group">
                    <span class="flex-shrink-0 w-10 h-10 bg-garage-neon/20 border-2 border-white text-white rounded-full flex items-center justify-center text-lg font-black mr-4 group-hover:bg-garage-neon group-hover:text-garage-charcoal transition-all">1</span>
                    <div class="flex-1 pt-1">
                        <h4 class="font-bold text-white mb-1 service-tag">BOOKING REVIEW</h4>
                        <p class="text-white">Our team will review your booking and confirm availability within 24 hours</p>
                    </div>
                </div>
                <div class="flex items-start group">
                    <span class="flex-shrink-0 w-10 h-10 bg-garage-neon/20 border-2 border-white text-white rounded-full flex items-center justify-center text-lg font-black mr-4 group-hover:bg-garage-neon group-hover:text-garage-charcoal transition-all">2</span>
                    <div class="flex-1 pt-1">
                        <h4 class="font-bold text-white mb-1 service-tag">CONFIRMATION</h4>
                        <p class="text-white">You'll receive a confirmation email and SMS once your appointment is approved</p>
                    </div>
                </div>
                <div class="flex items-start group">
                    <span class="flex-shrink-0 w-10 h-10 bg-garage-neon/20 border-2 border-white text-white rounded-full flex items-center justify-center text-lg font-black mr-4 group-hover:bg-garage-neon group-hover:text-garage-charcoal transition-all">3</span>
                    <div class="flex-1 pt-1">
                        <h4 class="font-bold text-white mb-1 service-tag">SERVICE DAY</h4>
                        <p class="text-white">Bring your vehicle to our service center on the scheduled date and time. Our expert technicians will take care of the rest!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="mt-8 text-center">
            <p class="text-white text-sm">
                Need help? Contact us at <a href="tel:+1234567890" class="text-white hover:underline">+1 (234) 567-8900</a> or 
                <a href="mailto:support@dexterauto.com" class="text-white hover:underline">support@dexterauto.com</a>
            </p>
        </div>
    </div>

    <!-- Success Modal -->
    <x-success-modal />
</div>
