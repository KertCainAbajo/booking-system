<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Booking Management</h2>
        <p class="text-gray-600">View and manage all bookings with received date and time</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-600">Total Bookings</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-yellow-50 rounded-lg shadow p-4">
            <p class="text-sm text-yellow-800">Pending</p>
            <p class="text-2xl font-bold text-yellow-900">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-blue-50 rounded-lg shadow p-4">
            <p class="text-sm text-blue-800">Approved</p>
            <p class="text-2xl font-bold text-blue-900">{{ $stats['approved'] }}</p>
        </div>
        <div class="bg-green-50 rounded-lg shadow p-4">
            <p class="text-sm text-green-800">Completed</p>
            <p class="text-2xl font-bold text-green-900">{{ $stats['completed'] }}</p>
        </div>
        <div class="bg-red-50 rounded-lg shadow p-4">
            <p class="text-sm text-red-800">Cancelled</p>
            <p class="text-2xl font-bold text-red-900">{{ $stats['cancelled'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <!-- Search and Filters -->
        <div class="mb-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <input wire:model.live="search" type="text" placeholder="Search by customer, vehicle, or booking ID..." 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <select wire:model.live="statusFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <input wire:model.live="dateFrom" type="date" placeholder="From Date" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <input wire:model.live="dateTo" type="date" placeholder="To Date" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th wire:click="sortByColumn('id')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">
                            <div class="flex items-center gap-1">
                                Booking ID
                                @if($sortBy === 'id')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                        <th wire:click="sortByColumn('booking_date')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">
                            <div class="flex items-center gap-1">
                                Booking Date
                                @if($sortBy === 'booking_date')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking Time</th>
                        <th wire:click="sortByColumn('created_at')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100">
                            <div class="flex items-center gap-1">
                                Received At
                                @if($sortBy === 'created_at')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap font-medium text-gray-900">
                                #{{ $booking->id }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $booking->customer->user->name }}</div>
                                    <div class="text-gray-500">{{ $booking->customer->user->email }}</div>
                                    <div class="text-gray-500">{{ $booking->customer->user->phone }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $booking->vehicle->license_plate }}</div>
                                    <div class="text-gray-500">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</div>
                                    <div class="text-gray-500 text-xs">{{ $booking->vehicle->year }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $booking->booking_date->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $booking->booking_time->format('h:i A') }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $booking->created_at->format('M d, Y') }}</div>
                                    <div class="text-gray-500">{{ $booking->created_at->format('h:i A') }}</div>
                                    <div class="text-xs text-gray-400">{{ $booking->created_at->diffForHumans() }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ${{ number_format($booking->total_amount, 2) }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <button wire:click="viewBooking({{ $booking->id }})" class="text-blue-600 hover:text-blue-900">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                No bookings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
