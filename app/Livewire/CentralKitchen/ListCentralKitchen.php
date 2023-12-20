<?php

namespace App\Livewire\CentralKitchen;

use App\Models\CentralKitchen;
use Livewire\Component;

class ListCentralKitchen extends Component
{
    public function render()
    {
        return view('livewire.central-kitchen.list-central-kitchen', ['centralKitchens' => CentralKitchen::paginate(10)]);
    }
}
