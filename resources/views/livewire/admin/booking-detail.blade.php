<div>
    <!-- Page Header -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-8 border-l-4 border-garage-neon relative overflow-hidden mb-6">
        <!-- Carbon Fiber Pattern Overlay -->
        <div class="absolute inset-0 bg-carbon-fiber opacity-50"></div>
        
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-4 mb-3">
                    <svg class="w-12 h-12 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <h1 class="text-4xl font-bold text-garage-offwhite service-tag tracking-wider">BOOKING #{{ $booking->id }}</h1>
                </div>
                <p class="text-garage-steel text-lg ml-16">View complete booking information</p>
            </div>
            <a href="{{ route('admin.bookings') }}" class="bg-garage-steel/30 hover:bg-garage-steel/50 text-garage-offwhite px-6 py-3 rounded-lg font-semibold transition-colors">
                ← BACK
            </a>
        </div>
        
        <!-- Garage Floor Marking -->
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-white to-transparent opacity-30"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Information -->
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
                <h3 class="text-xl font-bold text-garage-neon mb-4 service-tag">BOOKING INFORMATION</h3>
                
                <!-- Garage Floor Divider -->
                <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4"></div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-garage-steel uppercase tracking-wider">Booking ID</p>
                        <p class="font-bold text-garage-offwhite font-mono">#{{ $booking->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-garage-steel uppercase tracking-wider">Status</p>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                'approved' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                'completed' => 'bg-garage-neon/20 text-garage-neon border-garage-neon/30',
                                'cancelled' => 'bg-red-500/20 text-red-400 border-red-500/30',
                                'no_show' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
                            ];
                        @endphp
                        <span class="inline-block px-3 py-1 text-sm rounded-full border {{ $statusColors[$booking->status] ?? 'bg-garage-steel/20 text-garage-steel border-garage-steel/30' }} font-semibold">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-garage-steel uppercase tracking-wider">Booking Date</p>
                        <p class="font-semibold text-garage-offwhite">{{ $booking->booking_date->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-garage-steel uppercase tracking-wider">Booking Time</p>
                        <p class="font-semibold text-garage-offwhite">{{ $booking->booking_time->format('h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-garage-steel uppercase tracking-wider">Received Date</p>
                        <p class="font-semibold text-garage-offwhite">{{ $booking->created_at->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-garage-steel uppercase tracking-wider">Received Time</p>
                        <p class="font-semibold text-garage-offwhite">{{ $booking->created_at->format('h:i A') }}</p>
                        <p class="text-xs text-garage-steel/70">{{ $booking->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-garage-steel uppercase tracking-wider">Total Amount</p>
                        <p class="text-3xl font-bold text-garage-neon font-mono">₱{{ number_format($booking->total_amount, 2) }}</p>
                    </div>
                    @if($booking->notes)
                        <div class="col-span-2">
                            <p class="text-sm text-garage-steel uppercase tracking-wider">Notes</p>
                            <p class="text-garage-offwhite">{{ $booking->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Services -->
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
                <h3 class="text-xl font-bold text-garage-neon mb-4 service-tag">SERVICES</h3>
                
                <!-- Garage Floor Divider -->
                <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4"></div>
                
                <div class="space-y-3">
                    @foreach($booking->services as $service)
                        <div class="flex justify-between items-center border-b border-garage-neon/10 pb-3">
                            <div>
                                <p class="font-semibold text-garage-offwhite">{{ $service->name }}</p>
                                <p class="text-sm text-garage-steel">{{ $service->description }}</p>
                                <p class="text-xs text-garage-steel/70">Quantity: {{ $service->pivot->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-white font-mono">₱{{ number_format($service->pivot->price * $service->pivot->quantity, 2) }}</p>
                                <p class="text-sm text-white">₱{{ number_format($service->pivot->price, 2) }} each</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Status History -->
            @if($booking->statusLogs && $booking->statusLogs->count() > 0)
                <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
                    <h3 class="text-xl font-bold text-garage-neon mb-4 service-tag">STATUS HISTORY</h3>
                    
                    <!-- Garage Floor Divider -->
                    <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4"></div>
                    
                    <div class="space-y-3">
                        @foreach($booking->statusLogs as $log)
                            <div class="border-l-4 border-garage-neon pl-4 py-2 bg-garage-forest/20 rounded-r">
                                <p class="font-semibold text-garage-offwhite">
                                    Status changed from 
                                    <span class="text-yellow-400">{{ ucfirst($log->old_status) }}</span>
                                    to 
                                    <span class="text-garage-neon">{{ ucfirst($log->new_status) }}</span>
                                </p>
                                <p class="text-sm text-garage-steel">
                                    {{ $log->created_at->format('F d, Y h:i A') }}
                                    @if($log->user)
                                        by {{ $log->user->name }}
                                    @else
                                        by Guest
                                    @endif
                                </p>
                                @if($log->notes)
                                    <p class="text-sm text-garage-steel/70 mt-1 italic">{{ $log->notes }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Management -->
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
                <h3 class="text-xl font-bold text-garage-neon mb-4 service-tag">STATUS MANAGEMENT</h3>
                
                <x-success-modal />

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-garage-steel mb-3 uppercase tracking-wider">Change Status</label>
                        <div class="space-y-2">
                            @if($booking->status !== 'pending')
                            <button wire:click="updateStatus('pending')" 
                                class="w-full bg-yellow-600/20 hover:bg-yellow-600/30 border border-yellow-500/30 text-yellow-400 py-2 px-4 rounded-lg font-semibold transition-all">
                                Mark as Pending
                            </button>
                            @endif
                            @if($booking->status !== 'approved')
                            <button wire:click="updateStatus('approved')" 
                                class="w-full bg-blue-600/20 hover:bg-blue-600/30 border border-blue-500/30 text-blue-400 py-2 px-4 rounded-lg font-semibold transition-all">
                                Approve Booking
                            </button>
                            @endif
                            @if($booking->status !== 'completed')
                            <button wire:click="updateStatus('completed')" 
                                class="w-full bg-garage-neon/20 hover:bg-garage-neon/30 border border-garage-neon/30 text-garage-neon py-2 px-4 rounded-lg font-semibold transition-all">
                                Mark as Completed
                            </button>
                            @endif
                            @if($booking->status !== 'cancelled')
                            <button wire:click="updateStatus('cancelled')" 
                                wire:confirm="Are you sure you want to cancel this booking?"
                                class="w-full bg-red-600/20 hover:bg-red-600/30 border border-red-500/30 text-red-400 py-2 px-4 rounded-lg font-semibold transition-all">
                                Cancel Booking
                            </button>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-garage-steel mb-2 uppercase tracking-wider">Add Note (optional)</label>
                        <textarea wire:model="notes" 
                            class="w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon" 
                            rows="3" 
                            placeholder="Add a note about this status change..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Assign Staff -->
            @if($booking->status !== 'cancelled' && $booking->status !== 'completed')
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
                <h3 class="text-xl font-bold text-garage-neon mb-4 service-tag">ASSIGN STAFF</h3>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-garage-steel uppercase tracking-wider">Select Staff Member</label>
                    <select wire:change="assignStaff($event.target.value)" 
                        class="w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon">
                        <option value="">-- Select Staff --</option>
                        @foreach($availableStaff as $staff)
                            <option value="{{ $staff->id }}" {{ $booking->assigned_staff_id == $staff->id ? 'selected' : '' }}>
                                {{ $staff->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif

            <!-- Customer Information -->
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
                <h3 class="text-xl font-bold text-garage-neon mb-4 service-tag">CUSTOMER</h3>
                
                <!-- Garage Floor Divider -->
                <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4"></div>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-garage-steel uppercase tracking-wider">Name</p>
                        <p class="font-semibold text-garage-offwhite">{{ $booking->customer->getDisplayName() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-garage-steel uppercase tracking-wider">Email</p>
                        <p class="text-garage-offwhite">{{ $booking->customer->getContactEmail() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-garage-steel uppercase tracking-wider">Phone</p>
                        <p class="text-garage-offwhite">{{ $booking->customer->getContactPhone() }}</p>
                    </div>
                    @if($booking->customer->address)
                        <div>
                            <p class="text-sm text-garage-steel uppercase tracking-wider">Address</p>
                            <p class="text-garage-offwhite">{{ $booking->customer->address }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Vehicle Information -->
            <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
                <h3 class="text-xl font-bold text-garage-neon mb-4 service-tag">VEHICLE</h3>
                
                <!-- Garage Floor Divider -->
                <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4"></div>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-garage-steel uppercase tracking-wider">License Plate</p>
                        <p class="font-bold text-garage-neon font-mono">{{ $booking->vehicle->license_plate }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-garage-steel uppercase tracking-wider">Make & Model</p>
                        <p class="text-garage-offwhite">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-garage-steel uppercase tracking-wider">Year</p>
                        <p class="text-garage-offwhite">{{ $booking->vehicle->year }}</p>
                    </div>
                    @if($booking->vehicle->color)
                        <div>
                            <p class="text-sm text-garage-steel uppercase tracking-wider">Color</p>
                            <p class="text-garage-offwhite">{{ $booking->vehicle->color }}</p>
                        </div>
                    @endif
                    @if($booking->vehicle->vin)
                        <div>
                            <p class="text-sm text-garage-steel uppercase tracking-wider">VIN</p>
                            <p class="text-garage-offwhite text-xs font-mono">{{ $booking->vehicle->vin }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Assigned Staff -->
            @if($booking->assignedStaff)
                <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
                    <h3 class="text-xl font-bold text-garage-neon mb-4 service-tag">ASSIGNED STAFF</h3>
                    
                    <!-- Garage Floor Divider -->
                    <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4"></div>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-garage-steel uppercase tracking-wider">Name</p>
                            <p class="font-semibold text-garage-offwhite">{{ $booking->assignedStaff->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-garage-steel uppercase tracking-wider">Email</p>
                            <p class="text-garage-offwhite">{{ $booking->assignedStaff->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-garage-steel uppercase tracking-wider">Phone</p>
                            <p class="text-garage-offwhite">{{ $booking->assignedStaff->phone }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Payment Information -->
            @if($booking->payment)
                <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20">
                    <h3 class="text-xl font-bold text-garage-neon mb-4 service-tag">PAYMENT</h3>
                    
                    <!-- Garage Floor Divider -->
                    <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4"></div>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-garage-steel uppercase tracking-wider">Amount</p>
                            <p class="font-bold text-garage-neon font-mono text-xl">₱{{ number_format($booking->payment->amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-garage-steel uppercase tracking-wider">Status</p>
                            <span class="inline-block px-3 py-1 text-xs rounded-full border 
                                {{ $booking->payment->status === 'completed' ? 'bg-garage-neon/20 text-garage-neon border-garage-neon/30' : 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30' }} font-semibold">
                                {{ ucfirst($booking->payment->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-garage-steel uppercase tracking-wider">Payment Method</p>
                            <p class="text-garage-offwhite">{{ ucfirst($booking->payment->payment_method) }}</p>
                        </div>
                        @if($booking->payment->transaction_id)
                            <div>
                                <p class="text-sm text-garage-steel uppercase tracking-wider">Transaction ID</p>
                                <p class="text-garage-offwhite text-xs font-mono">{{ $booking->payment->transaction_id }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
