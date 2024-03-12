<?php

namespace App\Livewire\CentralKitchen;

use App\Models\CentralKitchen;
use Livewire\Component;

class ViewCentralKitchen extends Component
{
    public CentralKitchen $centralKitchen;

    public function mount(CentralKitchen $centralKitchen)
    {
        $this->centralKitchen = $centralKitchen;
    }

    public function render()
    {
        return view('livewire.central-kitchen.view-central-kitchen');
    }
}
