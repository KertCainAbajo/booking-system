@props(['message' => ''])

{{-- Kept for the pages that already use it: a success message now shows as a pop-up alert (see x-toasts) --}}
@php $text = session('success') ?? $message; @endphp
@if ($text)
    <div wire:key="success-{{ md5($text) }}" x-data x-init="$dispatch('toast', { type: 'success', message: @js($text) })" class="hidden"></div>
@endif
