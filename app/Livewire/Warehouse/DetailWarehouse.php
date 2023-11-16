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

    #[On('view-warehouse')]
    public function viewWarehouse()
    {
        $this->dispatch('set-width-title');
        // buat mode nya menjadi edit
        $this->mode = 'view';
    }


    public function render()
    {
        return view('livewire.warehouse.detail-warehouse');
    }
}
