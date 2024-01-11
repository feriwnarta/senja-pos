<?php

namespace App\Livewire\PointOfSalesKasir;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout("components.layout.pos")]
class SelectCustomerModal extends Component
{
    public function render()
    {
        return view('livewire.point-of-sales-kasir.select-customer-modal');
    }
}
