<?php

namespace App\Livewire\PointOfSalesKasir;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.pos')] 
class SideMenuOrder extends Component
{
    public $menuSaved;

    public function mount($dataMenu)
    {
        $this->menuSaved = $dataMenu;
    }

    public function render()
    {
        return view('livewire.point-of-sales-kasir.side-menu-order');
    }
}
