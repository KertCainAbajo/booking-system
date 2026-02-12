<div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <svg class="w-6 h-6 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h2 class="text-2xl font-bold text-garage-offwhite service-tag">AVAILABLE SLOTS</h2>
        </div>
        <a href="{{ route('customer.book') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-garage-neon hover:bg-garage-emerald text-garage-black font-bold rounded-lg transition-all shadow-neon-green service-tag text-sm">
            <span>BOOK NOW</span>
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </a>
    </div>

    <!-- Garage Floor Divider -->
    <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-6"></div>

    <!-- Date Selection -->
    <div class="mb-6">
        <h3 class="text-sm font-semibold text-garage-steel mb-3 uppercase tracking-wider">Select Date</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-3">
            @foreach($availableDates as $dateInfo)
                <button 
                    wire:click="selectDate('{{ $dateInfo['date'] }}')"
                    class="group relative bg-garage-charcoal/50 rounded-lg p-3 border-2 transition-all duration-200
                        {{ $selectedDate === $dateInfo['date'] 
                            ? 'border-garage-neon bg-garage-neon/10' 
                            : 'border-garage-steel/20 hover:border-garage-neon/50 hover:bg-garage-forest/30' }}
                    ">
                    <div class="text-center">
                        <div class="text-xs font-semibold text-garage-steel mb-1 uppercase tracking-wide">
                            {{ $dateInfo['day'] }}
                        </div>
                        <div class="text-2xl font-bold 
                            {{ $selectedDate === $dateInfo['date'] ? 'text-garage-neon' : 'text-garage-offwhite' }}
                        ">
                            {{ $dateInfo['dayNum'] }}
                        </div>
                        <div class="text-xs font-semibold text-garage-steel uppercase">
                            {{ $dateInfo['month'] }}
                        </div>
                    </div>
                    
                    @if($selectedDate === $dateInfo['date'])
                        <div class="absolute bottom-0 left-0 right-0 h-1 bg-garage-neon rounded-b"></div>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    <!-- Time Slots -->
    @if($selectedDate)
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-garage-steel uppercase tracking-wider">
                    Available Time Slots - {{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}
                </h3>
                <div class="flex items-center space-x-4 text-xs">
                    <div class="flex items-center space-x-1">
                        <div class="w-3 h-3 rounded-full bg-garage-neon"></div>
                        <span class="text-garage-steel">Available</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <span class="text-garage-steel">Limited</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <span class="text-garage-steel">Full</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @forelse($selectedDateSlots as $slot)
                    <div class="relative bg-garage-charcoal/50 rounded-lg p-4 border-2 
                        {{ $slot['isAvailable'] 
                            ? ($slot['isLimited'] ? 'border-yellow-500/30' : 'border-garage-neon/30 hover:border-garage-neon') 
                            : 'border-red-500/30 opacity-60' }}
                        transition-all duration-200">
                        
                        <!-- Status Indicator -->
                        <div class="absolute top-2 right-2">
                            @if($slot['isFull'])
                                <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
                            @elseif($slot['isLimited'])
                                <div class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></div>
                            @else
                                <div class="w-2 h-2 rounded-full bg-garage-neon"></div>
                            @endif
                        </div>

                        <div class="text-center">
                            <!-- Time Display -->
                            <div class="text-lg font-bold mb-2
                                {{ $slot['isAvailable'] 
                                    ? ($slot['isLimited'] ? 'text-yellow-400' : 'text-garage-neon') 
                                    : 'text-red-400' }}
                                font-mono">
                                {{ $slot['displayTime'] }}
                            </div>

                            <!-- Availability Status -->
                            <div class="text-xs font-semibold uppercase tracking-wide
                                {{ $slot['isAvailable'] 
                                    ? ($slot['isLimited'] ? 'text-yellow-400' : 'text-garage-steel') 
                                    : 'text-red-400' }}">
                                @if($slot['isFull'])
                                    <div class="flex items-center justify-center space-x-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Fully Booked</span>
                                    </div>
                                @elseif($slot['isLimited'])
                                    <div class="flex items-center justify-center space-x-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ $slot['availableSpots'] }} Spot Left</span>
                                    </div>
                                @else
                                    <div class="flex items-center justify-center space-x-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ $slot['availableSpots'] }} Spots Open</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8 text-garage-steel">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>No available time slots for this date</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    <!-- Info Note -->
    <div class="mt-6 pt-6 border-t border-garage-steel/20">
        <div class="flex items-start space-x-3 text-sm text-garage-steel">
            <svg class="w-5 h-5 text-garage-neon flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="font-semibold text-garage-offwhite mb-1">Booking Information:</p>
                <ul class="space-y-1 list-disc list-inside">
                    <li>Business hours: 8:00 AM - 6:00 PM (Closed on Sundays)</li>
                    <li>Lunch break: 12:00 PM - 1:00 PM</li>
                    <li>We can accommodate up to {{ $maxBookingsPerSlot }} simultaneous bookings per time slot</li>
                    <li>Book your preferred slot before it fills up!</li>
                </ul>
            </div>
        </div>
    </div>
</div>
