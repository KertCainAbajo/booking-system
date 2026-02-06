<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.staff')]
class InvoiceGenerator extends Component
{
    public function render()
    {
        return view('livewire.staff.invoice-generator');
    }
}
