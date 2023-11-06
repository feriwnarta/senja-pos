<?php

namespace App\Livewire\Warehouse;

use App\Models\Warehouse;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ListWarehouse extends Component
{

    public string $search = '';

    public function render()
    {
        return view('livewire.warehouse.list-warehouse');
    }

    #[Computed]
    public function warehouses()
    {
        return Warehouse::all();
    }
}
