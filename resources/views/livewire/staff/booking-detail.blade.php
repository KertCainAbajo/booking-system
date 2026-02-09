<div>
    <!-- Header Section -->
    <div class="mb-6">
        <!-- Back Button -->
        <div class="flex justify-end mb-6">
            <a href="{{ route('staff.bookings') }}" wire:navigate
                class="inline-flex items-center space-x-2 text-garage-neon hover:text-white font-semibold transition-all group">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span class="service-tag">BACK TO BOOKINGS</span>
            </a>
        </div>

        <!-- Title Card -->
        <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-8 border-l-4 border-garage-neon relative overflow-hidden">
            <!-- Carbon Fiber Pattern Overlay -->
            <div class="absolute inset-0 bg-carbon-fiber opacity-50"></div>
            
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-garage-neon/20 p-4 rounded-lg border border-garage-neon/30">
                        <svg class="w-10 h-10 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-garage-offwhite service-tag tracking-wider">BOOKING #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</h2>
                        <p class="text-garage-steel text-sm mt-1">Comprehensive booking details and management</p>
                    </div>
                </div>
                
                <div class="text-right">
                    <span class="service-tag px-6 py-3 text-sm rounded-lg font-bold inline-block border-2 shadow-lg
                        {{ $booking->status === 'pending' ? 'bg-yellow-400/20 text-yellow-400 border-yellow-400/50 shadow-yellow-400/20' : '' }}
                        {{ $booking->status === 'approved' ? 'bg-blue-400/20 text-blue-400 border-blue-400/50 shadow-blue-400/20' : '' }}
                        {{ $booking->status === 'completed' ? 'bg-garage-neon/20 text-garage-neon border-garage-neon/50 shadow-garage-neon/20' : '' }}
                        {{ $booking->status === 'cancelled' ? 'bg-red-500/20 text-red-400 border-red-500/50 shadow-red-500/20' : '' }}
                        {{ $booking->status === 'no_show' ? 'bg-orange-500/20 text-orange-400 border-orange-500/50 shadow-orange-500/20' : '' }}">
                        {{ $booking->status === 'no_show' ? 'NOT ARRIVING' : strtoupper(str_replace('_', ' ', $booking->status)) }}
                    </span>
                    <p class="text-garage-steel text-xs mt-2 font-mono">Received {{ $booking->created_at->diffForHumans() }}</p>
                </div>
            </div>
            
            <!-- Garage Floor Marking -->
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-garage-neon/50 to-transparent"></div>
        </div>
    </div>

    <x-success-modal />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20 relative overflow-hidden">
                <div class="absolute inset-0 bg-carbon-fiber opacity-30"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-garage-neon/20 p-3 rounded-lg border border-garage-neon/30">
                            <svg class="w-6 h-6 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-garage-offwhite service-tag">CUSTOMER INFORMATION</h3>
                    </div>
                    
                    <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-6"></div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-xs text-garage-steel uppercase tracking-wider font-semibold">Full Name</label>
                            <p class="font-bold text-garage-offwhite text-lg">{{ $booking->customer->getDisplayName() }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs text-garage-steel uppercase tracking-wider font-semibold">Email Address</label>
                            <p class="font-medium text-garage-offwhite">{{ $booking->customer->getContactEmail() }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs text-garage-steel uppercase tracking-wider font-semibold">Phone Number</label>
                            <p class="font-medium text-garage-offwhite font-mono">{{ $booking->customer->getContactPhone() }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs text-garage-steel uppercase tracking-wider font-semibold">Address</label>
                            <p class="font-medium text-garage-offwhite">{{ $booking->customer->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Information -->
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20 relative overflow-hidden">
                <div class="absolute inset-0 bg-carbon-fiber opacity-30"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-garage-neon/20 p-3 rounded-lg border border-garage-neon/30">
                            <svg class="w-6 h-6 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-garage-offwhite service-tag">VEHICLE INFORMATION</h3>
                    </div>
                    
                    <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-6"></div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-xs text-garage-steel uppercase tracking-wider font-semibold">Make & Model</label>
                            <p class="font-bold text-garage-offwhite text-lg">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs text-garage-steel uppercase tracking-wider font-semibold">Year</label>
                            <p class="font-medium text-garage-offwhite font-mono">{{ $booking->vehicle->year }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs text-garage-steel uppercase tracking-wider font-semibold">Plate Number</label>
                            <p class="font-bold text-garage-neon text-lg font-mono bg-garage-forest/50 px-3 py-1 rounded inline-block border border-garage-neon/30">{{ $booking->vehicle->plate_number }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs text-garage-steel uppercase tracking-wider font-semibold">VIN Number</label>
                            <p class="font-medium text-garage-offwhite font-mono text-sm">{{ $booking->vehicle->vin_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20 relative overflow-hidden">
                <div class="absolute inset-0 bg-carbon-fiber opacity-30"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-garage-neon/20 p-3 rounded-lg border border-garage-neon/30">
                            <svg class="w-6 h-6 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-garage-offwhite service-tag">BOOKING DETAILS</h3>
                    </div>
                    
                    <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-6"></div>
                    
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="space-y-1">
                            <label class="text-xs text-garage-steel uppercase tracking-wider font-semibold flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Scheduled Date
                            </label>
                            <p class="font-bold text-garage-neon text-lg">{{ \Carbon\Carbon::parse($booking->booking_date)->format('F d, Y') }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs text-garage-steel uppercase tracking-wider font-semibold flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Scheduled Time
                            </label>
                            <p class="font-bold text-garage-neon text-lg font-mono">{{ $booking->booking_time }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs text-garage-steel uppercase tracking-wider font-semibold">Received Date</label>
                            <p class="font-medium text-garage-offwhite">{{ $booking->created_at->format('F d, Y') }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs text-garage-steel uppercase tracking-wider font-semibold">Received Time</label>
                            <p class="font-medium text-garage-offwhite font-mono">{{ $booking->created_at->format('h:i A') }}</p>
                            <p class="text-xs text-garage-steel italic">{{ $booking->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="space-y-1 bg-garage-forest/30 p-4 rounded-lg border border-garage-neon/20">
                            <label class="text-xs text-garage-steel uppercase tracking-wider font-semibold">Total Amount</label>
                            <p class="font-bold text-white text-3xl font-mono">₱{{ number_format($booking->total_amount, 2) }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs text-garage-steel uppercase tracking-wider font-semibold">Assigned To</label>
                            <p class="font-medium text-garage-offwhite">{{ $booking->assignedStaff ? $booking->assignedStaff->name : 'Unassigned' }}</p>
                        </div>
                    </div>

                    @if($booking->notes)
                    <div class="pt-6 border-t border-garage-neon/20">
                        <label class="text-xs text-garage-steel uppercase tracking-wider font-semibold block mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            Customer Notes
                        </label>
                        <div class="bg-garage-forest/30 p-4 rounded border border-garage-neon/20">
                            <p class="text-garage-offwhite italic">{{ $booking->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Services -->
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20 relative overflow-hidden">
                <div class="absolute inset-0 bg-carbon-fiber opacity-30"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-garage-neon/20 p-3 rounded-lg border border-garage-neon/30">
                            <svg class="w-6 h-6 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-garage-offwhite service-tag">SERVICES ORDERED</h3>
                    </div>
                    
                    <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-6"></div>
                    
                    <div class="space-y-3">
                        @foreach($booking->services as $service)
                        <div class="flex justify-between items-center p-4 bg-garage-forest/30 rounded-lg border border-garage-neon/20 hover:bg-garage-forest/50 transition-all">
                            <div class="flex-1">
                                <p class="font-bold text-garage-offwhite text-lg mb-1">{{ $service->name }}</p>
                                <p class="text-sm text-garage-steel">{{ $service->description }}</p>
                            </div>
                            <div class="text-right ml-6">
                                <p class="font-bold text-white text-xl font-mono">₱{{ number_format($service->pivot->price, 2) }}</p>
                                <p class="text-xs text-garage-steel font-semibold mt-1">QTY: <span class="text-garage-offwhite font-mono">{{ $service->pivot->quantity }}</span></p>
                            </div>
                        </div>
                        @endforeach
                        
                        <!-- Total -->
                        <div class="pt-4 mt-4 border-t border-garage-neon/20">
                            <div class="flex justify-between items-center bg-garage-neon/10 p-4 rounded-lg border-2 border-garage-neon/30">
                                <span class="text-garage-offwhite font-bold text-lg service-tag">TOTAL AMOUNT</span>
                                <span class="text-white font-bold text-3xl font-mono">₱{{ number_format($booking->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status History -->
            @if($booking->statusLogs && $booking->statusLogs->count() > 0)
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20 relative overflow-hidden">
                <div class="absolute inset-0 bg-carbon-fiber opacity-30"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-garage-neon/20 p-3 rounded-lg border border-garage-neon/30">
                            <svg class="w-6 h-6 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-garage-offwhite service-tag">STATUS HISTORY</h3>
                    </div>
                    
                    <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-6"></div>
                    
                    <div class="space-y-4">
                        @foreach($booking->statusLogs->sortByDesc('created_at') as $log)
                        <div class="flex items-start gap-4 pb-4 border-b border-garage-neon/10 last:border-b-0">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-3 h-3 rounded-full bg-garage-neon shadow-lg shadow-garage-neon/50"></div>
                            </div>
                            <div class="flex-1 bg-garage-forest/20 p-4 rounded-lg border border-garage-neon/10">
                                <p class="text-sm text-garage-offwhite mb-2">
                                    <span class="font-bold text-garage-neon">{{ $log->user->name }}</span>
                                    <span class="text-garage-steel"> changed status from </span>
                                    <span class="font-bold text-yellow-400">{{ strtoupper($log->old_status) }}</span>
                                    <span class="text-garage-steel"> to </span>
                                    <span class="font-bold text-garage-neon">{{ strtoupper($log->new_status) }}</span>
                                </p>
                                @if($log->notes)
                                <div class="mt-2 bg-garage-charcoal/50 p-3 rounded border border-garage-neon/10">
                                    <p class="text-sm text-garage-steel italic">{{ $log->notes }}</p>
                                </div>
                                @endif
                                <p class="text-xs text-garage-steel mt-2 font-mono">{{ $log->created_at->format('M d, Y h:i A') }} • {{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Update Status -->
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20 relative overflow-hidden">
                <div class="absolute inset-0 bg-carbon-fiber opacity-30"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-garage-neon/20 p-3 rounded-lg border border-garage-neon/30">
                            <svg class="w-6 h-6 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-garage-offwhite service-tag">UPDATE STATUS</h3>
                    </div>
                    
                    <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-6"></div>

                    <div class="space-y-4">
                        <div class="space-y-3">
                            @if($booking->status === 'pending')
                            <button wire:click="updateStatus('approved')" 
                                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-4 rounded-lg hover:from-blue-700 hover:to-blue-800 font-bold transition-all shadow-lg hover:shadow-blue-500/50 service-tag flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                APPROVE BOOKING
                            </button>
                            @endif

                            @if($booking->status === 'approved')
                            <button wire:click="updateStatus('completed')" 
                                class="w-full bg-gradient-to-r from-garage-neon to-green-600 text-garage-charcoal py-3 px-4 rounded-lg hover:from-green-500 hover:to-garage-neon font-bold transition-all shadow-lg hover:shadow-garage-neon/50 service-tag flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                MARK AS COMPLETED
                            </button>
                            
                            <button wire:click="updateStatus('no_show')" 
                                class="w-full bg-gradient-to-r from-orange-600 to-orange-700 text-white py-3 px-4 rounded-lg hover:from-orange-700 hover:to-orange-800 font-bold transition-all shadow-lg hover:shadow-orange-500/50 service-tag flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                NOT ARRIVING / LATE
                            </button>
                            @endif

                            @if(!in_array($booking->status, ['completed', 'cancelled', 'no_show']))
                            <button wire:click="updateStatus('cancelled')" 
                                class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-3 px-4 rounded-lg hover:from-red-700 hover:to-red-800 font-bold transition-all shadow-lg hover:shadow-red-500/50 service-tag flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                CANCEL BOOKING
                            </button>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-garage-steel uppercase tracking-wider mb-2">Add Note (Optional)</label>
                            <textarea wire:model="notes" 
                                class="w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon placeholder-garage-steel/50" 
                                rows="3" 
                                placeholder="Add a note about this status change..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            @if($booking->payment)
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20 relative overflow-hidden">
                <div class="absolute inset-0 bg-carbon-fiber opacity-30"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-garage-neon/20 p-3 rounded-lg border border-garage-neon/30">
                            <svg class="w-6 h-6 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-garage-offwhite service-tag">PAYMENT INFO</h3>
                    </div>
                    
                    <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-6"></div>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-garage-steel font-semibold">Amount</span>
                            <span class="font-bold text-white text-xl font-mono">₱{{ number_format($booking->payment->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-garage-steel font-semibold">Method</span>
                            <span class="font-bold text-garage-offwhite">{{ strtoupper($booking->payment->payment_method) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-garage-steel font-semibold">Status</span>
                            <span class="px-3 py-1 text-xs rounded-full font-bold service-tag
                                {{ $booking->payment->payment_status === 'paid' ? 'bg-garage-neon/20 text-garage-neon border border-garage-neon/30' : 'bg-yellow-400/20 text-yellow-400 border border-yellow-400/30' }}">
                                {{ strtoupper($booking->payment->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Generate Invoice -->
            @if($booking->status === 'completed')
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20 relative overflow-hidden">
                <div class="absolute inset-0 bg-carbon-fiber opacity-30"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-garage-neon/20 p-3 rounded-lg border border-garage-neon/30">
                            <svg class="w-6 h-6 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-garage-offwhite service-tag">DOCUMENTS</h3>
                    </div>
                    
                    <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-6"></div>
                    
                    <a href="{{ route('staff.invoice', $booking->id) }}" 
                        class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-garage-forest to-garage-darkgreen text-white py-3 px-4 rounded-lg hover:from-garage-darkgreen hover:to-garage-forest font-bold transition-all shadow-lg hover:shadow-garage-neon/30 border border-garage-neon/20 service-tag">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        GENERATE INVOICE
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
