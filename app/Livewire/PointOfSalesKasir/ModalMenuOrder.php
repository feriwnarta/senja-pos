<?php

namespace App\Livewire\PointOfSalesKasir;

use App\Livewire\PointOfSalesKasir\Page\MenuOrder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.pos')]
class ModalMenuOrder extends Component
{
    // Counter Order Quantity
    public $count;

    // public function increment()
    // {
    //     $this->count++;
    // }

    // public function decrement()
    // {
    //     $this->count--;

    //     if ($this->count < 0) {
    //         $this->count++;
    //     }
    // }

    public $menuSelected;
    public function mount($dataMenu)
    {
        $this->menuSelected = $dataMenu;
    }

    public function render()
    {
        return view('livewire.point-of-sales-kasir.modal-menu-order');
    }
}
