<?php

namespace App\Livewire\Warehouse;

use Livewire\Attributes\On;
use Livewire\Component;

class DetailUnit extends Component
{

    public string $mode = 'view';

    #[On('edit-unit')]
    public function editUnit()
    {

        $this->mode = 'edit';

    }


    #[On('cancel-edit')]
    public function cancelEdit()
    {

        $this->mode = 'view';

    }

    public function rendered()
    {
        $this->dispatch('set-width-title');
    }

    public function render()
    {
        return view('livewire.warehouse.detail-unit');
    }
}
