<?php

namespace App\Livewire\Staff;

use App\Models\Booking;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('layouts.staff')]
class InvoiceGenerator extends Component
{
    public $booking;
    
    public function mount($id)
    {
        $this->booking = Booking::with([
            'customer',
            'vehicle',
            'services',
            'payment',
            'assignedStaff'
        ])->findOrFail($id);
    }

    public function downloadPdf()
    {
        $pdf = Pdf::loadView('pdf.invoice', ['booking' => $this->booking]);
        
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'invoice-' . $this->booking->booking_reference . '.pdf');
    }

    public function render()
    {
        return view('livewire.staff.invoice-generator');
    }
}
