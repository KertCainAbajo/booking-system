<div>
    <!-- Back to Dashboard Button -->
    <div class="mb-4">
        <a href="{{ route('customer.dashboard') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Track Your Bookings</h2>
        <p class="text-gray-600">Monitor the status of your active service bookings</p>
    </div>

    @forelse($activeBookings as $booking)
        <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Booking #{{ $booking->id }}</h3>
                        <p class="text-sm opacity-90">{{ $booking->booking_date }} at {{ $booking->booking_time }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 text-sm rounded-full font-semibold
                            {{ $booking->status === 'pending' ? 'bg-yellow-400 text-yellow-900' : '' }}
                            {{ $booking->status === 'approved' ? 'bg-green-400 text-green-900' : '' }}
                            {{ $booking->status === 'completed' ? 'bg-blue-400 text-blue-900' : '' }}
                        ">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Vehicle Info -->
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-900 mb-2">Vehicle</h4>
                    <div class="flex items-center text-gray-700">
                        <span class="text-2xl mr-3">🚗</span>
                        <div>
                            <div class="font-medium">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->year }})</div>
                            <div class="text-sm text-gray-500">{{ $booking->vehicle->plate_number }}</div>
                        </div>
                    </div>
                </div>

                <!-- Services -->
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Services</h4>
                    <div class="space-y-2">
                        @foreach($booking->services as $service)
                            <div class="flex items-center justify-between bg-gray-50 rounded p-3">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $service->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $service->estimated_duration }} minutes</div>
                                </div>
                                <div class="font-semibold text-gray-900">₱{{ number_format($service->pivot->price, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Progress Tracker -->
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Progress</h4>
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center 
                                {{ in_array($booking->status, ['pending', 'approved', 'completed']) ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }}">
                                ✓
                            </div>
                            <div class="text-xs mt-2 text-center font-medium">Pending</div>
                        </div>
                        <div class="h-1 flex-1 
                            {{ in_array($booking->status, ['approved', 'completed']) ? 'bg-blue-600' : 'bg-gray-300' }}">
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center 
                                {{ in_array($booking->status, ['approved', 'completed']) ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }}">
                                ✓
                            </div>
                            <div class="text-xs mt-2 text-center font-medium">Approved</div>
                        </div>
                        <div class="h-1 flex-1 
                            {{ $booking->status === 'completed' ? 'bg-blue-600' : 'bg-gray-300' }}">
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center 
                                {{ $booking->status === 'completed' ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-600' }}">
                                ✓
                            </div>
                            <div class="text-xs mt-2 text-center font-medium">Completed</div>
                        </div>
                    </div>
                </div>

                <!-- Staff Assignment -->
                @if($booking->assignedStaff)
                    <div class="mb-6 bg-blue-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-2">Assigned Staff</h4>
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">👨‍🔧</span>
                            <div>
                                <div class="font-medium text-gray-900">{{ $booking->assignedStaff->name }}</div>
                                <div class="text-sm text-gray-600">{{ $booking->assignedStaff->phone }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Notes -->
                @if($booking->notes)
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-2">Notes</h4>
                        <p class="text-gray-700 bg-gray-50 rounded p-3">{{ $booking->notes }}</p>
                    </div>
                @endif

                <!-- Total -->
                <div class="border-t pt-4">
                    <div class="flex items-center justify-between text-lg">
                        <span class="font-semibold text-gray-900">Total Amount</span>
                        <span class="font-bold text-blue-600">₱{{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                </div>

                <!-- Cancel Button -->
                @if(in_array($booking->status, ['pending', 'approved']))
                    <div class="mt-4 pt-4 border-t flex justify-center">
                        <button 
                            wire:click="cancelBooking({{ $booking->id }})" 
                            wire:confirm="Are you sure you want to cancel this booking?"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel Booking
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <div class="text-6xl mb-4">📋</div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Active Bookings</h3>
            <p class="text-gray-600 mb-6">You don't have any active bookings at the moment.</p>
            <a href="{{ route('customer.booking') }}" 
                class="inline-block bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700">
                Create New Booking
            </a>
        </div>
    @endforelse
</div>
