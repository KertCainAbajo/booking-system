<div class="space-y-6">
    <x-success-modal />

    <!-- Page header -->
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-emerald-300">Book a Service</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">Schedule your visit</h1>
            <p class="mt-1 text-gray-400">Five quick steps. Fields marked <span class="text-rose-400">*</span> are required.</p>
        </div>
        <a href="tel:+639270071538" class="cp-btn-ghost">
            <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            Prefer to call?
        </a>
    </div>

    <form wire:submit="submitBooking" class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_22rem] items-start">
        <div class="space-y-6 min-w-0">
            <!-- Step 1: Customer information -->
            <section class="cp-card p-5 sm:p-7">
                <div class="flex items-center gap-3 mb-5">
                    <span class="w-9 h-9 rounded-full bg-emerald-400 text-gray-950 font-bold flex items-center justify-center">1</span>
                    <div>
                        <h2 class="text-lg font-bold text-white">Your details</h2>
                        <p class="text-sm text-gray-400">So we can reach you about this booking.</p>
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="customerName" class="cp-label">Full name <span class="text-rose-400">*</span></label>
                        <input id="customerName" type="text" wire:model="customerName" placeholder="Juan Dela Cruz" class="cp-input" autocomplete="name">
                        @error('customerName') <span class="cp-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="customerPhone" class="cp-label">Phone number <span class="text-rose-400">*</span></label>
                        <input id="customerPhone" type="text" wire:model="customerPhone" placeholder="0917 123 4567" class="cp-input" autocomplete="tel">
                        @error('customerPhone') <span class="cp-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="customerAddress" class="cp-label">Address <span class="text-rose-400">*</span></label>
                        <textarea id="customerAddress" wire:model="customerAddress" rows="2" placeholder="House no., street, barangay, city" class="cp-input"></textarea>
                        @error('customerAddress') <span class="cp-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </section>

            <!-- Step 2: Vehicle -->
            <section class="cp-card p-5 sm:p-7">
                <div class="flex items-center gap-3 mb-5">
                    <span class="w-9 h-9 rounded-full bg-emerald-400 text-gray-950 font-bold flex items-center justify-center">2</span>
                    <div>
                        <h2 class="text-lg font-bold text-white">Your car <span class="text-rose-400">*</span></h2>
                        <p class="text-sm text-gray-400">
                            {{ $vehicles->isEmpty() ? 'Tell us about your car. We\'ll remember it for next time.' : 'Tap the car you\'re bringing in.' }}
                        </p>
                    </div>
                </div>

                @if ($vehicles->isNotEmpty())
                    <div class="grid gap-3 sm:grid-cols-2" role="radiogroup" aria-label="Your cars">
                        @foreach ($vehicles as $vehicle)
                            @php $isChosen = ! $showNewVehicleForm && (string) $selectedVehicle === (string) $vehicle->id; @endphp
                            <button type="button" wire:click="chooseVehicle({{ $vehicle->id }})" role="radio" aria-checked="{{ $isChosen ? 'true' : 'false' }}"
                                    @class([
                                        'flex items-center gap-4 p-4 rounded-2xl text-left ring-1 transition',
                                        'bg-emerald-400/10 ring-emerald-400/60' => $isChosen,
                                        'bg-black/20 ring-white/[0.08] hover:ring-white/25' => ! $isChosen,
                                    ])>
                                <span @class(['w-6 h-6 flex-shrink-0 rounded-full ring-2 flex items-center justify-center', 'ring-emerald-400 bg-emerald-400' => $isChosen, 'ring-white/25' => ! $isChosen])>
                                    @if ($isChosen)
                                        <svg class="w-4 h-4 text-gray-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block font-semibold text-white truncate">{{ $vehicle->make }} {{ $vehicle->model }}</span>
                                    <span class="block text-sm text-gray-400">{{ $vehicle->year }} model</span>
                                </span>
                                <span class="plate-number text-xs flex-shrink-0">{{ $vehicle->plate_number }}</span>
                            </button>
                        @endforeach

                        <button type="button" wire:click="startNewVehicle"
                                @class([
                                    'flex items-center gap-4 p-4 rounded-2xl text-left transition border-2 border-dashed',
                                    'border-emerald-400/60 bg-emerald-400/10' => $showNewVehicleForm,
                                    'border-white/15 hover:border-white/30' => ! $showNewVehicleForm,
                                ])>
                            <span class="w-6 h-6 flex-shrink-0 rounded-full bg-white/10 flex items-center justify-center text-emerald-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            </span>
                            <span class="font-semibold text-gray-200">Add another car</span>
                        </button>
                    </div>
                    @error('selectedVehicle') <span class="cp-error">{{ $message }}</span> @enderror
                @endif

                @if ($showNewVehicleForm)
                    <div @class(['space-y-4', 'mt-4 cp-panel p-5' => $vehicles->isNotEmpty()])>
                        <!-- Car: type to search, or browse brand -> model -->
                        <div wire:key="car-name-field"
                             x-data="{
                                open: false,
                                typing: false,
                                active: 0,
                                brand: null,
                                q: $wire.entangle('carName'),
                                catalog: @js($carCatalog),
                                get all() { return Object.entries(this.catalog).flatMap(([b, ms]) => ms.map(m => b + ' ' + m)) },
                                get mode() { return this.typing && (this.q || '').trim() ? 'search' : (this.brand ? 'models' : 'brands') },
                                get items() {
                                    if (this.mode === 'search') {
                                        const words = this.q.trim().toLowerCase().split(/\s+/);
                                        const has = (s) => words.every(w => s.toLowerCase().includes(w));
                                        const brands = Object.keys(this.catalog).filter(has).map(b => ({ kind: 'brand', label: b, value: b, hint: 'See models' }));
                                        const cars = this.all.filter(has).slice(0, 8).map(s => ({ kind: 'car', label: s, value: s }));
                                        return [...brands, ...cars];
                                    }
                                    if (this.mode === 'models') {
                                        return [
                                            ...this.catalog[this.brand].map(m => ({ kind: 'car', label: m, value: this.brand + ' ' + m })),
                                            { kind: 'other', label: 'Other ' + this.brand + ' model', value: this.brand },
                                        ];
                                    }
                                    return Object.keys(this.catalog).map(b => ({ kind: 'brand', label: b, value: b, hint: this.catalog[b].length + ' models' }));
                                },
                                isCurrent(item) { return item.kind === 'car' && item.value.toLowerCase() === (this.q || '').trim().toLowerCase() },
                                show() {
                                    const current = (this.q || '').trim().toLowerCase();
                                    this.open = true;
                                    this.typing = false;
                                    this.brand = Object.keys(this.catalog).find(b => current.startsWith(b.toLowerCase() + ' ')) || null;
                                    this.active = Math.max(0, this.items.findIndex(i => this.isCurrent(i)));
                                    this.scroll();
                                },
                                choose(item) {
                                    if (item.kind === 'brand') {
                                        this.brand = item.value; this.typing = false; this.active = 0;
                                        this.$refs.input.focus(); this.scroll();
                                        return;
                                    }
                                    if (item.kind === 'other') {
                                        this.q = item.value + ' '; this.typing = true; this.open = false;
                                        this.$nextTick(() => { const el = this.$refs.input; el.focus(); el.setSelectionRange(el.value.length, el.value.length); });
                                        return;
                                    }
                                    this.q = item.value; this.open = false; this.typing = false;
                                },
                                back() { this.brand = null; this.active = 0; this.$refs.input.focus(); this.scroll(); },
                                move(d) {
                                    if (!this.open) return this.show();
                                    const n = this.items.length;
                                    if (!n) return;
                                    this.active = (this.active + d + n) % n;
                                    this.scroll();
                                },
                                scroll() { this.$nextTick(() => this.$refs.list?.querySelector('[data-active=true]')?.scrollIntoView({ block: 'nearest' })) },
                             }"
                             x-on:click.outside="open = false"
                             class="relative">
                            <label for="carName" class="cp-label">Your car <span class="text-rose-400">*</span></label>
                            <div class="relative">
                                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                                <input id="carName" type="text" autocomplete="off" x-ref="input"
                                       x-model="q"
                                       x-on:input="typing = true; open = true; active = 0"
                                       x-on:focus="if (!open) show()"
                                       x-on:click="if (!open) show()"
                                       x-on:keydown.arrow-down.prevent="move(1)"
                                       x-on:keydown.arrow-up.prevent="move(-1)"
                                       x-on:keydown.enter.prevent="if (open && items[active]) choose(items[active])"
                                       x-on:keydown.escape="open = false"
                                       placeholder="Type your car, or pick from the list"
                                       class="cp-input !pl-12 !pr-14"
                                       :class="open && '!border-emerald-400 ring-4 ring-emerald-400/15'">
                                <button type="button" tabindex="-1"
                                        x-on:click="open ? (open = false) : (show(), $refs.input.focus())"
                                        class="absolute right-1.5 top-1/2 -translate-y-1/2 w-10 h-10 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/5"
                                        aria-label="Show car list">
                                    <svg class="w-5 h-5 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </div>

                            <div x-show="open" x-transition.opacity.duration.100ms style="display: none;"
                                 class="absolute z-40 mt-2 w-full overflow-hidden rounded-2xl bg-[#141a17] ring-1 ring-white/10 shadow-2xl shadow-black/60">
                                <div x-show="mode !== 'search'" class="flex items-center justify-between gap-3 px-4 py-2.5 border-b border-white/[0.06] text-sm">
                                    <button type="button" x-show="mode === 'models'" x-on:click="back()" class="inline-flex items-center gap-1 font-medium text-emerald-300 hover:text-emerald-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                        All brands
                                    </button>
                                    <span x-show="mode === 'brands'" class="text-gray-400">Choose your car's brand</span>
                                    <span x-show="mode === 'models'" class="text-gray-400">Choose the <span class="font-semibold text-white" x-text="brand"></span> model</span>
                                </div>

                                <ul x-ref="list" class="max-h-72 overflow-y-auto p-1.5" role="listbox">
                                    <template x-for="(item, i) in items" :key="item.kind + ':' + item.value + ':' + i">
                                        <li role="option" :aria-selected="isCurrent(item).toString()" :data-active="(i === active).toString()"
                                            x-on:click="choose(item)" x-on:mouseenter="active = i"
                                            class="flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl cursor-pointer text-base"
                                            :class="i === active ? 'bg-emerald-400/15 text-white' : 'text-gray-200'">
                                            <span class="truncate" :class="item.kind === 'other' && 'text-emerald-300'" x-text="item.label"></span>
                                            <span class="flex items-center gap-2 flex-shrink-0 text-sm text-gray-500">
                                                <span x-show="item.hint" x-text="item.hint"></span>
                                                <svg x-show="item.kind === 'brand'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                <svg x-show="isCurrent(item)" class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            </span>
                                        </li>
                                    </template>
                                    <li x-show="mode === 'search' && !items.length" class="px-3 py-4 text-sm text-gray-400">
                                        Not on our list? That's fine, we'll save "<span class="text-white" x-text="(q || '').trim()"></span>" as you typed it.
                                    </li>
                                </ul>
                            </div>
                            @error('carName') <span class="cp-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="cp-label">Year <span class="text-rose-400">*</span></label>
                                <x-cp-select
                                    model="newVehicle.year"
                                    label="Year"
                                    placeholder="Select year"
                                    :options="collect($years)->mapWithKeys(fn ($y) => [$y => $y])->all()"
                                />
                                @error('newVehicle.year') <span class="cp-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="plateNumber" class="cp-label">Plate number <span class="text-rose-400">*</span></label>
                                <input id="plateNumber" type="text" wire:model="newVehicle.plate_number" placeholder="e.g. ABC 1234" class="cp-input uppercase placeholder:normal-case">
                                @error('newVehicle.plate_number') <span class="cp-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div x-data="{ showVin: @js(filled($newVehicle['vin_number'] ?? '')) }">
                            <button type="button" x-show="!showVin" x-on:click="showVin = true; $nextTick(() => $refs.vin.focus())" class="text-sm font-medium text-emerald-300 hover:text-emerald-200">
                                + Add VIN (optional)
                            </button>
                            <div x-show="showVin" style="display: none;">
                                <label for="vinNumber" class="cp-label">VIN <span class="text-gray-500">(optional)</span></label>
                                <input id="vinNumber" x-ref="vin" type="text" wire:model="newVehicle.vin_number" placeholder="17-character VIN" class="cp-input">
                                @error('newVehicle.vin_number') <span class="cp-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                            <p class="flex items-center gap-2 text-sm text-gray-400">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                We'll save this car to your account when you submit.
                            </p>
                            @if ($vehicles->isNotEmpty())
                                <button type="button" wire:click="cancelNewVehicle" class="text-sm font-medium text-gray-400 hover:text-white">Cancel</button>
                            @endif
                        </div>
                    </div>
                @endif
            </section>

            <!-- Step 3: Services -->
            <section class="cp-card p-5 sm:p-7">
                <div class="flex items-center gap-3 mb-5">
                    <span class="w-9 h-9 rounded-full bg-emerald-400 text-gray-950 font-bold flex items-center justify-center">3</span>
                    <div>
                        <h2 class="text-lg font-bold text-white">Choose services <span class="text-rose-400">*</span></h2>
                        <p class="text-sm text-gray-400">Open a category and tap the work you need.</p>
                    </div>
                </div>

                @if (session()->has('services-saved'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                         class="mb-4 flex items-center gap-3 rounded-xl bg-emerald-400/10 ring-1 ring-emerald-400/30 p-3.5 text-emerald-200">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="font-medium">{{ session('services-saved') }}</span>
                    </div>
                @endif

                <div class="space-y-3">
                    @foreach ($categories as $category)
                        @php $isOpen = in_array($category->id, $expandedCategories); @endphp
                        <div @class(['rounded-2xl ring-1 overflow-hidden transition', 'ring-emerald-400/30 bg-white/[0.02]' => $isOpen, 'ring-white/[0.07]' => ! $isOpen])>
                            <button type="button" wire:click="toggleCategory({{ $category->id }})" class="w-full flex items-center justify-between gap-3 px-4 sm:px-5 py-4 text-left hover:bg-white/[0.03] transition" aria-expanded="{{ $isOpen ? 'true' : 'false' }}">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-white">{{ $category->name }}</span>
                                        @if ($categoryCounts[$category->id] > 0)
                                            <span class="inline-flex items-center justify-center min-w-[1.5rem] h-6 px-1.5 rounded-full bg-emerald-400 text-gray-950 text-xs font-bold">{{ $categoryCounts[$category->id] }}</span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $category->services->count() }} services</div>
                                </div>
                                <svg @class(['w-5 h-5 text-gray-400 transition-transform flex-shrink-0', 'rotate-180' => $isOpen]) fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            @if ($isOpen)
                                <div class="px-3 sm:px-4 pb-4 space-y-2">
                                    @foreach ($category->services as $service)
                                        @php $isSelected = in_array($service->id, $selectedServices); @endphp
                                        <div wire:click="toggleService({{ $service->id }})" role="checkbox" aria-checked="{{ $isSelected ? 'true' : 'false' }}" tabindex="0"
                                             @keydown.enter.prevent="$wire.toggleService({{ $service->id }})" @keydown.space.prevent="$wire.toggleService({{ $service->id }})"
                                             @class([
                                                 'flex items-start gap-3 sm:gap-4 p-4 rounded-xl cursor-pointer ring-1 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400',
                                                 'bg-emerald-400/10 ring-emerald-400/50' => $isSelected,
                                                 'bg-black/20 ring-white/[0.06] hover:ring-white/20' => ! $isSelected,
                                             ])>
                                            <span @class([
                                                'mt-0.5 w-6 h-6 flex-shrink-0 rounded-md flex items-center justify-center ring-1 transition',
                                                'bg-emerald-400 ring-emerald-400 text-gray-950' => $isSelected,
                                                'ring-white/25' => ! $isSelected,
                                            ])>
                                                @if ($isSelected)
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                @endif
                                            </span>
                                            <div class="flex-1 min-w-0">
                                                <div class="font-semibold text-white">{{ $service->name }}</div>
                                                @if ($service->description)
                                                    <div class="text-sm text-gray-400 mt-0.5">{{ $service->description }}</div>
                                                @endif
                                                <div class="text-xs text-gray-500 mt-1.5">About {{ $service->estimated_duration_minutes }} min</div>
                                            </div>
                                            <div class="font-bold text-white tabular-nums whitespace-nowrap">₱{{ number_format($service->base_price, 2) }}</div>
                                        </div>
                                    @endforeach

                                    @if ($categoryCounts[$category->id] > 0)
                                        <div class="pt-2">
                                            @if (in_array($category->id, $savedCategories))
                                                <div class="flex items-center justify-center gap-2 h-11 rounded-xl bg-emerald-400/10 ring-1 ring-emerald-400/30 text-emerald-300 font-semibold">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    Saved
                                                </div>
                                            @else
                                                <button type="button" wire:click="saveCategoryServices({{ $category->id }})" class="cp-btn-ghost w-full !h-11">
                                                    <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    Save {{ $category->name }} choices
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                @error('selectedServices') <span class="cp-error">{{ $message }}</span> @enderror
            </section>

            <!-- Step 4: Date & time -->
            <section class="cp-card p-5 sm:p-7">
                <div class="flex items-center gap-3 mb-5">
                    <span class="w-9 h-9 rounded-full bg-emerald-400 text-gray-950 font-bold flex items-center justify-center">4</span>
                    <div>
                        <h2 class="text-lg font-bold text-white">Date &amp; time</h2>
                        <p class="text-sm text-gray-400">Open Mon–Sat, 8 AM – 6 PM. Lunch break 12 – 1 PM.</p>
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="bookingDate" class="cp-label">Booking date <span class="text-rose-400">*</span></label>
                        <input id="bookingDate" type="date" wire:model="bookingDate" class="cp-input">
                        @error('bookingDate') <span class="cp-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="bookingTime" class="cp-label">Preferred time <span class="text-rose-400">*</span></label>
                        <input id="bookingTime" type="time" wire:model="bookingTime" class="cp-input">
                        @error('bookingTime') <span class="cp-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="notes" class="cp-label">Notes for the mechanic <span class="text-gray-500">(optional)</span></label>
                        <textarea id="notes" wire:model="notes" rows="3" class="cp-input" placeholder="e.g. Strange noise when braking, aircon not cold"></textarea>
                    </div>
                </div>
            </section>

            <!-- Step 5: Review & submit -->
            <section class="cp-card p-5 sm:p-7">
                <div class="flex items-center gap-3 mb-5">
                    <span class="w-9 h-9 rounded-full bg-emerald-400 text-gray-950 font-bold flex items-center justify-center">5</span>
                    <div>
                        <h2 class="text-lg font-bold text-white">Review &amp; submit</h2>
                        <p class="text-sm text-gray-400">The final price may change if extra parts or work are needed.</p>
                    </div>
                </div>

                <div class="flex items-center justify-between rounded-2xl bg-emerald-400/[0.07] ring-1 ring-emerald-400/20 p-4 sm:p-5 mb-5">
                    <span class="font-medium text-emerald-200">Estimated total</span>
                    <span class="text-3xl font-bold text-white tabular-nums">₱{{ number_format($estimatedTotal, 2) }}</span>
                </div>

                <label for="customer-policy-consent" class="flex items-start gap-3 cp-panel p-4 cursor-pointer">
                    <input id="customer-policy-consent" type="checkbox" wire:model="policyConsent"
                           class="mt-0.5 h-5 w-5 rounded border-white/30 bg-transparent text-emerald-400 focus:ring-emerald-400/40 focus:ring-offset-0">
                    <span class="text-sm text-gray-300">
                        I have read and agree to the
                        <a href="{{ route('privacy.policy') }}" target="_blank" rel="noopener" class="text-emerald-300 underline underline-offset-2">Privacy Policy</a>,
                        <a href="{{ route('terms.conditions') }}" target="_blank" rel="noopener" class="text-emerald-300 underline underline-offset-2">Terms and Conditions</a>, and
                        <a href="{{ route('refund.policy') }}" target="_blank" rel="noopener" class="text-emerald-300 underline underline-offset-2">Refund Policy</a>.
                    </span>
                </label>
                @error('policyConsent') <span class="cp-error">{{ $message }}</span> @enderror

                <button type="submit" wire:loading.attr="disabled" class="cp-btn-primary w-full mt-5 !h-14 text-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="submitBooking"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" wire:loading wire:target="submitBooking">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="submitBooking">Submit booking request</span>
                    <span wire:loading wire:target="submitBooking">Sending...</span>
                </button>
            </section>
        </div>

        <!-- Work order summary -->
        <aside class="lg:sticky lg:top-6">
            <div @class(['cp-card overflow-hidden flex flex-col lg:max-h-[calc(100vh-3rem)]', '!ring-emerald-400/40' => $servicesConfirmed])>
                <div class="p-5 border-b border-white/[0.06]">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-white">Work order</h2>
                        @if ($servicesConfirmed)
                            <span class="cp-badge bg-emerald-400/10 text-emerald-300 ring-emerald-400/30">Confirmed</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-400">{{ count($selectedServices) }} {{ \Illuminate\Support\Str::plural('service', count($selectedServices)) }} selected</p>
                </div>

                <div class="flex-1 overflow-y-auto min-h-0" wire:key="services-list">
                    @if (count($selectedServices) > 0)
                        <ul class="p-3 space-y-2">
                            @foreach ($allSelectedServices as $service)
                                <li wire:key="service-{{ $service->id }}" class="group flex items-start justify-between gap-3 rounded-xl bg-black/20 ring-1 ring-white/[0.06] p-3">
                                    <div class="min-w-0">
                                        <div class="font-medium text-white text-sm">{{ $service->name }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">About {{ $service->estimated_duration_minutes }} min</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-white text-sm tabular-nums">₱{{ number_format($service->base_price, 2) }}</span>
                                        <button type="button" wire:click="removeService({{ $service->id }})" class="w-7 h-7 rounded-lg text-gray-500 hover:text-rose-300 hover:bg-rose-500/10 flex items-center justify-center" aria-label="Remove {{ $service->name }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="px-5 py-10 text-center">
                            <span class="mx-auto w-12 h-12 rounded-xl bg-white/[0.05] flex items-center justify-center text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </span>
                            <p class="mt-3 font-medium text-gray-300">No services yet</p>
                            <p class="text-sm text-gray-500">Pick services in step 3.</p>
                        </div>
                    @endif
                </div>

                @if (count($selectedServices) > 0)
                    <div class="p-5 border-t border-white/[0.06] bg-black/20">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-gray-400">Estimate</span>
                            <span class="text-2xl font-bold text-white tabular-nums">₱{{ number_format($estimatedTotal, 2) }}</span>
                        </div>
                        <button type="button" wire:click="confirmServices" @class(['w-full', 'cp-btn-ghost' => $servicesConfirmed, 'cp-btn-primary' => ! $servicesConfirmed])>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            {{ $servicesConfirmed ? 'Update services' : 'Confirm services' }}
                        </button>
                        @if ($servicesConfirmed)
                            <p class="mt-2 text-xs text-center text-gray-500">You can still change services before submitting.</p>
                        @endif
                    </div>
                @endif
            </div>
        </aside>
    </form>

    <!-- Error Modal -->
    @if($showErrorModal)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
            <div x-data x-transition class="w-full max-w-sm rounded-3xl bg-[#111614] ring-1 ring-rose-500/30 shadow-2xl p-7 text-center">
                <span class="mx-auto w-14 h-14 rounded-2xl bg-rose-500/10 text-rose-400 flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </span>
                <h3 class="mt-4 text-xl font-bold text-white">Please check this</h3>
                <p class="mt-2 text-gray-300">{{ $errorMessage }}</p>
                <button wire:click="closeErrorModal" class="cp-btn-ghost w-full mt-6">OK</button>
            </div>
        </div>
    @endif

    <!-- Success Modal -->
    @if($showSuccessModal)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
            <div class="w-full max-w-sm rounded-3xl bg-[#111614] ring-1 ring-emerald-400/30 shadow-2xl p-7 text-center">
                <span class="mx-auto w-16 h-16 rounded-full bg-emerald-400 text-gray-950 flex items-center justify-center shadow-lg shadow-emerald-900/50">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </span>
                <h3 class="mt-5 text-2xl font-bold text-white">Booking sent!</h3>
                <p class="mt-2 text-gray-300">We received your request and will confirm your appointment shortly.</p>
                <button wire:click="closeSuccessModal" class="cp-btn-primary w-full mt-6">Done</button>
            </div>
        </div>
    @endif
</div>
