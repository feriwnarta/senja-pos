<?php

namespace App\Livewire\Warehouse;

use Livewire\Attributes\On;
use Livewire\Component;

class DetailWarehouse extends Component
{

    public string $mode = 'view';


    #[On('edit-warehouse')]
    public function editWarehouse()
    {
        // buat mode nya menjadi edit
        $this->mode = 'edit';
    }

    public function render()
    {
        return view('livewire.warehouse.detail-warehouse');
    }
}
