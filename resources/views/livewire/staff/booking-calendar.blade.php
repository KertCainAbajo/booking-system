<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Booking Calendar</h2>
        <p class="text-gray-600">Manage today's appointments and services</p>
    </div>

    <!-- Controls -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Date</label>
                <input wire:model.live="selectedDate" type="date" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                <select wire:model.live="statusFilter" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-500">Total</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
            <div class="text-xs text-gray-500">Pending</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['confirmed'] }}</div>
            <div class="text-xs text-gray-500">Confirmed</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $stats['in_progress'] }}</div>
            <div class="text-xs text-gray-500">In Progress</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</div>
            <div class="text-xs text-gray-500">Completed</div>
        </div>
    </div>

    <!-- Bookings List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="divide-y divide-gray-200">
            @forelse($bookings as $booking)
                <div class="p-6 hover:bg-gray-50">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $booking->customer->user->name }}</h3>
                                <span class="ml-3 px-2 py-1 text-xs rounded-full 
                                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $booking->status === 'in_progress' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-gray-600 mb-2">
                                <div>🚗 {{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</div>
                                <div>🕐 {{ $booking->booking_time }}</div>
                                <div>📱 {{ $booking->customer->user->phone }}</div>
                            </div>

                            <div class="text-sm text-gray-700">
                                <strong>Services:</strong>
                                {{ $booking->services->pluck('name')->join(', ') }}
                            </div>

                            @if($booking->notes)
                                <div class="mt-2 text-sm text-gray-600">
                                    <strong>Notes:</strong> {{ $booking->notes }}
                                </div>
                            @endif
                        </div>

                        <div class="text-right ml-4">
                            <div class="text-lg font-bold text-gray-900">₱{{ number_format($booking->total_amount, 2) }}</div>
                            <a href="{{ route('staff.booking.detail', $booking->id) }}" 
                                class="mt-2 inline-block text-blue-600 hover:text-blue-800 text-sm">
                                View Details →
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-gray-500">
                    <div class="text-6xl mb-4">📅</div>
                    <p class="text-lg">No bookings for {{ $selectedDate }}</p>
                    <p class="text-sm">Change the date to view other bookings</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
