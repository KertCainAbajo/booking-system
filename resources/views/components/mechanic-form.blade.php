@props(['booking'])

{{-- Mechanic assignment card; pairs with App\Livewire\Concerns\ManagesMechanic --}}
<section {{ $attributes->class('cp-card p-5 sm:p-6') }}>
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-lg font-bold text-white">Mechanic</h2>
        @if ($booking->mechanic_name)
            <span class="cp-badge bg-sky-400/10 text-sky-300 ring-sky-400/30">Assigned</span>
        @endif
    </div>
    <p class="text-sm text-gray-400">
        @if ($booking->mechanic_name)
            <span class="font-semibold text-white">{{ $booking->mechanic_name }}</span>@if ($booking->mechanic_phone) · {{ $booking->mechanic_phone }}@endif
        @else
            Not assigned yet. The customer sees this name on their tracker.
        @endif
    </p>

    <form wire:submit="saveMechanic" class="mt-4 space-y-3">
        <div>
            <label for="mechanicName" class="cp-label">Name</label>
            <input id="mechanicName" type="text" wire:model="mechanicName" placeholder="e.g. Mang Jun" class="cp-input">
            @error('mechanicName') <span class="cp-error">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="mechanicPhone" class="cp-label">Phone <span class="text-gray-500">(optional)</span></label>
            <input id="mechanicPhone" type="text" wire:model="mechanicPhone" placeholder="e.g. 0917 123 4567" class="cp-input">
            @error('mechanicPhone') <span class="cp-error">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="cp-btn-ghost w-full" wire:loading.attr="disabled" wire:target="saveMechanic">
            <span wire:loading.remove wire:target="saveMechanic">Save mechanic</span>
            <span wire:loading wire:target="saveMechanic">Saving...</span>
        </button>
    </form>
</section>
