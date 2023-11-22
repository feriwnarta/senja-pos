<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;

class CategoryItem extends Component
{

    public string $search = '';

    public function rendered()
    {
        $this->dispatch('set-width-title');
    }

    public function render()
    {
        return view('livewire.warehouse.category-item');
    }
}
