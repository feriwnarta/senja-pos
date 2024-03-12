<?php

namespace App\Livewire\CentralKitchen;

use App\Models\CentralKitchen;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ListCentralKitchen extends Component
{

    public function view(string $id)
    {
        $this->redirect("/central-kitchen/list-central-kitchen/view/$id", true);
    }

    public function render()
    {
        Log::info('render');
        return view('livewire.central-kitchen.list-central-kitchen', ['centralKitchens' => CentralKitchen::paginate(10)]);
    }
}
