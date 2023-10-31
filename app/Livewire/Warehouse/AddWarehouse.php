<?php

namespace App\Livewire\Warehouse;

use Livewire\Attributes\On;
use Livewire\Component;

class AddWarehouse extends Component
{
    public function render()
    {
        return view('livewire.warehouse.add-warehouse');
    }

    #[On('load-menu')]
    public function loadMenu()
    {
        
    }

}
