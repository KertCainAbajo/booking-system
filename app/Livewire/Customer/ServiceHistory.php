<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.customer')]
class ServiceHistory extends Component
{
    public function render()
    {
        return view('livewire.customer.service-history');
    }
}
