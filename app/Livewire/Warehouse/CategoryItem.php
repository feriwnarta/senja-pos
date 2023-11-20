<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;

class CategoryItem extends Component
{

    public function rendered()
    {
        $this->dispatch('set-width-title');
        $this->dispatch('update-menu');
    }

    public function render()
    {
        return view('livewire.warehouse.category-item');
    }
}
