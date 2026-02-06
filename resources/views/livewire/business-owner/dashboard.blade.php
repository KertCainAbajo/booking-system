<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Business Dashboard</h2>
        <p class="text-gray-600">Overview of your business performance</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500 mb-1">Today's Bookings</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['today_bookings'] }}</div>
                    <div class="text-xs text-green-600 mt-1">{{ $stats['completed_today'] }} completed</div>
                </div>
                <div class="text-4xl">📅</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500 mb-1">Pending Bookings</div>
                    <div class="text-3xl font-bold text-orange-600">{{ $stats['pending_bookings'] }}</div>
                    <div class="text-xs text-gray-600 mt-1">Awaiting confirmation</div>
                </div>
                <div class="text-4xl">⏳</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500 mb-1">Today's Revenue</div>
                    <div class="text-2xl font-bold text-green-600">₱{{ number_format($stats['today_revenue'], 2) }}</div>
                    <div class="text-xs text-gray-600 mt-1">Month: ₱{{ number_format($stats['month_revenue'], 2) }}</div>
                </div>
                <div class="text-4xl">💰</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Recent Bookings</h3>
            <div class="space-y-3">
                @forelse($recentBookings as $booking)
                    <div class="border-b pb-3">
                        <div class="flex items-center justify-between mb-1">
                            <div class="font-medium text-gray-900">{{ $booking->customer->user->name }}</div>
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            ">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $booking->vehicle->make }} {{ $booking->vehicle->model }} • {{ $booking->booking_date }}
                        </div>
                        <div class="text-sm font-semibold text-gray-900">₱{{ number_format($booking->total_amount, 2) }}</div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No bookings yet</p>
                @endforelse
            </div>
        </div>

        <!-- Top Services -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Top Services</h3>
            <div class="space-y-3">
                @forelse($topServices as $service)
                    <div class="flex items-center justify-between border-b pb-3">
                        <div>
                            <div class="font-medium text-gray-900">{{ $service->name }}</div>
                            <div class="text-sm text-gray-500">{{ $service->category->name }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold text-gray-900">{{ $service->booking_services_count }}</div>
                            <div class="text-xs text-gray-500">bookings</div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No service data yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Pending Payments Alert -->
    @if($stats['pending_payments'] > 0)
        <div class="mt-6 bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="text-orange-600 mr-3">⚠️</div>
                <div>
                    <h4 class="font-semibold text-orange-900">Pending Payments</h4>
                    <p class="text-sm text-orange-700 ">
                        ₱{{ number_format($stats['pending_payments'], 2) }} in pending payments need attention
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
