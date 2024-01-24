<?php

namespace App\Livewire\PointOfSalesKasir;

use App\Livewire\PointOfSalesKasir\Page\MenuOrder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.pos')]
class ModalMenuOrder extends Component
{
    // Counter Order Quantity
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

    public $dataMenu = [];

    public function render(MenuOrder $menuOrder)
    {
        $this->dataMenu = $menuOrder->getData();

        return view('livewire.point-of-sales-kasir.modal-menu-order', ['data'=>$this->dataMenu]);
    }
}
