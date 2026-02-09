<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header with Back Button -->
    <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-white service-tag mb-2">SERVICE BOOKING</h2>
            <p class="text-white text-sm sm:text-base">Schedule your vehicle maintenance appointment</p>
        </div>
        <a href="{{ route('customer.dashboard') }}" wire:navigate 
           class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 bg-garage-forest hover:bg-garage-darkgreen text-garage-offwhite text-sm sm:text-base font-semibold rounded-lg transition-all border border-garage-neon/20 hover:border-garage-neon/40 w-fit">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="service-tag">BACK</span>
        </a>
    </div>

    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 md:p-8 border border-garage-neon/30">

        <x-success-modal />

        <form wire:submit="submitBooking">
            <!-- Select Vehicle -->
            <div class="mb-6 sm:mb-8">
                <label class="block text-xs sm:text-sm font-bold text-white mb-2 sm:mb-3 service-tag flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                    SELECT VEHICLE
                </label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <select wire:model="selectedVehicle" class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base bg-garage-charcoal border-2 border-garage-neon/30 text-garage-offwhite rounded-lg focus:ring-2 focus:ring-garage-neon focus:border-garage-neon font-semibold">
                        <option value="">Choose a vehicle</option>
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">
                                {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }} - {{ $vehicle->plate_number }}
                            </option>
                        @endforeach>
                    </select>
                    <button type="button" wire:click="$set('showNewVehicleForm', true)" 
                            class="px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base bg-garage-neon hover:bg-garage-emerald text-garage-black rounded-lg transition-all shadow-neon-green font-bold service-tag whitespace-nowrap">
                        + ADD
                    </button>
                </div>
                @error('selectedVehicle') <span class="text-red-400 text-sm mt-2 block">{{ $message }}</span> @enderror
            </div>

            <!-- New Vehicle Form -->
            @if ($showNewVehicleForm)
                <div class="mb-6 sm:mb-8 p-4 sm:p-6 bg-garage-charcoal/70 rounded-lg border-2 border-garage-neon/40">
                    <h3 class="font-bold mb-4 sm:mb-5 text-lg sm:text-xl text-white service-tag flex items-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        ADD NEW VEHICLE
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-white mb-2">MAKE (BRAND) *</label>
                            <select wire:model="newVehicle.make" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base bg-garage-black border-2 border-garage-steel/30 text-garage-offwhite rounded-lg focus:ring-2 focus:ring-garage-neon focus:border-garage-neon">
                                <option value="">Select a brand</option>
                                @foreach ($carBrands as $brand)
                                    <option value="{{ $brand }}">{{ $brand }}</option>
                                @endforeach
                            </select>
                            @error('newVehicle.make') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-white mb-2">MODEL *</label>
                            <input type="text" wire:model="newVehicle.model" placeholder="e.g., Civic, Camry" class="w-full px-4 py-3 bg-garage-black border-2 border-garage-steel/30 text-garage-offwhite rounded-lg focus:ring-2 focus:ring-garage-neon focus:border-garage-neon placeholder-garage-steel/50">
                            @error('newVehicle.model') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-white mb-2">YEAR *</label>
                            <select wire:model="newVehicle.year" class="w-full px-4 py-3 bg-garage-black border-2 border-garage-steel/30 text-garage-offwhite rounded-lg focus:ring-2 focus:ring-garage-neon focus:border-garage-neon">
                                <option value="">Select year</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                            @error('newVehicle.year') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-white mb-2">PLATE NUMBER *</label>
                            <input type="text" wire:model="newVehicle.plate_number" placeholder="e.g., ABC-1234" class="w-full px-4 py-3 bg-garage-black border-2 border-garage-steel/30 text-garage-offwhite rounded-lg focus:ring-2 focus:ring-garage-neon focus:border-garage-neon placeholder-garage-steel/50">
                            @error('newVehicle.plate_number') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-white mb-2">VIN NUMBER (Optional)</label>
                            <input type="text" wire:model="newVehicle.vin_number" placeholder="17-digit VIN" class="w-full px-4 py-3 bg-garage-black border-2 border-garage-steel/30 text-garage-offwhite rounded-lg focus:ring-2 focus:ring-garage-neon focus:border-garage-neon placeholder-garage-steel/50">
                            @error('newVehicle.vin_number') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button type="button" wire:click="addNewVehicle" class="px-6 py-3 bg-garage-neon hover:bg-garage-emerald text-garage-black rounded-lg transition-all font-bold service-tag shadow-neon-green">
                            SAVE VEHICLE
                        </button>
                        <button type="button" wire:click="$set('showNewVehicleForm', false)" class="px-6 py-3 bg-garage-steel/20 text-white hover:bg-garage-steel/30 rounded-lg transition-all font-semibold">
                            CANCEL
                        </button>
                    </div>
                </div>
            @endif

            <!-- Services Section with Grid Layout -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-white mb-4 service-tag flex items-center">
                    <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    SELECT SERVICES
                </label>

                @if (session()->has('services-saved'))
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-init="setTimeout(() => show = false, 5000)"
                         x-transition:enter="transition ease-out duration-400"
                         x-transition:enter-start="opacity-0 transform -translate-x-8 scale-95"
                         x-transition:enter-end="opacity-100 transform translate-x-0 scale-100"
                         x-transition:leave="transition ease-in duration-500"
                         x-transition:leave-start="opacity-100 transform translate-x-0"
                         x-transition:leave-end="opacity-0 transform translate-x-8"
                         class="bg-garage-neon/20 border-l-4 border-garage-neon px-6 py-4 rounded-lg mb-4">
                        <span class="text-white font-semibold">{{ session('services-saved') }}</span>
                    </div>
                @endif
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left: Categories and Services (2/3 width) -->
                    <div class="lg:col-span-2 space-y-4 pb-6">
                        @foreach ($categories as $category)
                            <div class="border-2 border-garage-neon/30 rounded-lg overflow-hidden bg-garage-charcoal/50">
                                <!-- Category Header -->
                                <button type="button" 
                                        wire:click="toggleCategory({{ $category->id }})"
                                        class="w-full p-5 bg-gradient-to-r from-garage-forest to-garage-darkgreen hover:from-garage-neon/10 hover:to-garage-forest transition-all flex items-center justify-between border-b-2 border-garage-neon/20">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3 text-white">
                                            {{ in_array($category->id, $expandedCategories) ? '▼' : '▶' }}
                                        </span>
                                        <div class="text-left">
                                            <div class="flex items-center gap-3">
                                                <span class="font-bold text-white text-lg service-tag">{{ strtoupper($category->name) }}</span>
                                                @if ($categoryCounts[$category->id] > 0)
                                                    <span class="inline-flex items-center justify-center min-w-[28px] h-7 px-2 text-sm font-bold text-garage-black bg-garage-neon rounded-full service-tag">
                                                        {{ $categoryCounts[$category->id] }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-white mt-1">{{ $category->services->count() }} services available</div>
                                        </div>
                                    </div>
                                </button>

                                <!-- Services List (Expandable) -->
                                @if (in_array($category->id, $expandedCategories))
                                    <div class="p-4 bg-garage-black/30">
                                        <div class="space-y-3 mb-4">
                                            @foreach ($category->services as $service)
                                                <div wire:click="toggleService({{ $service->id }})"
                                                     class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all
                                                            {{ in_array($service->id, $selectedServices) ? 'border-garage-neon bg-garage-neon/10 shadow-neon-green' : 'border-garage-steel/30 hover:border-garage-neon/50 bg-garage-charcoal/50' }}">
                                                    <div class="flex-shrink-0 mt-1 mr-4">
                                                        <div class="w-6 h-6 rounded border-2 flex items-center justify-center
                                                                    {{ in_array($service->id, $selectedServices) ? 'bg-garage-neon border-garage-neon shadow-neon-green' : 'border-garage-steel/50' }}">
                                                            @if (in_array($service->id, $selectedServices))
                                                                <svg class="w-5 h-5 text-garage-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="font-bold text-white">{{ $service->name }}</div>
                                                        <div class="text-sm text-white mt-1">{{ $service->description }}</div>
                                                        <div class="text-sm text-white mt-2 flex items-center">
                                                            <svg class="w-4 h-4 mr-1 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            ~{{ $service->estimated_duration_minutes }} mins
                                                        </div>
                                                    </div>
                                                    <div class="flex-shrink-0 ml-4 font-bold text-white font-mono text-lg">
                                                        ₱{{ number_format($service->base_price, 2) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <!-- Save Button for Category -->
                                        @if ($categoryCounts[$category->id] > 0)
                                            <div class="pt-3 border-t-2 border-garage-neon/20">
                                                @if (in_array($category->id, $savedCategories))
                                                    <div class="flex items-center justify-center p-3 bg-garage-emerald/20 border-2 border-garage-emerald rounded-lg">
                                                        <svg class="w-5 h-5 text-garage-emerald mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <span class="text-garage-emerald font-bold service-tag">SAVED</span>
                                                    </div>
                                                @else
                                                    <button type="button" 
                                                            wire:click="saveCategoryServices({{ $category->id }})"
                                                            class="w-full py-3 bg-garage-neon hover:bg-garage-emerald text-garage-black font-bold rounded-lg transition-all shadow-neon-green flex items-center justify-center service-tag">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        SAVE {{ strtoupper($category->name) }} SERVICES
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Right: Selected Services Summary (1/3 width) -->
                    <div class="lg:col-span-1">
                        <div class="lg:sticky lg:top-4 flex flex-col border-2 {{ $servicesConfirmed ? 'border-garage-emerald/50 bg-garage-forest/20' : 'border-garage-neon/50 bg-garage-darkgreen/30' }} rounded-lg overflow-hidden transition-all duration-500 lg:max-h-[calc(100vh-8rem)] h-auto lg:h-[420px] shadow-garage z-10">
                            <!-- Fixed Header -->
                            <div class="flex-shrink-0 {{ $servicesConfirmed ? 'bg-garage-emerald/20' : 'bg-garage-neon/20' }} border-b-2 border-garage-neon/30 p-5">
                                <h3 class="font-bold text-xl text-white service-tag flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    WORK ORDER
                                </h3>
                                <p class="text-sm text-white mt-1 ml-8">
                                    {{ count($selectedServices) }} service(s) selected
                                </p>
                            </div>

                            <!-- Scrollable Services List -->
                            <div class="flex-1 overflow-y-auto min-h-0" wire:key="services-list">
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
                                                 class="bg-garage-charcoal/80 rounded-lg p-3 border-2 border-garage-neon/30 relative group hover:border-garage-neon/50 transition-all">
                                                <button type="button" 
                                                        wire:click="removeService({{ $service->id }})"
                                                        class="absolute -top-2 -right-2 w-7 h-7 bg-red-600 hover:bg-red-500 text-white rounded-full flex items-center justify-center transition-all shadow-lg opacity-0 group-hover:opacity-100 font-bold">
                                                    ×
                                                </button>
                                                <div class="pr-4">
                                                    <div class="font-semibold text-white text-sm">{{ $service->name }}</div>
                                                    <div class="text-xs text-white mt-1 flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        ~{{ $service->estimated_duration_minutes }} mins
                                                    </div>
                                                    <div class="font-bold text-white mt-1 font-mono">₱{{ number_format($service->base_price, 2) }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-4">
                                        <div class="text-center py-8 text-white">
                                            <svg class="w-20 h-20 mx-auto mb-3 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            <p class="text-sm font-semibold">No services selected</p>
                                            <p class="text-xs mt-1">Click services to add</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Sticky Footer (Subtotal & Action Button) -->
                            @if (count($selectedServices) > 0)
                                <div class="flex-shrink-0 p-4 bg-garage-charcoal/90 border-t-2 border-garage-neon/30">
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="font-bold text-white service-tag">ESTIMATE:</span>
                                        <span class="text-2xl font-bold text-white font-mono" 
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
                                            class="w-full py-3 {{ $servicesConfirmed ? 'bg-garage-emerald hover:bg-garage-neon' : 'bg-garage-neon hover:bg-garage-emerald' }} text-garage-black font-bold rounded-lg transition-all shadow-neon-green flex items-center justify-center service-tag">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ $servicesConfirmed ? 'UPDATE SERVICES' : 'CONFIRM & CONTINUE' }}
                                    </button>

                                    @if ($servicesConfirmed)
                                        <p class="text-xs text-center text-white mt-2">Services can be modified anytime</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                        @error('selectedServices') 
                            <span class="text-red-400 text-sm mt-2 block">{{ $message }}</span> 
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Date and Time -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8 mt-8">
                <div>
                    <label class="block text-sm font-bold text-white mb-3 service-tag flex items-center">
                        <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        BOOKING DATE
                    </label>
                    <input type="date" wire:model="bookingDate" class="w-full px-4 py-3 bg-garage-charcoal border-2 border-garage-neon/30 text-garage-offwhite rounded-lg focus:ring-2 focus:ring-garage-neon focus:border-garage-neon font-mono">
                    @error('bookingDate') <span class="text-red-400 text-sm mt-2 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-white mb-3 service-tag flex items-center">
                        <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        PREFERRED TIME
                    </label>
                    <select wire:model="bookingTime" class="w-full px-4 py-3 bg-garage-charcoal border-2 border-garage-neon/30 text-garage-offwhite rounded-lg focus:ring-2 focus:ring-garage-neon focus:border-garage-neon font-semibold">
                        <option value="09:00">9:00 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="16:00">4:00 PM</option>
                    </select>
                    @error('bookingTime') <span class="text-red-400 text-sm mt-2 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-white mb-3 service-tag flex items-center">
                    <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    ADDITIONAL NOTES (Optional)
                </label>
                <textarea wire:model="notes" rows="3" class="w-full px-4 py-3 bg-garage-charcoal border-2 border-garage-neon/30 text-garage-offwhite rounded-lg focus:ring-2 focus:ring-garage-neon focus:border-garage-neon placeholder-garage-steel/50" placeholder="Any specific requirements or concerns..."></textarea>
            </div>

            <!-- Estimated Total -->
            <div class="bg-gradient-to-r from-garage-charcoal to-garage-forest p-6 rounded-lg mb-8 border-2 border-garage-neon/40 shadow-neon-green">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-white service-tag">ESTIMATED TOTAL:</span>
                    <span class="text-4xl font-bold text-white font-mono">₱{{ number_format($estimatedTotal, 2) }}</span>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/50 to-transparent my-3"></div>
                <p class="text-sm text-white">* Final price may vary based on parts and additional services required</p>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    wire:loading.attr="disabled"
                    class="w-full bg-garage-neon hover:bg-garage-emerald text-garage-black px-8 py-4 rounded-lg font-bold hover:shadow-neon-green transition-all text-lg service-tag flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" wire:loading.remove wire:target="submitBooking">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <svg class="animate-spin h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" wire:loading wire:target="submitBooking">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="submitBooking">SUBMIT SERVICE REQUEST</span>
                <span wire:loading wire:target="submitBooking">PROCESSING...</span>
            </button>
        </form>
    </div>

    <!-- Error Modal -->
    @if($showErrorModal)
        <div x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full z-[9999] flex items-center justify-center">
            <div x-transition:enter="transition ease-out duration-400 delay-100"
                 x-transition:enter-start="opacity-0 transform scale-75 -translate-y-10"
                 x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-75"
                 class="relative mx-auto p-8 border-2 border-red-500 w-96 shadow-2xl rounded-lg bg-gradient-to-br from-garage-charcoal to-black">
                <div class="text-center">
                    <!-- Error Icon -->
                    <div x-transition:enter="transition ease-out duration-500 delay-200"
                         x-transition:enter-start="opacity-0 transform scale-50 rotate-180"
                         x-transition:enter-end="opacity-100 transform scale-100 rotate-0"
                         class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-500/20 border-2 border-red-500 mb-4">
                        <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    
                    <!-- Error Message -->
                    <h3 x-transition:enter="transition ease-out duration-400 delay-300"
                        x-transition:enter-start="opacity-0 transform translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        class="text-xl font-bold text-red-500 mb-3 service-tag">ATTENTION</h3>
                    <p x-transition:enter="transition ease-out duration-400 delay-400"
                       x-transition:enter-start="opacity-0 transform translate-y-4"
                       x-transition:enter-end="opacity-100 transform translate-y-0"
                       class="text-garage-offwhite mb-6">{{ $errorMessage }}</p>
                    
                    <!-- OK Button -->
                    <button x-transition:enter="transition ease-out duration-400 delay-500"
                            x-transition:enter-start="opacity-0 transform scale-90"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            wire:click="closeErrorModal" 
                            class="w-full px-6 py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition-colors duration-200 service-tag shadow-lg">
                        OK
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Success Modal -->
    @if($showSuccessModal)
        <div x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 backdrop-blur-sm">
            <div x-transition:enter="transition ease-out duration-400 delay-100"
                 x-transition:enter-start="opacity-0 transform scale-75 -translate-y-10"
                 x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-75"
                 class="relative mx-auto p-8 border-2 border-garage-neon w-96 shadow-2xl rounded-lg bg-gradient-to-br from-garage-charcoal to-garage-darkgreen">
                <div class="text-center">
                    <!-- Success Icon -->
                    <div x-transition:enter="transition ease-out duration-500 delay-200"
                         x-transition:enter-start="opacity-0 transform scale-50 rotate-180"
                         x-transition:enter-end="opacity-100 transform scale-100 rotate-0"
                         class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-garage-neon/20 border-2 border-garage-neon mb-4 animate-pulse">
                        <svg class="h-10 w-10 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    
                    <!-- Success Message -->
                    <h3 x-transition:enter="transition ease-out duration-400 delay-300"
                        x-transition:enter-start="opacity-0 transform translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        class="text-2xl font-bold text-garage-neon mb-3 service-tag">SUCCESSFULLY BOOKED!</h3>
                    <p x-transition:enter="transition ease-out duration-400 delay-400"
                       x-transition:enter-start="opacity-0 transform translate-y-4"
                       x-transition:enter-end="opacity-100 transform translate-y-0"
                       class="text-garage-offwhite mb-6 font-medium">Your booking has been submitted successfully. We will confirm your appointment shortly.</p>
                    
                    <!-- OK Button -->
                    <button x-transition:enter="transition ease-out duration-400 delay-500"
                            x-transition:enter-start="opacity-0 transform scale-90"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            wire:click="closeSuccessModal" 
                            class="w-full px-6 py-3 bg-garage-neon text-garage-black font-bold rounded-lg hover:bg-garage-emerald transition-all duration-200 service-tag shadow-neon-green">
                        OK
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
