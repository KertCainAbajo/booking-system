{{--
    Pop-up alerts for the whole site (top-right on desktop, bottom on phones). They close by themselves.

    Show one from PHP:
      - before a redirect:   session()->flash('toast', ['type' => 'success', 'message' => '...'])
                             (session 'success' and 'error' flashes are shown too)
      - on a Livewire page:  $this->dispatch('toast', type: 'success', message: 'Saved.')
    Types: success, error, warning, info.
--}}
@php
    $initial = collect([
        session('toast'),
        session('success') ? ['type' => 'success', 'message' => session('success')] : null,
        is_string(session('message')) ? ['type' => 'success', 'message' => session('message')] : null,
        session('error') ? ['type' => 'error', 'message' => session('error')] : null,
    ])->filter(fn ($t) => is_array($t) && filled($t['message'] ?? null))->values();
@endphp

<div x-data="{
        toasts: [],
        recent: {},
        add(t) {
            if (!t || !t.message) return;
            // The same message can arrive twice (page load + component); show it once
            const key = (t.type || 'success') + '|' + t.message;
            if (this.recent[key] && Date.now() - this.recent[key] < 2500) return;
            this.recent[key] = Date.now();

            const id = Date.now() + Math.random();
            const type = ['success', 'error', 'warning', 'info'].includes(t.type) ? t.type : 'success';
            this.toasts.push({ id, type, title: t.title || null, message: t.message, show: true });
            setTimeout(() => this.dismiss(id), type === 'error' ? 8000 : 5000);
        },
        dismiss(id) {
            const t = this.toasts.find(t => t.id === id);
            if (!t) return;
            t.show = false;
            setTimeout(() => this.toasts = this.toasts.filter(t => t.id !== id), 300);
        },
     }"
     x-init="@js($initial).forEach(t => add(t))"
     @toast.window="add(Array.isArray($event.detail) ? $event.detail[0] : $event.detail)"
     class="fixed z-[60] inset-x-4 bottom-4 sm:inset-x-auto sm:bottom-auto sm:top-20 lg:top-24 sm:right-6 lg:right-8 sm:w-96 flex flex-col gap-3 pointer-events-none"
     aria-live="polite">
    <template x-for="t in toasts" :key="t.id">
        <div x-show="t.show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-3 sm:translate-y-0 sm:translate-x-6"
             x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             :role="t.type === 'error' ? 'alert' : 'status'"
             class="pointer-events-auto flex items-start gap-3 rounded-2xl p-4 pr-3 shadow-2xl shadow-black/50 ring-1 backdrop-blur-xl bg-[#0f1412]/95"
             :class="{
                'ring-emerald-400/40': t.type === 'success',
                'ring-rose-400/50': t.type === 'error',
                'ring-amber-400/50': t.type === 'warning',
                'ring-sky-400/40': t.type === 'info',
             }">
            <span class="w-9 h-9 shrink-0 rounded-xl flex items-center justify-center"
                  :class="{
                    'bg-emerald-500/15 text-emerald-300': t.type === 'success',
                    'bg-rose-500/15 text-rose-300': t.type === 'error',
                    'bg-amber-500/15 text-amber-300': t.type === 'warning',
                    'bg-sky-500/15 text-sky-300': t.type === 'info',
                  }">
                <svg x-show="t.type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <svg x-show="t.type === 'error'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <svg x-show="t.type === 'warning'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <svg x-show="t.type === 'info'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
            <div class="flex-1 min-w-0 pt-0.5">
                <p x-show="t.title" x-text="t.title" class="font-bold text-white"></p>
                <p x-text="t.message" class="text-[15px] leading-snug text-gray-200"></p>
            </div>
            <button type="button" @click="dismiss(t.id)" class="p-1.5 -mt-0.5 rounded-lg text-gray-400 hover:text-white hover:bg-white/10" aria-label="Close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </template>
</div>
