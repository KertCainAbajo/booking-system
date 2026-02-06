<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Book a Service</h2>

        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit="submitBooking">
            <!-- Select Vehicle -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Vehicle</label>
                <div class="flex gap-2">
                    <select wire:model="selectedVehicle" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Choose a vehicle</option>
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">
                                {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})
                            </option>
                        @endforeach
                    </select>
                    <button type="button" wire:click="$set('showNewVehicleForm', true)" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        + Add Vehicle
                    </button>
                </div>
                @error('selectedVehicle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- New Vehicle Form -->
            @if ($showNewVehicleForm)
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-bold mb-4">Add New Vehicle</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Make</label>
                            <input type="text" wire:model="newVehicle.make" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('newVehicle.make') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                            <input type="text" wire:model="newVehicle.model" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('newVehicle.model') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                            <input type="number" wire:model="newVehicle.year" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('newVehicle.year') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Plate Number</label>
                            <input type="text" wire:model="newVehicle.plate_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('newVehicle.plate_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <button type="button" wire:click="addNewVehicle" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Save Vehicle
                        </button>
                        <button type="button" wire:click="$set('showNewVehicleForm', false)" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            Cancel
                        </button>
                    </div>
                </div>
            @endif

            <!-- Select Service Category -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Service Category</label>
                <div class="grid grid-cols-2 gap-4">
                    @foreach ($categories as $category)
                        <button type="button" 
                                wire:click="$set('selectedCategory', {{ $category->id }})"
                                class="p-4 border-2 rounded-lg text-left transition-all
                                       {{ $selectedCategory == $category->id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'  }}">
                            <div class="font-bold text-gray-800">{{ $category->name }}</div>
                            <div class="text-sm text-gray-600">{{ $category->services->count() }} services</div>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Select Services -->
            @if ($selectedCategory && $services->count() > 0)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Services</label>
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @foreach ($services as $service)
                            <label class="flex items-start p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" wire:model.live="selectedServices" value="{{ $service->id }}" class="mt-1 mr-3">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-800">{{ $service->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $service->description }}</div>
                                    <div class="text-sm text-gray-500">Duration: ~{{ $service->estimated_duration_minutes }} mins</div>
                                </div>
                                <div class="font-bold text-blue-600">₱{{ number_format($service->base_price, 2) }}</div>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedServices') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            @endif

            <!-- Date and Time -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Booking Date</label>
                    <input type="date" wire:model="bookingDate" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('bookingDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Time</label>
                    <select wire:model="bookingTime" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="09:00">9:00 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="16:00">4:00 PM</option>
                    </select>
                    @error('bookingTime') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                <textarea wire:model="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
            </div>

            <!-- Estimated Total -->
            <div class="bg-blue-50 p-4 rounded-lg mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-medium text-gray-700">Estimated Total:</span>
                    <span class="text-2xl font-bold text-blue-600">₱{{ number_format($estimatedTotal, 2) }}</span>
                </div>
                <p class="text-sm text-gray-600 mt-1">Final price may vary based on parts and additional services</p>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                Submit Booking
            </button>
        </form>
    </div>
</div>
