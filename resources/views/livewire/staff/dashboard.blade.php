<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Staff Dashboard</h2>
        <p class="text-gray-600">Welcome back! Here's your overview for today</p>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <a href="{{ route('staff.calendar') }}" 
            class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6 hover:from-blue-600 hover:to-blue-700 transition transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-1">View Calendar</h3>
                    <p class="text-blue-100 text-sm">See all bookings in calendar view</p>
                </div>
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </a>

        <a href="{{ route('staff.profile') }}" 
            class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6 hover:from-purple-600 hover:to-purple-700 transition transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-1">My Profile</h3>
                    <p class="text-purple-100 text-sm">View and update your profile</p>
                </div>
                <svg class="w-12 h-12 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
        </a>
    </div>

    <!-- Today's Stats -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Overview</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-gray-900">{{ $todayStats['total'] }}</div>
                <div class="text-xs text-gray-500">Total Bookings</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $todayStats['pending'] }}</div>
                <div class="text-xs text-gray-500">Pending</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $todayStats['approved'] }}</div>
                <div class="text-xs text-gray-500">Approved</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $todayStats['completed'] }}</div>
                <div class="text-xs text-gray-500">Completed</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-red-600">{{ $todayStats['cancelled'] }}</div>
                <div class="text-xs text-gray-500">Cancelled</div>
            </div>
        </div>
    </div>

    <!-- This Week's Stats -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">This Week's Stats</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-gray-900">{{ $weekStats['total'] }}</div>
                <div class="text-xs text-gray-500">Total Bookings</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $weekStats['pending'] }}</div>
                <div class="text-xs text-gray-500">Pending</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $weekStats['approved'] }}</div>
                <div class="text-xs text-gray-500">Approved</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $weekStats['completed'] }}</div>
                <div class="text-xs text-gray-500">Completed</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-red-600">{{ $weekStats['cancelled'] }}</div>
                <div class="text-xs text-gray-500">Cancelled</div>
            </div>
        </div>
    </div>

    <!-- Today's Bookings -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Today's Bookings</h3>
            @if($todayBookings->count() > 0)
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $todayBookings->count() }} booking(s)
                </span>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            @forelse($todayBookings as $booking)
                <div class="p-4 border-b border-gray-200 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-sm font-semibold text-gray-900">{{ $booking->booking_time }}</span>
                                <h4 class="font-semibold text-gray-900">{{ $booking->customer->user->name }}</h4>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $booking->status === 'approved' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <span>🚗 {{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</span>
                                <span class="ml-4">{{ $booking->services->pluck('name')->join(', ') }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-gray-900">₱{{ number_format($booking->total_amount, 2) }}</div>
                            <a href="{{ route('staff.booking.detail', $booking->id) }}" 
                                class="text-blue-600 hover:text-blue-800 text-sm">
                                View Details →
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <div class="text-5xl mb-3">📅</div>
                    <p class="text-lg">No bookings for today</p>
                    <p class="text-sm">Enjoy your day!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Upcoming Bookings -->
    @if($upcomingBookings->count() > 0)
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Upcoming Bookings (Next 7 Days)</h3>
                <a href="{{ route('staff.calendar') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    View All in Calendar →
                </a>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                @foreach($upcomingBookings as $booking)
                    <div class="p-4 border-b border-gray-200 last:border-b-0 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                    </span>
                                    <span class="text-sm text-gray-600">{{ $booking->booking_time }}</span>
                                    <h4 class="font-semibold text-gray-900">{{ $booking->customer->user->name }}</h4>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $booking->status === 'approved' ? 'bg-blue-100 text-blue-800' : '' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    🚗 {{ $booking->vehicle->make }} {{ $booking->vehicle->model }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-gray-900">₱{{ number_format($booking->total_amount, 2) }}</div>
                                <a href="{{ route('staff.booking.detail', $booking->id) }}" 
                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                    Details →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
