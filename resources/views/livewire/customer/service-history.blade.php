<div>
    <!-- Back to Dashboard Button -->
    <div class="mb-4">
        <a href="{{ route('customer.dashboard') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Service History</h2>
        <p class="text-gray-600">View your past service bookings</p>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <p class="text-gray-500 text-center py-8">Your service history will appear here.</p>
    </div>
</div>
