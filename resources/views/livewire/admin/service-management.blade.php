<div>
    <!-- Page Header -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 md:p-8 border-l-4 border-garage-neon relative overflow-hidden mb-4 sm:mb-6">
        <!-- Carbon Fiber Pattern Overlay -->
        <div class="absolute inset-0 bg-carbon-fiber opacity-50"></div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-2 sm:space-x-4 mb-2 sm:mb-3">
                <!-- Service Icon -->
                <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-garage-offwhite service-tag tracking-wider">SERVICE MANAGEMENT</h1>
            </div>
            <p class="text-garage-steel text-sm sm:text-base md:text-lg ml-0 sm:ml-16">Manage services and pricing</p>
        </div>
        
        <!-- Garage Floor Marking -->
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-white to-transparent opacity-30"></div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-gradient-to-r from-garage-neon/20 to-garage-forest border border-garage-neon text-garage-offwhite px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 border border-garage-neon/20">
        <!-- Search and Filter -->
        <div class="mb-4 sm:mb-6 flex flex-col gap-3 sm:gap-4">
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-2">
                <input wire:model.live="search" type="text" placeholder="Search services..." 
                    class="flex-1 rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite placeholder-garage-steel shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm">
                <select wire:model.live="categoryFilter" class="rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm sm:w-auto">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <button wire:click="createService" class="bg-gradient-to-r from-garage-neon to-garage-forest hover:from-garage-neon/80 hover:to-garage-forest/80 text-garage-charcoal font-bold px-4 sm:px-6 py-2 rounded-lg service-tag transition-all shadow-neon-green text-sm sm:text-base w-full sm:w-auto">
                + ADD SERVICE
            </button>
        </div>
        
        <!-- Garage Floor Divider -->
        <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4 sm:mb-6"></div>

        <!-- Services Table - Desktop View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-garage-neon/20">
                <thead class="bg-garage-charcoal/70">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-garage-charcoal/30 divide-y divide-garage-neon/10">
                    @foreach($services as $service)
                        <tr class="hover:bg-garage-forest/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-garage-offwhite">{{ $service->name }}</div>
                                <div class="text-sm text-garage-steel">{{ Str::limit($service->description, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-garage-offwhite">{{ $service->category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-white font-bold font-mono">₱{{ number_format($service->base_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-garage-steel">{{ $service->estimated_duration_minutes }} min</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleActive({{ $service->id }})" 
                                    class="px-3 py-1 text-xs rounded-full border font-semibold transition-all {{ $service->is_active ? 'bg-garage-neon/20 text-garage-neon border-garage-neon/30 hover:bg-garage-neon/30' : 'bg-garage-steel/20 text-garage-steel border-garage-steel/30 hover:bg-garage-steel/30' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button wire:click="editService({{ $service->id }})" class="text-garage-neon hover:text-white mr-3 font-semibold transition-colors">Edit</button>
                                <button wire:click="deleteService({{ $service->id }})" 
                                    onclick="return confirm('Are you sure?')" 
                                    class="text-red-400 hover:text-red-300 font-semibold transition-colors">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Services Cards - Mobile/Tablet View -->
        <div class="lg:hidden space-y-3 sm:space-y-4">
            @foreach($services as $service)
                <div class="bg-garage-charcoal/50 border border-garage-neon/20 rounded-lg p-4 hover:bg-garage-forest/30 transition-colors">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0 mr-2">
                            <h3 class="text-base sm:text-lg font-bold text-garage-offwhite">{{ $service->name }}</h3>
                            <p class="text-xs sm:text-sm text-garage-steel line-clamp-2 mt-1">{{ $service->description }}</p>
                        </div>
                        <button wire:click="toggleActive({{ $service->id }})" 
                            class="px-2 sm:px-3 py-1 text-xs rounded-full border font-semibold transition-all whitespace-nowrap flex-shrink-0 {{ $service->is_active ? 'bg-garage-neon/20 text-garage-neon border-garage-neon/30' : 'bg-garage-steel/20 text-garage-steel border-garage-steel/30' }}">
                            {{ $service->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div class="flex items-center text-xs sm:text-sm">
                            <svg class="w-4 h-4 mr-1.5 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span class="text-garage-offwhite truncate">{{ $service->category->name }}</span>
                        </div>
                        <div class="flex items-center text-xs sm:text-sm">
                            <svg class="w-4 h-4 mr-1.5 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-garage-steel">{{ $service->estimated_duration_minutes }} min</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-3 border-t border-garage-neon/10">
                        <div class="text-lg sm:text-xl font-bold text-garage-neon font-mono">₱{{ number_format($service->base_price, 2) }}</div>
                        <div class="flex gap-2">
                            <button wire:click="editService({{ $service->id }})" 
                                    class="bg-garage-neon/20 hover:bg-garage-neon/30 text-garage-neon font-semibold px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg transition-colors text-xs sm:text-sm">
                                Edit
                            </button>
                            <button wire:click="deleteService({{ $service->id }})" 
                                    onclick="return confirm('Are you sure?')" 
                                    class="bg-red-500/20 hover:bg-red-500/30 text-red-400 hover:text-red-300 font-semibold px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg transition-colors text-xs sm:text-sm">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $services->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-garage-charcoal/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50 px-4">
            <div class="relative top-10 sm:top-20 mx-auto p-4 sm:p-6 border-2 border-garage-neon w-full max-w-md shadow-neon-green rounded-lg bg-gradient-to-br from-garage-charcoal to-garage-darkgreen">
                <h3 class="text-lg sm:text-xl font-bold text-garage-offwhite mb-4 service-tag">{{ $serviceId ? 'EDIT SERVICE' : 'CREATE SERVICE' }}</h3>
                
                <div class="space-y-3 sm:space-y-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-garage-neon mb-1">Service Name</label>
                        <input wire:model="name" type="text" class="mt-1 block w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm">
                        @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-garage-neon mb-1">Description</label>
                        <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm"></textarea>
                        @error('description') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-garage-neon mb-1">Category</label>
                        <select wire:model="service_category_id" class="mt-1 block w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('service_category_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-garage-neon mb-1">Base Price (₱)</label>
                        <input wire:model="base_price" type="number" step="0.01" class="mt-1 block w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm">
                        @error('base_price') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-garage-neon mb-1">Duration (minutes)</label>
                        <input wire:model="estimated_duration_minutes" type="number" class="mt-1 block w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm">
                        @error('estimated_duration_minutes') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center">
                        <input wire:model="is_active" type="checkbox" class="rounded border-garage-neon/30 text-garage-neon shadow-sm focus:border-garage-neon focus:ring-garage-neon">
                        <label class="ml-2 text-xs sm:text-sm text-garage-offwhite">Active</label>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 mt-4 sm:mt-6">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-garage-steel/30 text-garage-offwhite rounded-lg hover:bg-garage-steel/50 transition-colors text-sm">
                        Cancel
                    </button>
                    <button wire:click="saveService" class="px-4 py-2 bg-gradient-to-r from-garage-neon to-garage-forest text-garage-charcoal font-bold rounded-lg hover:from-garage-neon/80 hover:to-garage-forest/80 service-tag transition-all text-sm">
                        SAVE
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
