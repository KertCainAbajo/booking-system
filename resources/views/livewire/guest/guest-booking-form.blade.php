<div class="min-h-screen bg-gradient-to-br from-black to-green-900 py-6 sm:py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Garage Header -->
        <div class="text-center mb-6 sm:mb-10">
            <!-- Logo and Brand -->
            <div class="flex flex-col sm:flex-row items-center justify-center mb-3 sm:mb-4 gap-2 sm:gap-4">
                <img src="{{ asset('images/shop.png') }}" alt="Dexter Auto Services" class="h-12 w-12 sm:h-16 sm:w-16 brightness-0 invert">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white">
                    DEXTER AUTO SERVICES
                </h1>
            </div>
            <p class="text-garage-steel text-xs sm:text-sm">Professional Auto Service Platform</p>
        </div>

        <!-- Bay Indicator Lights (Progress Stepper) -->
        <div class="mb-8 sm:mb-12 overflow-x-auto">
            <div class="flex items-center justify-center min-w-max px-2 sm:px-4">
                <div class="flex items-center space-x-1 sm:space-x-3 md:space-x-6">
                    <!-- Step 1: Services -->
                    <div class="flex flex-col items-center">
                        <div class="bay-indicator {{ $currentStep >= 1 ? ($currentStep > 1 ? 'completed' : 'active') : 'inactive' }}">
                            @if($currentStep > 1)
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            @else
                                <span class="text-xs sm:text-sm md:text-base">1</span>
                            @endif
                        </div>
                        <span class="mt-1 sm:mt-2 text-[10px] sm:text-xs font-semibold {{ $currentStep >= 1 ? 'text-garage-neon' : 'text-garage-steel' }} service-tag whitespace-nowrap">SERVICES</span>
                    </div>
                    
                    <!-- Cable connector 1 -->
                    <div class="w-4 sm:w-8 md:w-16 h-1 bay-cable {{ $currentStep > 1 ? 'active' : 'inactive' }}"></div>
                    
                    <!-- Step 2: Vehicle -->
                    <div class="flex flex-col items-center">
                        <div class="bay-indicator {{ $currentStep >= 2 ? ($currentStep > 2 ? 'completed' : 'active') : 'inactive' }}">
                            @if($currentStep > 2)
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            @else
                                <span class="text-xs sm:text-sm md:text-base">2</span>
                            @endif
                        </div>
                        <span class="mt-1 sm:mt-2 text-[10px] sm:text-xs font-semibold {{ $currentStep >= 2 ? 'text-garage-neon' : 'text-garage-steel' }} service-tag whitespace-nowrap">VEHICLE</span>
                    </div>
                    
                    <!-- Cable connector 2 -->
                    <div class="w-4 sm:w-8 md:w-16 h-1 bay-cable {{ $currentStep > 2 ? 'active' : 'inactive' }}"></div>
                    
                    <!-- Step 3: Your Info -->
                    <div class="flex flex-col items-center">
                        <div class="bay-indicator {{ $currentStep >= 3 ? ($currentStep > 3 ? 'completed' : 'active') : 'inactive' }}">
                            @if($currentStep > 3)
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            @else
                                <span class="text-xs sm:text-sm md:text-base">3</span>
                            @endif
                        </div>
                        <span class="mt-1 sm:mt-2 text-[10px] sm:text-xs font-semibold {{ $currentStep >= 3 ? 'text-garage-neon' : 'text-garage-steel' }} service-tag whitespace-nowrap">INFO</span>
                    </div>
                    
                    <!-- Cable connector 3 -->
                    <div class="w-4 sm:w-8 md:w-16 h-1 bay-cable {{ $currentStep > 3 ? 'active' : 'inactive' }}"></div>
                    
                    <!-- Step 4: Confirm -->
                    <div class="flex flex-col items-center">
                        <div class="bay-indicator {{ $currentStep >= 4 ? 'active' : 'inactive' }}">
                            <span class="text-xs sm:text-sm md:text-base">4</span>
                        </div>
                        <span class="mt-1 sm:mt-2 text-[10px] sm:text-xs font-semibold {{ $currentStep >= 4 ? 'text-garage-neon' : 'text-garage-steel' }} service-tag whitespace-nowrap">CONFIRM</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Main Content Card -->
            <div class="lg:col-span-2">
                <div class="service-bay-card carbon-texture p-4 sm:p-6 md:p-8">
                    <!-- Step 1: Select Services -->
                    @if ($currentStep == 1)
                        <h2 class="text-2xl sm:text-3xl font-black text-garage-offwhite service-tag mb-2">SELECT SERVICES</h2>
                        <p class="text-garage-steel text-sm sm:text-base mb-6 sm:mb-8">Choose the maintenance services for your vehicle</p>

                        @foreach ($categories as $category)
                            <div class="mb-4">
                                <button type="button" 
                                        wire:click="toggleCategory({{ $category->id }})"
                                        class="bay-category-header">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-garage-neon mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="service-tag">{{ strtoupper($category->name) }}</span>
                                        @if ($categoryCounts[$category->id] > 0)
                                            <span class="ml-3 px-2 py-1 bg-garage-neon/20 text-garage-neon text-xs rounded-full service-tag">
                                                {{ $categoryCounts[$category->id] }} SELECTED
                                            </span>
                                        @endif
                                    </div>
                                    <svg class="w-5 h-5 transform transition-transform duration-200 {{ in_array($category->id, $expandedCategories) ? 'rotate-180' : '' }} text-garage-neon" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                @if (in_array($category->id, $expandedCategories))
                                    <div class="mt-2 p-4 bg-garage-charcoal/30 rounded-lg">
                                        <div class="space-y-2 mb-4">
                                            @foreach ($category->services as $service)
                                                <label class="bay-service-item">
                                                    <input type="checkbox" 
                                                           wire:click="toggleService({{ $service->id }})"
                                                           {{ in_array($service->id, $selectedServices) ? 'checked' : '' }}
                                                           class="w-5 h-5 rounded bg-garage-charcoal border-garage-neon/50 text-garage-neon focus:ring-garage-neon focus:ring-offset-0">
                                                    <div class="ml-4 flex-1">
                                                        <div class="font-semibold text-garage-offwhite">{{ $service->name }}</div>
                                                        <div class="text-sm text-garage-steel">{{ $service->description }}</div>
                                                    </div>
                                                    <div class="bay-price">₱{{ number_format($service->base_price, 2) }}</div>
                                                </label>
                                            @endforeach
                                        </div>
                                        
                                        <!-- Save Button for Category -->
                                        @if ($categoryCounts[$category->id] > 0)
                                            <div class="pt-3 border-t-2 border-garage-neon/20">
                                                @if (in_array($category->id, $savedCategories))
                                                    <div class="flex items-center justify-center p-3 bg-garage-neon/20 border-2 border-garage-neon rounded-lg">
                                                        <svg class="w-5 h-5 text-garage-neon mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <span class="text-garage-neon font-bold service-tag">SAVED</span>
                                                    </div>
                                                @else
                                                    <button type="button" 
                                                            wire:click="saveCategoryServices({{ $category->id }})"
                                                            class="w-full py-3 bg-garage-neon hover:bg-garage-emerald text-garage-black font-bold rounded-lg transition-all shadow-lg flex items-center justify-center service-tag">
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

                        <div class="mt-8 flex flex-col sm:flex-row justify-between gap-3 sm:gap-4">
                            <button type="button" wire:click="backToHome" class="bay-back text-center sm:text-left">
                                ← BACK TO AUTO SERVICE BOOKING
                            </button>
                            <button type="button" wire:click="nextStep" class="bay-cta text-center sm:text-right">
                                CONTINUE TO VEHICLE INFO →
                            </button>
                        </div>
                    @endif

                    <!-- Step 2: Vehicle Information -->
                    @if ($currentStep == 2)
                        <h2 class="text-3xl font-black text-garage-offwhite service-tag mb-2">VEHICLE INFORMATION</h2>
                        <p class="text-garage-steel mb-8">Tell us about your vehicle</p>

                        <div class="space-y-6 pb-12">
                            <!-- Dropdowns first with extra bottom spacing -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                                <div class="dropdown-container">
                                    <label class="block text-sm font-bold text-garage-steel mb-2 service-tag">MAKE (BRAND) *</label>
                                    <select wire:model="vehicleMake" class="bay-input w-full" size="1">
                                        <option value="">Select a brand</option>
                                        @foreach ($carBrands as $brand)
                                            <option value="{{ $brand }}">{{ $brand }}</option>
                                        @endforeach
                                    </select>
                                    @error('vehicleMake') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div class="dropdown-container">
                                    <label class="block text-sm font-bold text-garage-steel mb-2 service-tag">YEAR *</label>
                                    <select wire:model="vehicleYear" class="bay-input w-full" size="1">
                                        <option value="">Select year</option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                    @error('vehicleYear') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Other fields below -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-garage-steel mb-2 service-tag">MODEL *</label>
                                    <input type="text" wire:model="vehicleModel" placeholder="e.g., Civic, Camry" 
                                           class="bay-input w-full">
                                    @error('vehicleModel') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-garage-steel mb-2 service-tag">PLATE NUMBER *</label>
                                    <input type="text" wire:model="vehiclePlateNumber" placeholder="e.g., ABC-1234" 
                                           class="bay-input w-full uppercase">
                                    @error('vehiclePlateNumber') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-garage-steel mb-2 service-tag">
                                    <svg class="w-4 h-4 inline mr-1 tool-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    VIN CHASSIS NO. (OPTIONAL)
                                </label>
                                <input type="text" wire:model="vehicleVinNumber" placeholder="17-digit Vehicle Identification Number" 
                                       class="chassis-label w-full">
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row justify-between gap-3 sm:gap-4">
                            <button type="button" wire:click="previousStep" class="bay-back text-center sm:text-left">
                                ← BACK
                            </button>
                            <button type="button" wire:click="nextStep" class="bay-cta text-center sm:text-right">
                                CONTINUE TO YOUR INFO →
                            </button>
                        </div>
                    @endif

                    <!-- Step 3: Customer Information -->
                    @if ($currentStep == 3)
                        <h2 class="text-3xl font-black text-garage-offwhite service-tag mb-2">YOUR INFORMATION</h2>
                        <p class="text-garage-steel mb-8">Contact details and appointment schedule</p>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-garage-steel mb-2 service-tag">FULL NAME *</label>
                                <input type="text" wire:model="customerName" placeholder="John Doe" 
                                       class="bay-input w-full">
                                @error('customerName') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-garage-steel mb-2 service-tag">EMAIL *</label>
                                    <input type="email" wire:model="customerEmail" placeholder="john@example.com" 
                                           class="bay-input w-full">
                                    @error('customerEmail') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-garage-steel mb-2 service-tag">PHONE *</label>
                                    <input type="tel" wire:model="customerPhone" placeholder="+1 234 567 8900" 
                                           class="bay-input w-full">
                                    @error('customerPhone') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-garage-steel mb-2 service-tag">ADDRESS (OPTIONAL)</label>
                                <textarea wire:model="customerAddress" placeholder="Your address" rows="2"
                                          class="bay-input w-full"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-garage-steel mb-2 service-tag">PREFERRED DATE *</label>
                                    <input type="date" wire:model="bookingDate" 
                                           class="bay-input w-full">
                                    @error('bookingDate') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-garage-steel mb-2 service-tag">PREFERRED TIME *</label>
                                    <input type="time" wire:model="bookingTime" 
                                           class="bay-input w-full">
                                    @error('bookingTime') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-garage-steel mb-2 service-tag">ADDITIONAL NOTES (OPTIONAL)</label>
                                <textarea wire:model="notes" placeholder="Any specific concerns or requests?" rows="3"
                                          class="bay-input w-full"></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row justify-between gap-3 sm:gap-4">
                            <button type="button" wire:click="previousStep" class="bay-back text-center sm:text-left">
                                ← BACK
                            </button>
                            <button type="button" wire:click="nextStep" class="bay-cta text-center sm:text-right">
                                REVIEW BOOKING →
                            </button>
                        </div>
                    @endif

                    <!-- Step 4: Confirmation -->
                    @if ($currentStep == 4)
                        <h2 class="text-3xl font-black text-garage-offwhite service-tag mb-2">CONFIRM YOUR BOOKING</h2>
                        <p class="text-garage-steel mb-8">Review your service appointment details</p>

                        <div class="space-y-6">
                            <!-- Customer Info -->
                            <div class="border-b border-garage-steel/30 pb-6">
                                <h3 class="font-bold text-lg text-garage-neon mb-3 service-tag">YOUR INFORMATION</h3>
                                <div class="text-garage-offwhite space-y-2">
                                    <p><span class="text-garage-steel">NAME:</span> {{ $customerName }}</p>
                                    <p><span class="text-garage-steel">EMAIL:</span> {{ $customerEmail }}</p>
                                    <p><span class="text-garage-steel">PHONE:</span> {{ $customerPhone }}</p>
                                    @if($customerAddress)
                                        <p><span class="text-garage-steel">ADDRESS:</span> {{ $customerAddress }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Vehicle Info -->
                            <div class="border-b border-garage-steel/30 pb-6">
                                <h3 class="font-bold text-lg text-garage-neon mb-3 service-tag">VEHICLE</h3>
                                <div class="flex items-center p-4 bg-garage-charcoal/50 rounded-lg border border-garage-neon/20">
                                    <svg class="w-10 h-10 text-garage-neon mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17a5 5 0 01-.916-9.916 5.002 5.002 0 019.832 0A5.002 5.002 0 0116 17m-7-5l3-3m0 0l3 3m-3-3v12"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xl font-bold text-garage-offwhite">{{ $vehicleYear }} {{ $vehicleMake }} {{ $vehicleModel }}</p>
                                        <p class="text-garage-steel font-mono">{{ $vehiclePlateNumber }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Details -->
                            <div class="border-b border-garage-steel/30 pb-6">
                                <h3 class="font-bold text-lg text-garage-neon mb-3 service-tag">APPOINTMENT</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-4 bg-garage-charcoal/50 rounded-lg border border-garage-neon/20">
                                        <p class="text-garage-steel text-sm service-tag mb-1">DATE</p>
                                        <p class="text-garage-offwhite font-bold">{{ \Carbon\Carbon::parse($bookingDate)->format('F d, Y') }}</p>
                                    </div>
                                    <div class="p-4 bg-garage-charcoal/50 rounded-lg border border-garage-neon/20">
                                        <p class="text-garage-steel text-sm service-tag mb-1">TIME</p>
                                        <p class="text-garage-offwhite font-bold">{{ \Carbon\Carbon::parse($bookingTime)->format('g:i A') }}</p>
                                    </div>
                                </div>
                                @if($notes)
                                    <div class="mt-4 p-4 bg-garage-charcoal/30 rounded-lg border border-garage-steel/20">
                                        <p class="text-garage-steel text-sm service-tag mb-1">NOTES</p>
                                        <p class="text-garage-offwhite">{{ $notes }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Selected Services -->
                            <div>
                                <h3 class="font-bold text-lg text-garage-neon mb-3 service-tag">SELECTED SERVICES</h3>
                                <div class="space-y-2">
                                    @foreach ($allSelectedServices as $service)
                                        <div class="flex justify-between items-center p-3 bg-garage-charcoal/50 rounded border border-garage-neon/20">
                                            <span class="text-garage-offwhite">{{ $service->name }}</span>
                                            <span class="bay-price">₱{{ number_format($service->base_price, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row justify-between gap-3 sm:gap-4">
                            <button type="button" wire:click="previousStep" class="bay-back text-center sm:text-left">
                                ← BACK
                            </button>
                            <button type="button" wire:click="submitBooking" 
                                    wire:loading.attr="disabled"
                                    class="px-4 py-3 sm:px-8 sm:py-4 rounded-lg font-black transition-all duration-300 bg-garage-neon text-garage-black hover:shadow-neon-green-lg service-tag disabled:opacity-50 disabled:cursor-not-allowed text-xs sm:text-sm md:text-base text-center sm:text-right">
                                <span wire:loading.remove wire:target="submitBooking">CONFIRM BOOKING</span>
                                <span wire:loading wire:target="submitBooking">PROCESSING...</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Summary Sidebar - Service Receipt Style -->
            <div class="lg:col-span-1">
                <div class="service-receipt sticky top-6">
                    <h3 class="font-black text-xl text-garage-offwhite mb-4 service-tag">SERVICE RECEIPT</h3>
                    
                    @if (count($allSelectedServices) > 0)
                        <div class="space-y-3 mb-6">
                            @foreach ($allSelectedServices as $service)
                                <div class="flex justify-between items-start text-sm">
                                    <span class="flex-1 text-garage-offwhite">{{ $service->name }}</span>
                                    <span class="font-bold ml-3 text-white">₱{{ number_format($service->base_price, 2) }}</span>
                                </div>
                                @if(!$loop->last)
                                    <div class="receipt-divider"></div>
                                @endif
                            @endforeach
                        </div>
                        <div class="border-t-2 border-garage-neon/50 pt-4">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-1 sm:gap-2">
                                <span class="text-sm sm:text-base text-garage-steel font-bold service-tag">TOTAL:</span>
                                <span class="bay-total">₱{{ number_format($estimatedTotal, 2) }}</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-garage-steel mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-garage-steel text-sm service-tag">NO SERVICES SELECTED</p>
                        </div>
                    @endif

                    @if ($currentStep > 1 && $vehicleMake)
                        <div class="mt-6 pt-6 border-t border-garage-steel/30">
                            <h4 class="font-bold text-garage-neon mb-2 service-tag text-sm">VEHICLE</h4>
                            <div class="p-3 bg-garage-charcoal/50 rounded border border-garage-neon/20">
                                <p class="text-sm font-bold text-garage-offwhite">{{ $vehicleYear }} {{ $vehicleMake }}</p>
                                <p class="text-sm text-garage-steel">{{ $vehicleModel }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($currentStep > 2 && $bookingDate)
                        <div class="mt-4 pt-4 border-t border-garage-steel/30">
                            <h4 class="font-bold text-garage-neon mb-2 service-tag text-sm">APPOINTMENT</h4>
                            <div class="p-3 bg-garage-charcoal/50 rounded border border-garage-neon/20">
                                <p class="text-sm text-garage-offwhite font-mono">{{ \Carbon\Carbon::parse($bookingDate)->format('M d, Y') }}</p>
                                <p class="text-sm text-garage-steel font-mono">{{ \Carbon\Carbon::parse($bookingTime)->format('g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
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
             class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
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
    <x-success-modal />
</div>
