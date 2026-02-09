<div>
    <!-- Page Header -->
    <div class="bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-4 sm:p-6 md:p-8 border-l-4 border-garage-neon relative overflow-hidden mb-4 sm:mb-6">
        <!-- Carbon Fiber Pattern Overlay -->
        <div class="absolute inset-0 bg-carbon-fiber opacity-50"></div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-2 sm:space-x-4 mb-2 sm:mb-3">
                <!-- User Management Icon -->
                <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-garage-neon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-garage-offwhite service-tag tracking-wider">USER MANAGEMENT</h1>
            </div>
            <p class="text-garage-steel text-sm sm:text-base md:text-lg ml-0 sm:ml-16">Manage system users and their roles</p>
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
                <input wire:model.live="search" type="text" placeholder="Search users..." 
                    class="flex-1 rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite placeholder-garage-steel shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm">
                <select wire:model.live="roleFilter" class="rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm sm:w-auto">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</option>
                    @endforeach
                </select>
            </div>
            <button wire:click="createUser" class="bg-gradient-to-r from-garage-neon to-garage-forest hover:from-garage-neon/80 hover:to-garage-forest/80 text-garage-charcoal font-bold px-4 sm:px-6 py-2 rounded-lg service-tag transition-all shadow-neon-green text-sm sm:text-base w-full sm:w-auto">
                + ADD USER
            </button>
        </div>
        
        <!-- Garage Floor Divider -->
        <div class="h-px bg-gradient-to-r from-transparent via-garage-neon/30 to-transparent mb-4 sm:mb-6"></div>

        <!-- Users Table - Desktop View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-garage-neon/20">
                <thead class="bg-garage-charcoal/70">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-garage-neon uppercase service-tag tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-garage-charcoal/30 divide-y divide-garage-neon/10">
                    @foreach($users as $user)
                        <tr class="hover:bg-garage-forest/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-garage-offwhite">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-garage-steel">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-garage-steel">{{ $user->phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs rounded-full bg-garage-neon/20 text-garage-neon border border-garage-neon/30 font-semibold">
                                    {{ ucfirst(str_replace('_', ' ', $user->role->name)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button wire:click="editUser({{ $user->id }})" class="text-garage-neon hover:text-white mr-3 font-semibold transition-colors">Edit</button>
                                <button wire:click="deleteUser({{ $user->id }})" 
                                    onclick="return confirm('Are you sure?')" 
                                    class="text-red-400 hover:text-red-300 font-semibold transition-colors">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Users Cards - Mobile/Tablet View -->
        <div class="lg:hidden space-y-3 sm:space-y-4">
            @foreach($users as $user)
                <div class="bg-garage-charcoal/50 border border-garage-neon/20 rounded-lg p-4 hover:bg-garage-forest/30 transition-colors">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base sm:text-lg font-bold text-garage-offwhite truncate">{{ $user->name }}</h3>
                            <p class="text-xs sm:text-sm text-garage-steel break-all">{{ $user->email }}</p>
                        </div>
                        <span class="ml-2 px-2 sm:px-3 py-1 text-xs rounded-full bg-garage-neon/20 text-garage-neon border border-garage-neon/30 font-semibold whitespace-nowrap flex-shrink-0">
                            {{ ucfirst(str_replace('_', ' ', $user->role->name)) }}
                        </span>
                    </div>
                    
                    <div class="mb-3 space-y-1">
                        <div class="flex items-center text-xs sm:text-sm text-garage-steel">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="truncate">{{ $user->phone }}</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 pt-3 border-t border-garage-neon/10">
                        <button wire:click="editUser({{ $user->id }})" 
                                class="flex-1 bg-garage-neon/20 hover:bg-garage-neon/30 text-garage-neon font-semibold px-3 py-2 rounded-lg transition-colors text-xs sm:text-sm">
                            Edit
                        </button>
                        <button wire:click="deleteUser({{ $user->id }})" 
                                onclick="return confirm('Are you sure?')" 
                                class="flex-1 bg-red-500/20 hover:bg-red-500/30 text-red-400 hover:text-red-300 font-semibold px-3 py-2 rounded-lg transition-colors text-xs sm:text-sm">
                            Delete
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-garage-charcoal/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50 px-4">
            <div class="relative top-10 sm:top-20 mx-auto p-4 sm:p-6 border-2 border-garage-neon w-full max-w-md shadow-neon-green rounded-lg bg-gradient-to-br from-garage-charcoal to-garage-darkgreen">
                <h3 class="text-lg sm:text-xl font-bold text-garage-offwhite mb-4 service-tag">{{ $userId ? 'EDIT USER' : 'CREATE USER' }}</h3>
                
                <div class="space-y-3 sm:space-y-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-garage-neon mb-1">Name</label>
                        <input wire:model="name" type="text" class="mt-1 block w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm">
                        @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-garage-neon mb-1">Email</label>
                        <input wire:model="email" type="email" class="mt-1 block w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm">
                        @error('email') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-garage-neon mb-1">Phone</label>
                        <input wire:model="phone" type="text" class="mt-1 block w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm">
                        @error('phone') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-garage-neon mb-1">Role</label>
                        <select wire:model="role_id" class="mt-1 block w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</option>
                            @endforeach
                        </select>
                        @error('role_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-garage-neon mb-1">Password {{ $userId ? '(leave blank to keep current)' : '' }}</label>
                        <input wire:model="password" type="password" class="mt-1 block w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite shadow-sm focus:border-garage-neon focus:ring-garage-neon text-sm">
                        @error('password') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 mt-4 sm:mt-6">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-garage-steel/30 text-garage-offwhite rounded-lg hover:bg-garage-steel/50 transition-colors text-sm">
                        Cancel
                    </button>
                    <button wire:click="saveUser" class="px-4 py-2 bg-gradient-to-r from-garage-neon to-garage-forest text-garage-charcoal font-bold rounded-lg hover:from-garage-neon/80 hover:to-garage-forest/80 service-tag transition-all text-sm">
                        SAVE
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
