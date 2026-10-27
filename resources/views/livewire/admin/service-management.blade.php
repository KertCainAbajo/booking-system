<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-sky-300">Services</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">Service catalog</h1>
            <p class="mt-1 text-gray-400">What customers can book, with prices and time estimates. Hidden services can't be booked.</p>
        </div>
        <button wire:click="createService" class="inline-flex items-center gap-2 h-11 px-5 rounded-xl bg-sky-400 text-gray-950 font-semibold hover:bg-sky-300 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add service
        </button>
    </div>

    <x-cp-notice :notice="$notice" />

    <!-- Category chips -->
    <div class="flex flex-wrap gap-2">
        <button wire:click="$set('categoryFilter', '')" @class(['h-9 px-3.5 rounded-full text-sm font-medium ring-1 transition', 'bg-sky-400 text-gray-950 ring-sky-400' => ! $categoryFilter, 'bg-white/[0.04] text-gray-300 ring-white/10 hover:text-white hover:bg-white/10' => $categoryFilter])>
            All <span class="ml-1 opacity-70">{{ $categories->sum('services_count') }}</span>
        </button>
        @foreach ($categories as $category)
            <button wire:click="$set('categoryFilter', '{{ $category->id }}')" @class(['h-9 px-3.5 rounded-full text-sm font-medium ring-1 transition', 'bg-sky-400 text-gray-950 ring-sky-400' => (string) $categoryFilter === (string) $category->id, 'bg-white/[0.04] text-gray-300 ring-white/10 hover:text-white hover:bg-white/10' => (string) $categoryFilter !== (string) $category->id])>
                {{ $category->name }} <span class="ml-1 opacity-70">{{ $category->services_count }}</span>
            </button>
        @endforeach
    </div>

    <section class="cp-card">
        <div class="p-4 sm:p-5 border-b border-white/[0.06]">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search services" class="cp-input !pl-12">
            </div>
        </div>

        <ul class="divide-y divide-white/[0.05]">
            @forelse ($services as $service)
                <li wire:key="s-{{ $service->id }}" @class(['flex flex-col lg:flex-row lg:items-center gap-3 lg:gap-6 px-4 sm:px-5 py-4', 'opacity-60' => ! $service->is_active])>
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-semibold text-white">{{ $service->name }}</span>
                            <span class="cp-badge bg-white/[0.05] text-gray-300 ring-white/10">{{ $service->category?->name ?? 'No category' }}</span>
                        </div>
                        @if ($service->description)
                            <p class="mt-0.5 text-sm text-gray-400 line-clamp-1">{{ $service->description }}</p>
                        @endif
                    </div>
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm">
                        <span class="w-24 font-semibold text-white tabular-nums">₱{{ number_format($service->base_price, 2) }}</span>
                        <span class="w-20 text-gray-400">{{ $service->estimated_duration_minutes }} min</span>
                        <span class="w-24 text-gray-400">{{ $service->bookings_count }} {{ \Illuminate\Support\Str::plural('booking', $service->bookings_count) }}</span>

                        <!-- Active switch -->
                        <button wire:click="toggleActive({{ $service->id }})" role="switch" aria-checked="{{ $service->is_active ? 'true' : 'false' }}" class="flex items-center gap-2 text-sm" title="{{ $service->is_active ? 'Hide from customers' : 'Show to customers' }}">
                            <span @class(['relative w-10 h-6 rounded-full transition', 'bg-emerald-500' => $service->is_active, 'bg-white/15' => ! $service->is_active])>
                                <span @class(['absolute top-1 w-4 h-4 rounded-full bg-white transition-all', 'left-5' => $service->is_active, 'left-1' => ! $service->is_active])></span>
                            </span>
                            <span class="w-14 text-left {{ $service->is_active ? 'text-emerald-300' : 'text-gray-400' }}">{{ $service->is_active ? 'Active' : 'Hidden' }}</span>
                        </button>

                        <div class="flex gap-2">
                            <button wire:click="editService({{ $service->id }})" class="h-9 px-3.5 rounded-lg text-sm font-semibold bg-white/[0.06] text-white ring-1 ring-white/10 hover:bg-white/10">Edit</button>
                            <button wire:click="deleteService({{ $service->id }})" wire:confirm="Delete {{ $service->name }}? This can't be undone."
                                    @disabled($service->bookings_count > 0)
                                    title="{{ $service->bookings_count > 0 ? 'Already booked, so hide it instead' : 'Delete service' }}"
                                    class="h-9 px-3.5 rounded-lg text-sm font-semibold text-rose-300 ring-1 ring-rose-500/30 hover:bg-rose-500/10 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-transparent">
                                Delete
                            </button>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-5 py-16 text-center">
                    <p class="text-lg font-semibold text-white">No services found</p>
                    <p class="mt-1 text-sm text-gray-400">Try another search or category, or add a new service.</p>
                </li>
            @endforelse
        </ul>

        @if ($services->hasPages())
            <div class="p-4 sm:p-5 border-t border-white/[0.06]">{{ $services->links('livewire.cp-pagination') }}</div>
        @endif
    </section>

    <!-- Add / edit service -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" wire:click.self="$set('showModal', false)">
            <div class="w-full max-w-lg rounded-3xl bg-[#111417] ring-1 ring-white/10 shadow-2xl" @keydown.escape.window="$wire.set('showModal', false)">
                <div class="flex items-center justify-between p-6 pb-2">
                    <h2 class="text-xl font-bold text-white">{{ $serviceId ? 'Edit service' : 'Add service' }}</h2>
                    <button wire:click="$set('showModal', false)" class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5" aria-label="Close">✕</button>
                </div>
                <form wire:submit="saveService" class="p-6 pt-4 space-y-4">
                    <div>
                        <label for="s-name" class="cp-label">Service name</label>
                        <input id="s-name" wire:model="name" type="text" class="cp-input" placeholder="e.g. Change oil" autofocus>
                        @error('name') <span class="cp-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="cp-label">Category</label>
                        <x-cp-select model="service_category_id" label="Category" placeholder="Choose a category" :searchable="false"
                            :options="$categories->pluck('name', 'id')->all()" />
                        @error('service_category_id') <span class="cp-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="s-desc" class="cp-label">Description <span class="text-gray-500">(shown to customers)</span></label>
                        <textarea id="s-desc" wire:model="description" rows="3" class="cp-input"></textarea>
                        @error('description') <span class="cp-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="s-price" class="cp-label">Price (₱)</label>
                            <input id="s-price" wire:model="base_price" type="number" step="0.01" min="0" class="cp-input">
                            @error('base_price') <span class="cp-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="s-mins" class="cp-label">Takes about (minutes)</label>
                            <input id="s-mins" wire:model="estimated_duration_minutes" type="number" min="1" class="cp-input">
                            @error('estimated_duration_minutes') <span class="cp-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <label class="flex items-center gap-3 cp-panel p-3.5 cursor-pointer">
                        <input wire:model="is_active" type="checkbox" class="h-5 w-5 rounded border-white/30 bg-transparent text-sky-400 focus:ring-sky-400/40 focus:ring-offset-0">
                        <span class="text-sm text-gray-200">Customers can book this service</span>
                    </label>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="$set('showModal', false)" class="cp-btn-ghost">Cancel</button>
                        <button type="submit" class="inline-flex items-center justify-center h-12 px-6 rounded-xl font-semibold text-gray-950 bg-sky-400 hover:bg-sky-300" wire:loading.attr="disabled" wire:target="saveService">
                            {{ $serviceId ? 'Save changes' : 'Create service' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
