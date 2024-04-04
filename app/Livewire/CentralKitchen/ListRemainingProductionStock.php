<?php

namespace App\Livewire\CentralKitchen;

use App\Models\CentralKitchen;
use App\Models\CentralKitchenStock;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ListRemainingProductionStock extends Component
{

    public CentralKitchen $centralKitchen;
    public Collection $centralKitchens;
    public string $selectCentralKitchen;

    public function mount(CentralKitchen $centralKitchen)
    {
        $this->centralKitchen = $centralKitchen;
        $this->getListCentralKitchen();
        $this->setInitialSelectedCentral();
    }

    private function getListCentralKitchen()
    {

        try {
            $this->centralKitchens = $this->centralKitchen->all();

        } catch (Exception $exception) {
            Log::error('gagal mendapatkan daftar list central kitchen');
            report(new Exception('gagal mendapatkan daftar list central kitchen di list remaining production'));
        }
    }

    private function setInitialSelectedCentral()
    {
        if ($this->centralKitchens->isNotEmpty()) {
            $central = $this->centralKitchens->first();
            $this->selectCentralKitchen = $central->id;
        }
    }

    public function render()
    {
        return view('livewire.central-kitchen.list-remaining-production-stock', [
            'stocks' => (isset($this->selectCentralKitchen)) ? CentralKitchenStock::where('central_kitchens_id', $this->selectCentralKitchen)->paginate(10) : []
        ]);
    }
}
