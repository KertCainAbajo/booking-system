<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">System Monitoring</h2>
        <p class="text-gray-600">Overview of system performance and activity</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500 mb-1">Total Users</div>
            <div class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500 mb-1">Total Bookings</div>
            <div class="text-3xl font-bold text-gray-900">{{ $stats['total_bookings'] }}</div>
            <div class="text-xs text-orange-600 mt-1">{{ $stats['pending_bookings'] }} pending</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500 mb-1">Active Services</div>
            <div class="text-3xl font-bold text-gray-900">{{ $stats['active_services'] }}</div>
            <div class="text-xs text-gray-600 mt-1">of {{ $stats['total_services'] }} total</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500 mb-1">Total Revenue</div>
            <div class="text-3xl font-bold text-green-600">₱{{ number_format($stats['total_revenue'], 2) }}</div>
            <div class="text-xs text-orange-600 mt-1">₱{{ number_format($stats['pending_payments'], 2) }} pending</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Bookings by Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Bookings by Status</h3>
            <div class="space-y-3">
                @foreach($bookingsByStatus as $status)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full mr-2 
                                {{ $status->status === 'pending' ? 'bg-yellow-400' : '' }}
                                {{ $status->status === 'confirmed' ? 'bg-blue-400' : '' }}
                                {{ $status->status === 'in_progress' ? 'bg-purple-400' : '' }}
                                {{ $status->status === 'completed' ? 'bg-green-400' : '' }}
                                {{ $status->status === 'cancelled' ? 'bg-red-400' : '' }}
                            "></span>
                            <span class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $status->status)) }}</span>
                        </div>
                        <span class="font-semibold text-gray-900">{{ $status->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Recent Users</h3>
            <div class="space-y-3">
                @foreach($recentUsers as $user)
                    <div class="flex items-center justify-between border-b pb-2">
                        <div>
                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                            {{ ucfirst(str_replace('_', ' ', $user->role->name)) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h3 class="text-lg font-semibold mb-4">Recent Bookings</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentBookings as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">#{{ $booking->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $booking->customer->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $booking->booking_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $booking->status === 'in_progress' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">₱{{ number_format($booking->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
