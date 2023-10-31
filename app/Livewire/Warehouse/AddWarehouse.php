<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;

class AddWarehouse extends Component
{

    public function mount() {
        $this->dispatch('load-add-warehouse-script');
    }

    public function render()
    {
        return view('livewire.warehouse.add-warehouse');
    }


}
