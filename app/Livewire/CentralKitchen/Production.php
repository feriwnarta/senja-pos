<?php

namespace App\Livewire\CentralKitchen;

use App\Models\CentralKitchen;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Production extends Component
{

    public Collection $centralKitchens;

    public function boot()
    {
        $this->loadCentralKitchen();
    }

    private function loadCentralKitchen()
    {

        try {

            $this->centralKitchens = CentralKitchen::all();


        } catch (Exception $exception) {
            Log::error($exception);

        }
    }

    public function render()
    {
        return view('livewire.central-kitchen.production');
    }
}
