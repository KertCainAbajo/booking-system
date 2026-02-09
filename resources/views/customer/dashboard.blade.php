<x-customer-layout>
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-white rounded-lg shadow-md p-6 border-2 border-green-800 border-r-2 border-r-black">
            <h1 class="text-3xl font-bold text-gray-800">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600 mt-2">Manage your vehicle services and appointments</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('customer.book') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-6 transition-colors">
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

            <a href="{{ route('customer.tracker') }}" class="bg-green-600 hover:bg-green-700 text-white rounded-lg p-6 transition-colors">
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

            <a href="{{ route('customer.history') }}" class="bg-purple-600 hover:bg-purple-700 text-white rounded-lg p-6 transition-colors">
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

        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow-md p-6 border-2 border-green-800 border-r-2 border-r-black">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Bookings</h2>
            <p class="text-gray-600">Your recent service appointments will appear here</p>
        </div>
    </div>
</x-customer-layout>
