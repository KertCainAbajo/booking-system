<?php

namespace App\Livewire\Concerns;

/**
 * Lets a booking screen record which mechanic is working on the car.
 * Mechanics don't have accounts; their name and phone are typed in.
 * Expects the component to have a loaded $booking.
 */
trait ManagesMechanic
{
    public $mechanicName = '';
    public $mechanicPhone = '';

    protected function fillMechanic(): void
    {
        $this->mechanicName = $this->booking->mechanic_name ?? '';
        $this->mechanicPhone = $this->booking->mechanic_phone ?? '';
    }

    public function saveMechanic()
    {
        $validated = $this->validate([
            'mechanicName' => 'nullable|string|max:255',
            'mechanicPhone' => 'nullable|string|max:30',
        ]);

        $this->booking->update([
            'mechanic_name' => trim((string) $validated['mechanicName']) ?: null,
            'mechanic_phone' => trim((string) $validated['mechanicPhone']) ?: null,
        ]);

        $this->loadBooking();
        $this->fillMechanic();
        session()->flash('success', $this->booking->mechanic_name ? 'Mechanic saved.' : 'Mechanic removed.');
    }
}
