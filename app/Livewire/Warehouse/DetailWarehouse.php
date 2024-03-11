<?php

namespace App\Livewire\Warehouse;

use App\Models\Warehouse;
use Livewire\Attributes\On;
use Livewire\Component;

class DetailWarehouse extends Component
{
    public Warehouse $warehouse;

    public function mount(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
    }

    public function render()
    {
        return view('livewire.warehouse.detail-warehouse');
    }
}
