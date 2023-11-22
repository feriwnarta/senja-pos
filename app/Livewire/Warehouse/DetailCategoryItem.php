<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;

class DetailCategoryItem extends Component
{

    public string $mode = 'view';

    public function render()
    {
        return view('livewire.warehouse.detail-category-item');
    }
}
