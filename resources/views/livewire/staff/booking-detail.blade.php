<div>
    <div class="mb-6">
        <a href="{{ route('staff.bookings') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            ← Back to Bookings
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Booking Details #{{ $booking->id }}</h2>
                <p class="text-gray-600">Manage booking information and status</p>
            </div>
            <span class="px-4 py-2 text-sm rounded-full 
                {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $booking->status === 'approved' ? 'bg-blue-100 text-blue-800' : '' }}
                {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
            ">
                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-800 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Full Name</label>
                        <p class="font-medium">{{ $booking->customer->user->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Email</label>
                        <p class="font-medium">{{ $booking->customer->user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Phone</label>
                        <p class="font-medium">{{ $booking->customer->user->phone }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Address</label>
                        <p class="font-medium">{{ $booking->customer->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Vehicle Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Make & Model</label>
                        <p class="font-medium">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Year</label>
                        <p class="font-medium">{{ $booking->vehicle->year }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Plate Number</label>
                        <p class="font-medium">{{ $booking->vehicle->plate_number }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">VIN</label>
                        <p class="font-medium">{{ $booking->vehicle->vin_number ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Details</h3>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-sm text-gray-600">Booking Date</label>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($booking->booking_date)->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Booking Time</label>
                        <p class="font-medium">{{ $booking->booking_time }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Received Date</label>
                        <p class="font-medium">{{ $booking->created_at->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Received Time</label>
                        <p class="font-medium">{{ $booking->created_at->format('h:i A') }}</p>
                        <p class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Total Amount</label>
                        <p class="font-medium text-lg text-green-600">₱{{ number_format($booking->total_amount, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Assigned To</label>
                        <p class="font-medium">{{ $booking->assignedStaff ? $booking->assignedStaff->name : 'Unassigned' }}</p>
                    </div>
                </div>

                @if($booking->notes)
                <div class="pt-4 border-t">
                    <label class="text-sm text-gray-600">Customer Notes</label>
                    <p class="mt-1">{{ $booking->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Services -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Services</h3>
                <div class="space-y-2">
                    @foreach($booking->services as $service)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <p class="font-medium">{{ $service->name }}</p>
                            <p class="text-sm text-gray-600">{{ $service->description }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">₱{{ number_format($service->pivot->price, 2) }}</p>
                            <p class="text-sm text-gray-600">Qty: {{ $service->pivot->quantity }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Status History -->
            @if($booking->statusLogs && $booking->statusLogs->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status History</h3>
                <div class="space-y-3">
                    @foreach($booking->statusLogs->sortByDesc('created_at') as $log)
                    <div class="flex items-start gap-3 pb-3 border-b last:border-b-0">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-blue-500"></div>
                        <div class="flex-1">
                            <p class="text-sm">
                                <span class="font-medium">{{ $log->user->name }}</span>
                                changed status from 
                                <span class="font-medium">{{ $log->old_status }}</span>
                                to 
                                <span class="font-medium">{{ $log->new_status }}</span>
                            </p>
                            @if($log->notes)
                            <p class="text-sm text-gray-600 mt-1">{{ $log->notes }}</p>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Update Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h3>

                <div class="space-y-4">
                    <div>
                        <div class="space-y-2">
                            @if($booking->status === 'pending')
                            <button wire:click="updateStatus('approved')" 
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                                Approve Booking
                            </button>
                            @endif

                            @if($booking->status === 'approved')
                            <button wire:click="updateStatus('completed')" 
                                class="w-full bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
                                Mark as Completed
                            </button>
                            @endif

                            @if(!in_array($booking->status, ['completed', 'cancelled']))
                            <button wire:click="updateStatus('cancelled')" 
                                class="w-full bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">
                                Cancel Booking
                            </button>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Add Note (optional)</label>
                        <textarea wire:model="notes" 
                            class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                            rows="3" 
                            placeholder="Add a note about this status change..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            @if($booking->payment)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Amount</span>
                        <span class="font-medium">₱{{ number_format($booking->payment->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Method</span>
                        <span class="font-medium">{{ ucfirst($booking->payment->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status</span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $booking->payment->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($booking->payment->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Generate Invoice -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Documents</h3>
                <a href="{{ route('staff.invoice', $booking->id) }}" 
                    class="w-full block text-center bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700">
                    Generate Invoice
                </a>
            </div>
        </div>
    </div>
</div>
