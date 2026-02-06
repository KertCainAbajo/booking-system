<x-admin-layout>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-3xl font-bold text-gray-800">IT Admin Dashboard</h1>
            <p class="text-gray-600 mt-2">System administration and monitoring</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-blue-50 p-6 rounded-lg">
                <div class="text-sm text-blue-600">Total Users</div>
                <div class="text-3xl font-bold text-blue-700">{{ \App\Models\User::count() }}</div>
            </div>
            <div class="bg-green-50 p-6 rounded-lg">
                <div class="text-sm text-green-600">Total Bookings</div>
                <div class="text-3xl font-bold text-green-700">{{ \App\Models\Booking::count() }}</div>
            </div>
            <div class="bg-purple-50 p-6 rounded-lg">
                <div class="text-sm text-purple-600">Services Available</div>
                <div class="text-3xl font-bold text-purple-700">{{ \App\Models\Service::count() }}</div>
            </div>
            <div class="bg-orange-50 p-6 rounded-lg">
                <div class="text-sm text-orange-600">Inventory Items</div>
                <div class="text-3xl font-bold text-orange-700">{{ \App\Models\InventoryItem::count() }}</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-3 gap-4">
                <a href="{{ route('admin.users') }}" class="p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 text-center">
                    <div class="font-bold text-gray-800">Manage Users</div>
                </a>
                <a href="{{ route('admin.services') }}" class="p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 text-center">
                    <div class="font-bold text-gray-800">Manage Services</div>
                </a>
                <a href="{{ route('admin.monitoring') }}" class="p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 text-center">
                    <div class="font-bold text-gray-800">System Monitoring</div>
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
