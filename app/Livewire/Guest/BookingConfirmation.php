<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;

#[Layout('layouts.app')]
class BookingConfirmation extends Component
{
    public $booking;
    public $bookingReference;

    public function mount($reference)
    {
        $this->bookingReference = $reference;
        $this->booking = Booking::with(['customer', 'vehicle', 'services'])
            ->where('booking_reference', $reference)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.guest.booking-confirmation');
    }
}
