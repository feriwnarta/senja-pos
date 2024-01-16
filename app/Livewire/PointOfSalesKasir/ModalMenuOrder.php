<?php

namespace App\Livewire\PointOfSalesKasir;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.pos')]
class ModalMenuOrder extends Component
{

    public $count = 1;

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;

        if ($this->count < 0) {
            $this->count++;
        }
    }
    public function render()
    {
        return view('livewire.point-of-sales-kasir.modal-menu-order');
    }
}
