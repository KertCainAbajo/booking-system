<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Booking Calendar</h2>
            <p class="text-gray-600">View customer appointments and bookings</p>
        </div>
        <a href="{{ route('staff.dashboard') }}" 
            class="flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-500">Total This Month</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
            <div class="text-xs text-gray-500">Pending</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['approved'] }}</div>
            <div class="text-xs text-gray-500">Approved</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</div>
            <div class="text-xs text-gray-500">Completed</div>
        </div>
    </div>

    <!-- Calendar Controls -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Month Navigation -->
            <div class="flex items-center gap-4">
                <button wire:click="previousMonth" 
                    class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <h3 class="text-xl font-bold text-gray-900 min-w-[200px] text-center">{{ $monthName }}</h3>
                <button wire:click="nextMonth" 
                    class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <button wire:click="today" 
                    class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Today
                </button>
            </div>

            <!-- Filter -->
            <div class="w-full sm:w-auto">
                <select wire:model.live="statusFilter" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Day Headers -->
        <div class="grid grid-cols-7 gap-px bg-gray-200">
            <div class="bg-gray-50 p-3 text-center font-semibold text-gray-700 text-sm">Sun</div>
            <div class="bg-gray-50 p-3 text-center font-semibold text-gray-700 text-sm">Mon</div>
            <div class="bg-gray-50 p-3 text-center font-semibold text-gray-700 text-sm">Tue</div>
            <div class="bg-gray-50 p-3 text-center font-semibold text-gray-700 text-sm">Wed</div>
            <div class="bg-gray-50 p-3 text-center font-semibold text-gray-700 text-sm">Thu</div>
            <div class="bg-gray-50 p-3 text-center font-semibold text-gray-700 text-sm">Fri</div>
            <div class="bg-gray-50 p-3 text-center font-semibold text-gray-700 text-sm">Sat</div>
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7 gap-px bg-gray-200">
            @foreach($calendar as $week)
                @foreach($week as $day)
                    @if($day === null)
                        <div class="bg-gray-50 min-h-[120px] p-2"></div>
                    @else
                        <div class="bg-white min-h-[120px] p-2 {{ $day['isToday'] ? 'ring-2 ring-blue-500' : '' }} {{ $day['isPast'] ? 'bg-gray-50' : '' }} hover:bg-blue-50 transition">
                            <!-- Day Number -->
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-semibold {{ $day['isToday'] ? 'bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center' : 'text-gray-700' }}">
                                    {{ $day['day'] }}
                                </span>
                                @if($day['bookings']->count() > 0)
                                    <span class="text-xs bg-red-500 text-white rounded-full px-2 py-0.5 font-semibold">
                                        {{ $day['bookings']->count() }}
                                    </span>
                                @endif
                            </div>

                            <!-- Bookings -->
                            <div class="space-y-1">
                                @foreach($day['bookings']->take(3) as $booking)
                                    <a href="{{ route('staff.booking.detail', $booking->id) }}" 
                                        class="block text-xs p-1 rounded truncate
                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : '' }}
                                        {{ $booking->status === 'approved' ? 'bg-blue-100 text-blue-800 hover:bg-blue-200' : '' }}
                                        {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800 hover:bg-green-200' : '' }}
                                        {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800 hover:bg-red-200' : '' }}">
                                        <div class="font-medium">{{ $booking->booking_time }}</div>
                                        <div class="truncate">{{ $booking->customer->user->name }}</div>
                                    </a>
                                @endforeach
                                @if($day['bookings']->count() > 3)
                                    <div class="text-xs text-gray-500 font-medium pl-1">
                                        +{{ $day['bookings']->count() - 3 }} more
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>

    <!-- Legend -->
    <div class="mt-6 bg-white rounded-lg shadow p-4">
        <h4 class="font-semibold text-gray-700 mb-3">Status Legend:</h4>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-yellow-100 border border-yellow-300 rounded"></div>
                <span class="text-sm text-gray-600">Pending</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-blue-100 border border-blue-300 rounded"></div>
                <span class="text-sm text-gray-600">Approved</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-green-100 border border-green-300 rounded"></div>
                <span class="text-sm text-gray-600">Completed</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-red-100 border border-red-300 rounded"></div>
                <span class="text-sm text-gray-600">Cancelled</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-blue-600 rounded-full"></div>
                <span class="text-sm text-gray-600">Today</span>
            </div>
        </div>
    </div>
</div>
