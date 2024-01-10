<?php

namespace App\Livewire\PointOfSalesKasir;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.pos')] 
class MenuOrderModal extends Component
{    

    public $count = 1;

    public function increment()
    {
        $this->js("alert('test')");
        $this->count++;
    }
 
    public function decrement()
    {
        $this->count--;
    }
    public function render()
    {
        return view('livewire.point-of-sales-kasir.menu-order-modal');
    }
}
