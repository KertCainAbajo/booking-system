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

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Service History</h2>
        <p class="text-gray-600">View your past service bookings</p>
    </div>

    @forelse($historyBookings as $booking)
        <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r {{ $booking->status === 'completed' ? 'from-blue-500 to-blue-600' : 'from-gray-500 to-gray-600' }} px-6 py-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Booking #{{ $booking->id }}</h3>
                        <p class="text-sm opacity-90">{{ $booking->booking_date->format('M d, Y') }} at {{ $booking->booking_time->format('h:i A') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 text-sm rounded-full font-semibold
                            {{ $booking->status === 'completed' ? 'bg-green-400 text-green-900' : 'bg-gray-300 text-gray-800' }}
                        ">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Vehicle Info -->
                <div class="mb-4">
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
                <div class="mb-4">
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

                <!-- Payment Status -->
                @if($booking->payment)
                    <div class="mb-4 bg-green-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-2">Payment</h4>
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-gray-700">Method: <span class="font-medium">{{ ucfirst($booking->payment->payment_method) }}</span></div>
                                <div class="text-sm text-gray-700">Date: <span class="font-medium">{{ $booking->payment->payment_date->format('M d, Y') }}</span></div>
                            </div>
                            <span class="px-3 py-1 text-sm rounded-full font-semibold bg-green-200 text-green-800">
                                {{ ucfirst($booking->payment->payment_status) }}
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Notes -->
                @if($booking->notes)
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">Notes</h4>
                        <p class="text-gray-700 bg-gray-50 rounded p-3">{{ $booking->notes }}</p>
                    </div>
                @endif

                <!-- Total -->
                <div class="border-t pt-4">
                    <div class="flex items-center justify-between text-lg">
                        <span class="font-semibold text-gray-900">Total Amount</span>
                        <span class="font-bold {{ $booking->status === 'completed' ? 'text-blue-600' : 'text-gray-600' }}">₱{{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white shadow rounded-lg p-12 text-center">
            <div class="text-6xl mb-4">📋</div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Service History</h3>
            <p class="text-gray-600 mb-6">You don't have any completed or cancelled bookings yet.</p>
            <a href="{{ route('customer.booking') }}" wire:navigate
                class="inline-block bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700">
                Create New Booking
            </a>
        </div>
    @endforelse
</div>
