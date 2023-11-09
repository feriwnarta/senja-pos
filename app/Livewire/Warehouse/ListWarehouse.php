<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use Livewire\WithPagination;

class ListWarehouse extends Component
{

    use WithPagination;

    public string $search = '';

    public function rendered($view, $html)
    {
        $this->dispatch('set-width-title');
        $this->dispatch('update-menu');
    }

    public function render()
    {
        return view('livewire.warehouse.list-warehouse');
    }

}
