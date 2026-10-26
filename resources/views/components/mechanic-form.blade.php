@props(['booking'])

{{-- Mechanic assignment card; pairs with App\Livewire\Concerns\ManagesMechanic --}}
<div {{ $attributes->class('bg-gradient-to-br from-garage-charcoal to-garage-darkgreen rounded-lg shadow-garage p-6 border border-garage-neon/20') }}>
    <h3 class="text-xl font-bold text-garage-neon mb-1 service-tag">MECHANIC</h3>
    <p class="text-sm text-garage-steel mb-4">
        @if ($booking->mechanic_name)
            Currently: <span class="font-semibold text-garage-offwhite">{{ $booking->mechanic_name }}</span>@if ($booking->mechanic_phone) · {{ $booking->mechanic_phone }}@endif
        @else
            No mechanic yet. The customer sees this name on their tracker.
        @endif
    </p>

    <form wire:submit="saveMechanic" class="space-y-3">
        <div>
            <label for="mechanicName" class="block text-sm font-medium text-garage-steel mb-1">Name</label>
            <input id="mechanicName" type="text" wire:model="mechanicName" placeholder="e.g. Mang Jun"
                   class="w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite placeholder-garage-steel/60 focus:border-garage-neon focus:ring-garage-neon">
            @error('mechanicName') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="mechanicPhone" class="block text-sm font-medium text-garage-steel mb-1">Phone <span class="text-garage-steel/70">(optional)</span></label>
            <input id="mechanicPhone" type="text" wire:model="mechanicPhone" placeholder="e.g. 0917 123 4567"
                   class="w-full rounded-lg bg-garage-forest border-garage-neon/30 text-garage-offwhite placeholder-garage-steel/60 focus:border-garage-neon focus:ring-garage-neon">
            @error('mechanicPhone') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="w-full px-4 py-2.5 bg-garage-neon hover:bg-garage-emerald text-garage-black font-bold rounded-lg transition service-tag">
            SAVE MECHANIC
        </button>
    </form>
</div>
