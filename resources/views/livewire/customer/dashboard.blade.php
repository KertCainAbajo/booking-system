<div>
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-3xl font-bold text-gray-800">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600 mt-2">Manage your vehicle services and appointments</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('customer.booking') }}" wire:navigate class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-6 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold">Book Service</h3>
                        <p class="text-blue-100 text-sm">Schedule a new appointment</p>
                    </div>
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('customer.tracker') }}" wire:navigate class="bg-green-600 hover:bg-green-700 text-white rounded-lg p-6 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold">Track Booking</h3>
                        <p class="text-green-100 text-sm">Check your service status</p>
                    </div>
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('customer.history') }}" wire:navigate class="bg-purple-600 hover:bg-purple-700 text-white rounded-lg p-6 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold">Service History</h3>
                        <p class="text-purple-100 text-sm">View past services</p>
                    </div>
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </a>
        </div>

        <!-- All Bookings -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">All Bookings</h2>
                @if($allBookings->count() > 0)
                    <a href="{{ route('customer.tracker') }}" wire:navigate class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        View Active Bookings →
                    </a>
                @endif
            </div>

            @forelse($allBookings as $booking)
                <div class="border-b border-gray-200 py-4 last:border-b-0">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="font-semibold text-gray-900">Booking #{{ $booking->id }}</span>
                                <span class="px-2 py-1 text-xs rounded-full font-semibold
                                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $booking->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $booking->status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 mb-2">
                                <span class="inline-flex items-center mr-4">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $booking->booking_date->format('M d, Y') }}
                                </span>
                                <span class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $booking->booking_time->format('h:i A') }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-700 mb-2">
                                <strong>Vehicle:</strong> {{ $booking->vehicle->make }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->plate_number }})
                            </div>
                            <div class="text-sm text-gray-700">
                                <strong>Services:</strong> 
                                @foreach($booking->services as $service)
                                    <span class="inline-block mr-2">{{ $service->name }}@if(!$loop->last),@endif</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="text-right ml-4">
                            <div class="text-lg font-bold text-gray-900">₱{{ number_format($booking->total_amount, 2) }}</div>
                            @if(in_array($booking->status, ['pending', 'approved']))
                                <a href="{{ route('customer.tracker') }}" wire:navigate class="text-sm text-blue-600 hover:text-blue-700 font-medium mt-1 inline-block">
                                    Track →
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="text-gray-400 text-5xl mb-3">📋</div>
                    <p class="text-gray-600">No bookings yet</p>
                    <a href="{{ route('customer.booking') }}" wire:navigate class="inline-block mt-4 text-blue-600 hover:text-blue-700 font-medium">
                        Book your first service →
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
