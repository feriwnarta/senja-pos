<?php

namespace App\Livewire\PointOfSalesKasir\Page;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Point Of Sales")]
#[Layout('components.layouts.pos')] 
class MenuOrder extends Component
{    

    public function render()
    {
        return view('livewire.point-of-sales-kasir.page.menu-order');
    }
}
