@props(['status'])

@php
    $styles = [
        'pending' => 'bg-amber-400/10 text-amber-300 ring-amber-400/30',
        'approved' => 'bg-sky-400/10 text-sky-300 ring-sky-400/30',
        'completed' => 'bg-emerald-400/10 text-emerald-300 ring-emerald-400/30',
        'cancelled' => 'bg-gray-400/10 text-gray-300 ring-gray-400/30',
        'no_show' => 'bg-orange-400/10 text-orange-300 ring-orange-400/30',
        'not_available' => 'bg-rose-400/10 text-rose-300 ring-rose-400/30',
    ];
@endphp

<span {{ $attributes->class(['cp-badge', $styles[$status] ?? $styles['pending']]) }}>
    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
    {{ ucwords(str_replace('_', ' ', $status)) }}
</span>
