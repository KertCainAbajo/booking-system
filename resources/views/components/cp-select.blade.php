{{--
    Dark, searchable dropdown bound to a Livewire property.
    Usage: <x-cp-select model="brandSelection" live :options="['Toyota' => 'Toyota']" placeholder="Select a brand" />
--}}
@props([
    'model',
    'options' => [],
    'placeholder' => 'Select an option',
    'live' => false,
    'searchable' => null,
    'label' => null,
])

@php
    $items = collect($options)->map(fn ($text, $value) => ['value' => (string) $value, 'label' => (string) $text])->values();
    $searchable ??= $items->count() > 8;
    $binding = "\$wire.entangle('{$model}')" . ($live ? '.live' : '');
@endphp

<div
    wire:key="cp-select-{{ $model }}-{{ md5($items->toJson()) }}"
    x-data="{
        open: false,
        search: '',
        active: -1,
        value: {{ $binding }},
        options: @js($items),
        get selected() { return this.options.find(o => o.value === String(this.value ?? '')) },
        get filtered() {
            const q = this.search.trim().toLowerCase();
            return q ? this.options.filter(o => o.label.toLowerCase().includes(q)) : this.options;
        },
        toggle() { this.open ? this.close() : this.show() },
        show() {
            this.open = true;
            this.search = '';
            this.active = Math.max(0, this.filtered.findIndex(o => o.value === String(this.value ?? '')));
            this.$nextTick(() => { this.$refs.search ? this.$refs.search.focus() : this.$refs.list.focus(); this.scrollActive(); });
        },
        close() { this.open = false; this.$refs.button.focus(); },
        choose(option) { this.value = option.value; this.close(); },
        move(step) {
            if (!this.open) return this.show();
            const n = this.filtered.length;
            if (!n) return;
            this.active = (this.active + step + n) % n;
            this.scrollActive();
        },
        pick() { const o = this.filtered[this.active]; if (o) this.choose(o); },
        scrollActive() { this.$nextTick(() => this.$refs.list?.querySelector('[data-active=true]')?.scrollIntoView({ block: 'nearest' })) },
    }"
    x-on:keydown.escape.prevent.stop="open && close()"
    x-on:click.outside="open = false"
    {{ $attributes->class('relative') }}
>
    <button
        type="button"
        x-ref="button"
        x-on:click="toggle()"
        x-on:keydown.arrow-down.prevent="move(1)"
        x-on:keydown.arrow-up.prevent="move(-1)"
        class="cp-input flex items-center justify-between gap-3 text-left"
        :class="open && '!border-emerald-400 ring-4 ring-emerald-400/15'"
        aria-haspopup="listbox"
        :aria-expanded="open.toString()"
        @if ($label) aria-label="{{ $label }}" @endif
    >
        <span class="truncate" :class="selected ? 'text-white' : 'text-gray-500'" x-text="selected ? selected.label : @js($placeholder)">{{ $placeholder }}</span>
        <svg class="w-5 h-5 flex-shrink-0 text-gray-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>

    <div
        x-show="open"
        x-transition.opacity.duration.100ms
        class="absolute z-40 mt-2 w-full overflow-hidden rounded-2xl bg-[#141a17] ring-1 ring-white/10 shadow-2xl shadow-black/60"
        style="display: none;"
    >
        @if ($searchable)
            <div class="p-2 border-b border-white/[0.06]">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                    <input
                        type="text"
                        x-ref="search"
                        x-model="search"
                        x-on:input="active = 0"
                        x-on:keydown.arrow-down.prevent="move(1)"
                        x-on:keydown.arrow-up.prevent="move(-1)"
                        x-on:keydown.enter.prevent="pick()"
                        placeholder="Type to search..."
                        class="w-full h-11 pl-10 pr-3 rounded-xl bg-white/[0.05] border border-white/10 text-white placeholder:text-gray-500 focus:outline-none focus:border-emerald-400 focus:ring-0"
                    >
                </div>
            </div>
        @endif

        <ul
            x-ref="list"
            tabindex="-1"
            role="listbox"
            x-on:keydown.arrow-down.prevent="move(1)"
            x-on:keydown.arrow-up.prevent="move(-1)"
            x-on:keydown.enter.prevent="pick()"
            class="max-h-72 overflow-y-auto p-1.5 focus:outline-none"
        >
            <template x-for="(option, index) in filtered" :key="option.value">
                <li
                    role="option"
                    :aria-selected="(option.value === String(value ?? '')).toString()"
                    :data-active="(index === active).toString()"
                    x-on:click="choose(option)"
                    x-on:mouseenter="active = index"
                    class="flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl cursor-pointer text-base transition-colors"
                    :class="index === active ? 'bg-emerald-400/15 text-white' : 'text-gray-200'"
                >
                    <span class="truncate" x-text="option.label"></span>
                    <svg x-show="option.value === String(value ?? '')" class="w-5 h-5 flex-shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </li>
            </template>
            <li x-show="filtered.length === 0" class="px-3 py-6 text-center text-gray-500">No matches found</li>
        </ul>
    </div>
</div>
