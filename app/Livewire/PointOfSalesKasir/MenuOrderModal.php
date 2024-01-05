<?php

namespace App\Livewire\PointOfSalesKasir;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.pos')] 
class MenuOrderModal extends Component
{
    public function render()
    {
        return view('livewire.point-of-sales-kasir.menu-order-modal');
    }
}
