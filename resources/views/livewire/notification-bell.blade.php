@php
    // Colour and icon for each kind of update
    $style = fn ($event) => match ($event) {
        'approved', 'completed' => ['bg-emerald-500/15 text-emerald-300', 'M5 13l4 4L19 7'],
        'cancelled', 'customer_cancelled', 'not_available', 'no_show' => ['bg-rose-500/15 text-rose-300', 'M6 18L18 6M6 6l12 12'],
        'running_late', 'reminder' => ['bg-amber-500/15 text-amber-300', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        'mechanic' => ['bg-sky-500/15 text-sky-300', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        default => ['bg-white/10 text-gray-200', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
    };
@endphp

<div wire:poll.30s.visible
     x-data="{
        open: false,
        push: window.dexterPush?.state() ?? 'unsupported',
        busy: false,
        promptDismissed: (() => { try { return localStorage.getItem('dexter-push-prompt') === 'no' } catch (e) { return false } })(),
        async turnOn() {
            this.busy = true;
            this.push = await window.dexterPush.enable();
            this.busy = false;
        },
        async turnOff() {
            this.busy = true;
            this.push = await window.dexterPush.disable();
            this.busy = false;
        },
        notNow() {
            this.promptDismissed = true;
            try { localStorage.setItem('dexter-push-prompt', 'no') } catch (e) {}
        },
     }"
     @keydown.escape.window="open = false"
     class="relative">

    {{-- Bell button --}}
    <button type="button" @click="open = !open" :aria-expanded="open.toString()"
            class="relative w-10 h-10 lg:w-12 lg:h-12 rounded-full flex items-center justify-center bg-[#0c100f]/90 backdrop-blur ring-1 ring-white/10 text-gray-200 hover:text-white hover:ring-emerald-400/40 transition"
            aria-label="Notifications{{ $unread ? " ($unread new)" : '' }}">
        <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if ($unread)
            <span class="absolute -top-1 -right-1 min-w-[1.35rem] h-[1.35rem] px-1 rounded-full bg-rose-500 text-white text-xs font-bold flex items-center justify-center ring-2 ring-[#090c0b]">
                {{ $unread > 9 ? '9+' : $unread }}
            </span>
        @endif
    </button>

    {{-- Panel --}}
    <div x-show="open" x-transition.origin.top.right @click.outside="open = false" style="display: none;"
         class="absolute right-0 mt-3 w-[calc(100vw-2rem)] max-w-sm rounded-2xl bg-[#0f1412] ring-1 ring-white/10 shadow-2xl shadow-black/60 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-white/[0.06]">
            <h2 class="text-lg font-bold text-white">Notifications</h2>
            @if ($unread)
                <button type="button" wire:click="markAllRead" class="text-sm font-semibold text-emerald-300 hover:text-emerald-200">Mark all as read</button>
            @endif
        </div>

        {{-- Device alerts switch --}}
        <div class="px-5 py-3 border-b border-white/[0.06] bg-white/[0.02]">
            <template x-if="push === 'on'">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm text-emerald-200">✓ Alerts are on for this device</p>
                    <button type="button" @click="turnOff()" :disabled="busy" class="text-sm text-gray-400 hover:text-white">Turn off</button>
                </div>
            </template>
            <template x-if="push === 'off'">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm text-gray-300">Get alerts on this phone or computer, even when this site is closed.</p>
                    <button type="button" @click="turnOn()" :disabled="busy" class="shrink-0 px-3 py-1.5 rounded-lg bg-emerald-400 text-gray-950 text-sm font-bold hover:bg-emerald-300">Turn on</button>
                </div>
            </template>
            <template x-if="push === 'blocked'">
                <p class="text-sm text-amber-200">Alerts are blocked in this browser. Allow notifications for this site in your browser settings, then reload.</p>
            </template>
            <template x-if="push === 'ios-install'">
                <p class="text-sm text-gray-300">On iPhone or iPad: tap <b>Share</b> → <b>Add to Home Screen</b>, open the app from there, then turn on alerts here.</p>
            </template>
            <template x-if="push === 'unsupported'">
                <p class="text-sm text-gray-400">This browser can't show alerts. You'll still get emails.</p>
            </template>
        </div>

        <ul class="max-h-[60vh] overflow-y-auto divide-y divide-white/[0.05]">
            @forelse ($notifications as $n)
                @php [$tone, $icon] = $style($n->data['event'] ?? ''); @endphp
                <li wire:key="n-{{ $n->id }}">
                    <button type="button" wire:click="open('{{ $n->id }}')"
                            class="w-full text-left flex gap-3 px-5 py-4 hover:bg-white/[0.04] transition {{ $n->read_at ? 'opacity-70' : '' }}">
                        <span class="w-10 h-10 shrink-0 rounded-xl flex items-center justify-center {{ $tone }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                        </span>
                        <span class="flex-1 min-w-0">
                            <span class="flex items-center gap-2">
                                <span class="font-semibold text-white">{{ $n->data['title'] ?? 'Update' }}</span>
                                @unless ($n->read_at)<span class="w-2 h-2 rounded-full bg-emerald-400" aria-label="new"></span>@endunless
                            </span>
                            <span class="block text-[15px] leading-snug text-gray-300 mt-0.5">{{ $n->data['body'] ?? '' }}</span>
                            <span class="block text-xs text-gray-500 mt-1">{{ $n->created_at->diffForHumans() }}</span>
                        </span>
                    </button>
                </li>
            @empty
                <li class="px-5 py-10 text-center text-gray-400">No notifications yet. We'll tell you here when your booking changes.</li>
            @endforelse
        </ul>
    </div>

    {{-- One-time invitation to turn on alerts, shown until they choose --}}
    <div x-show="push === 'off' && !promptDismissed && !open" x-transition style="display: none;"
         class="fixed bottom-4 right-4 left-4 sm:left-auto sm:w-96 rounded-2xl bg-[#0f1412] ring-1 ring-emerald-400/30 shadow-2xl shadow-black/60 p-5">
        <p class="font-bold text-white text-lg">Get booking alerts on this device?</p>
        <p class="text-gray-300 mt-1">We'll tell you right away when your booking is confirmed, changed or ready — even when this site is closed.</p>
        <div class="mt-4 flex gap-3">
            <button type="button" @click="turnOn()" :disabled="busy" class="cp-btn-primary !h-11 flex-1">Turn on alerts</button>
            <button type="button" @click="notNow()" class="cp-btn-ghost !h-11">Not now</button>
        </div>
    </div>
</div>
