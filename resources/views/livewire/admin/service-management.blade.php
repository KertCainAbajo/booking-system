<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Service Management</h2>
        <p class="text-gray-600">Manage services and pricing</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <!-- Search and Filter -->
        <div class="mb-4 flex flex-col sm:flex-row gap-4 justify-between">
            <div class="flex gap-2 flex-1">
                <input wire:model.live="search" type="text" placeholder="Search services..." 
                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <select wire:model.live="categoryFilter" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <button wire:click="createService" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                + Add Service
            </button>
        </div>

        <!-- Services Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($services as $service)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $service->name }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($service->description, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $service->category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">₱{{ number_format($service->base_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $service->estimated_duration_minutes }} min</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleActive({{ $service->id }})" 
                                    class="px-2 py-1 text-xs rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button wire:click="editService({{ $service->id }})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                <button wire:click="deleteService({{ $service->id }})" 
                                    onclick="return confirm('Are you sure?')" 
                                    class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $services->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <h3 class="text-lg font-bold mb-4">{{ $serviceId ? 'Edit Service' : 'Create Service' }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Service Name</label>
                        <input wire:model="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select wire:model="service_category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('service_category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Base Price (₱)</label>
                        <input wire:model="base_price" type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('base_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                        <input wire:model="estimated_duration_minutes" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('estimated_duration_minutes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center">
                        <input wire:model="is_active" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <label class="ml-2 text-sm text-gray-700">Active</label>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        Cancel
                    </button>
                    <button wire:click="saveService" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Save
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
