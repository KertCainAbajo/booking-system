<div class="max-w-4xl mx-auto">
    <!-- Back to Dashboard Button -->
    <div class="mb-4">
        <a href="{{ route('customer.dashboard') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>

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
                                {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }} - {{ $vehicle->plate_number }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" wire:click="$set('showNewVehicleForm', true)" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors whitespace-nowrap">
                        + Add Vehicle
                    </button>
                </div>
                @error('selectedVehicle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- New Vehicle Form -->
            @if ($showNewVehicleForm)
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border-2 border-blue-200">
                    <h3 class="font-bold mb-4 text-lg text-gray-800">Add New Vehicle</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Make (Brand) *</label>
                            <select wire:model="newVehicle.make" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select a brand</option>
                                @foreach ($carBrands as $brand)
                                    <option value="{{ $brand }}">{{ $brand }}</option>
                                @endforeach
                            </select>
                            @error('newVehicle.make') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Model *</label>
                            <input type="text" wire:model="newVehicle.model" placeholder="e.g., Civic, Camry, Mustang" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('newVehicle.model') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Year *</label>
                            <select wire:model="newVehicle.year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select year</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                            @error('newVehicle.year') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Plate Number *</label>
                            <input type="text" wire:model="newVehicle.plate_number" placeholder="e.g., ABC-1234" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('newVehicle.plate_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">VIN Number (Optional)</label>
                            <input type="text" wire:model="newVehicle.vin_number" placeholder="17-digit VIN" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('newVehicle.vin_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <button type="button" wire:click="addNewVehicle" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Save Vehicle
                        </button>
                        <button type="button" wire:click="$set('showNewVehicleForm', false)" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            @endif

            <!-- Services Section with Grid Layout -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Select Services</label>

                @if (session()->has('services-saved'))
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-init="setTimeout(() => show = false, 5000)"
                         x-transition:leave="transition ease-in duration-1000"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('services-saved') }}
                    </div>
                @endif
                
                <div class="grid grid-cols-3 gap-6">
                    <!-- Left: Categories and Services (2/3 width) -->
                    <div class="col-span-2 space-y-3">
                        @foreach ($categories as $category)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <!-- Category Header -->
                                <button type="button" 
                                        wire:click="toggleCategory({{ $category->id }})"
                                        class="w-full p-4 bg-gray-50 hover:bg-gray-100 transition-colors flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="text-lg mr-2">
                                            {{ in_array($category->id, $expandedCategories) ? '▼' : '▶' }}
                                        </span>
                                        <div class="text-left">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-800">{{ $category->name }}</span>
                                                @if ($categoryCounts[$category->id] > 0)
                                                    <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-blue-600 rounded-full">
                                                        {{ $categoryCounts[$category->id] }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-600">{{ $category->services->count() }} services available</div>
                                        </div>
                                    </div>
                                </button>

                                <!-- Services List (Expandable) -->
                                @if (in_array($category->id, $expandedCategories))
                                    <div class="p-3 bg-white space-y-2">
                                        @foreach ($category->services as $service)
                                            <div wire:click="toggleService({{ $service->id }})"
                                                 class="flex items-start p-3 border-2 rounded-lg cursor-pointer transition-all hover:shadow-md
                                                        {{ in_array($service->id, $selectedServices) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
                                                <div class="flex-shrink-0 mt-1 mr-3">
                                                    <div class="w-5 h-5 rounded border-2 flex items-center justify-center
                                                                {{ in_array($service->id, $selectedServices) ? 'bg-blue-500 border-blue-500' : 'border-gray-300' }}">
                                                        @if (in_array($service->id, $selectedServices))
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="font-medium text-gray-800">{{ $service->name }}</div>
                                                    <div class="text-sm text-gray-600 mt-1">{{ $service->description }}</div>
                                                    <div class="text-sm text-gray-500 mt-1">⏱ ~{{ $service->estimated_duration_minutes }} mins</div>
                                                </div>
                                                <div class="flex-shrink-0 ml-3 font-bold text-blue-600">
                                                    ₱{{ number_format($service->base_price, 2) }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Right: Selected Services Summary (1/3 width) -->
                    <div class="col-span-1">
                        <div class="sticky top-4 flex flex-col border-2 {{ $servicesConfirmed ? 'border-green-300 bg-green-50' : 'border-blue-200 bg-blue-50' }} rounded-lg overflow-hidden transition-all duration-500 ease-in-out h-[380px]">
                            <!-- Fixed Header -->
                            <div class="flex-shrink-0 {{ $servicesConfirmed ? 'bg-green-600' : 'bg-blue-600' }} text-white p-4">
                                <h3 class="font-bold text-lg">Selected Services</h3>
                                <p class="text-sm {{ $servicesConfirmed ? 'text-green-100' : 'text-blue-100' }}">
                                    {{ count($selectedServices) }} service(s)
                                </p>
                            </div>

                            <!-- Scrollable Services List -->
                            <div class="flex-1 overflow-y-auto min-h-0" 
                                 x-data="{ scrollToBottom: false }"
                                 x-init="$watch('scrollToBottom', value => { if(value) { $el.scrollTop = $el.scrollHeight; scrollToBottom = false; } })"
                                 wire:key="services-list">
                                @if (count($selectedServices) > 0)
                                    <div class="p-4 space-y-2">
                                        @foreach ($allSelectedServices as $index => $service)
                                            <div wire:key="service-{{ $service->id }}"
                                                 x-data="{ show: false }"
                                                 x-init="setTimeout(() => show = true, {{ $index * 50 }})"
                                                 x-show="show"
                                                 x-transition:enter="transition ease-out duration-300"
                                                 x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                                                 x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                                                 class="bg-white rounded-lg p-3 border-2 border-gray-200 relative group hover:shadow-md transition-shadow">
                                                <button type="button" 
                                                        wire:click="removeService({{ $service->id }})"
                                                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-all shadow-md opacity-0 group-hover:opacity-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                                <div class="pr-4">
                                                    <div class="font-medium text-gray-800 text-sm">{{ $service->name }}</div>
                                                    <div class="text-xs text-gray-500 mt-1">⏱ ~{{ $service->estimated_duration_minutes }} mins</div>
                                                    <div class="font-semibold text-blue-600 mt-1">₱{{ number_format($service->base_price, 2) }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-4">
                                        <div class="text-center py-8 text-gray-500">
                                            <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            <p class="text-sm font-medium">No services selected</p>
                                            <p class="text-xs mt-1">Click on services to add</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Sticky Footer (Subtotal & Action Button) -->
                            @if (count($selectedServices) > 0)
                                <div class="flex-shrink-0 p-4 bg-white border-t-2 {{ $servicesConfirmed ? 'border-green-200' : 'border-blue-200' }}">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="font-semibold text-gray-700">Subtotal:</span>
                                        <span class="text-xl font-bold {{ $servicesConfirmed ? 'text-green-600' : 'text-blue-600' }}" 
                                              x-data="{ amount: {{ $estimatedTotal }} }"
                                              x-init="$watch('amount', value => { 
                                                  $el.classList.add('scale-110'); 
                                                  setTimeout(() => $el.classList.remove('scale-110'), 200);
                                              })"
                                              class="transition-transform duration-200">
                                            ₱{{ number_format($estimatedTotal, 2) }}
                                        </span>
                                    </div>

                                    <button type="button" 
                                            wire:click="confirmServices"
                                            class="w-full py-3 {{ $servicesConfirmed ? 'bg-green-600 hover:bg-green-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-semibold rounded-lg transition-all hover:shadow-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ $servicesConfirmed ? 'Update Services' : 'Save & Continue' }}
                                    </button>

                                    @if ($servicesConfirmed)
                                        <p class="text-xs text-center text-gray-600 mt-2">You can still add or remove services</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                        @error('selectedServices') 
                            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> 
                        @enderror
                    </div>
                </div>
            </div>

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
