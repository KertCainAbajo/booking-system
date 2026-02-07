<div>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Booking Details #{{ $booking->id }}</h2>
                <p class="text-gray-600">View complete booking information</p>
            </div>
            <a href="{{ route('admin.bookings') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                ← Back to Bookings
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Booking Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Booking ID</p>
                        <p class="font-semibold text-gray-900">#{{ $booking->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-blue-100 text-blue-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="inline-block px-3 py-1 text-sm rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Booking Date</p>
                        <p class="font-semibold text-gray-900">{{ $booking->booking_date->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Booking Time</p>
                        <p class="font-semibold text-gray-900">{{ $booking->booking_time->format('h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Received Date</p>
                        <p class="font-semibold text-gray-900">{{ $booking->created_at->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Received Time</p>
                        <p class="font-semibold text-gray-900">{{ $booking->created_at->format('h:i A') }}</p>
                        <p class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">Total Amount</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($booking->total_amount, 2) }}</p>
                    </div>
                    @if($booking->notes)
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600">Notes</p>
                            <p class="text-gray-900">{{ $booking->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Services -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Services</h3>
                <div class="space-y-3">
                    @foreach($booking->services as $service)
                        <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $service->name }}</p>
                                <p class="text-sm text-gray-600">{{ $service->description }}</p>
                                <p class="text-xs text-gray-500">Quantity: {{ $service->pivot->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">${{ number_format($service->pivot->price * $service->pivot->quantity, 2) }}</p>
                                <p class="text-sm text-gray-600">${{ number_format($service->pivot->price, 2) }} each</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Status History -->
            @if($booking->statusLogs && $booking->statusLogs->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Status History</h3>
                    <div class="space-y-3">
                        @foreach($booking->statusLogs as $log)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-blue-600"></div>
                                <div class="ml-4">
                                    <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $log->status)) }}</p>
                                    <p class="text-sm text-gray-600">{{ $log->created_at->format('F d, Y h:i A') }}</p>
                                    @if($log->notes)
                                        <p class="text-sm text-gray-500 mt-1">{{ $log->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Customer</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="font-semibold text-gray-900">{{ $booking->customer->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="text-gray-900">{{ $booking->customer->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="text-gray-900">{{ $booking->customer->user->phone }}</p>
                    </div>
                    @if($booking->customer->address)
                        <div>
                            <p class="text-sm text-gray-600">Address</p>
                            <p class="text-gray-900">{{ $booking->customer->address }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Vehicle Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Vehicle</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">License Plate</p>
                        <p class="font-semibold text-gray-900">{{ $booking->vehicle->license_plate }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Make & Model</p>
                        <p class="text-gray-900">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Year</p>
                        <p class="text-gray-900">{{ $booking->vehicle->year }}</p>
                    </div>
                    @if($booking->vehicle->color)
                        <div>
                            <p class="text-sm text-gray-600">Color</p>
                            <p class="text-gray-900">{{ $booking->vehicle->color }}</p>
                        </div>
                    @endif
                    @if($booking->vehicle->vin)
                        <div>
                            <p class="text-sm text-gray-600">VIN</p>
                            <p class="text-gray-900 text-xs">{{ $booking->vehicle->vin }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Assigned Staff -->
            @if($booking->assignedStaff)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assigned Staff</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Name</p>
                            <p class="font-semibold text-gray-900">{{ $booking->assignedStaff->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="text-gray-900">{{ $booking->assignedStaff->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Phone</p>
                            <p class="text-gray-900">{{ $booking->assignedStaff->phone }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Payment Information -->
            @if($booking->payment)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Payment</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Amount</p>
                            <p class="font-semibold text-gray-900">${{ number_format($booking->payment->amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <span class="inline-block px-2 py-1 text-xs rounded-full 
                                {{ $booking->payment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($booking->payment->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Payment Method</p>
                            <p class="text-gray-900">{{ ucfirst($booking->payment->payment_method) }}</p>
                        </div>
                        @if($booking->payment->transaction_id)
                            <div>
                                <p class="text-sm text-gray-600">Transaction ID</p>
                                <p class="text-gray-900 text-xs">{{ $booking->payment->transaction_id }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
