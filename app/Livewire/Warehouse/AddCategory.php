<?php

namespace App\Livewire\Warehouse;

use Livewire\Attributes\On;
use Livewire\Component;

class AddCategory extends Component
{


    public function rendered()
    {
        $this->dispatch('set-width-title');
        $this->dispatch('update-menu');
    }

    public function render()
    {
        return view('livewire.warehouse.add-category');
    }

    #[On('load-item')]
    public function loadItem()
    {
        
    }
}
