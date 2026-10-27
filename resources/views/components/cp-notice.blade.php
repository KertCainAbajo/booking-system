@props(['notice' => null])

{{-- Inline success / error notice for a Livewire component's public $notice --}}
@if ($notice)
    @php $isError = ($notice['type'] ?? 'message') === 'error'; @endphp
    <div wire:key="notice-{{ md5($notice['text']) }}" role="{{ $isError ? 'alert' : 'status' }}"
         {{ $attributes->class([
             'flex items-start gap-3 rounded-2xl p-4 ring-1',
             'bg-rose-500/10 ring-rose-500/30 text-rose-100' => $isError,
             'bg-emerald-500/10 ring-emerald-500/30 text-emerald-100' => ! $isError,
         ]) }}>
        <svg class="w-5 h-5 mt-0.5 flex-shrink-0 {{ $isError ? 'text-rose-400' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $isError ? 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}"/>
        </svg>
        <p class="flex-1 font-medium">{{ $notice['text'] }}</p>
        <button type="button" wire:click="$set('notice', null)" class="opacity-70 hover:opacity-100" aria-label="Dismiss">✕</button>
    </div>
@endif
