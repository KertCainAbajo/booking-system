<?php

namespace App\Livewire\BusinessOwner;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.owner')]
class RevenueReport extends Component
{
    public function render()
    {
        return view('livewire.business-owner.revenue-report');
    }
}
