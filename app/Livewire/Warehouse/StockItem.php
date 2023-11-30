<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;

class StockItem extends Component
{
    public function render()
    {
        return view('livewire.warehouse.stock-item');
    }

    public function rendered($view, $html)
    {
        
        $this->dispatch('update-menu');
    }
}
