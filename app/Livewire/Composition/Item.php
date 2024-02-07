<?php

namespace App\Livewire\Composition;

use App\Models\CentralKitchen;
use App\Models\Outlet;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Item extends Component
{

    public array $outletCentralKitchenDropdown;
    public string $selected = '';
    public bool $notSelected = false;

    public function render()
    {
        return view('livewire.composition.item');
    }

    public function mount()
    {
        $this->getOutletCentralKitchen();
    }

    private function getOutletCentralKitchen()
    {
        try {

            // Ambil semua outlet dengan kolom id dan name
            $outlets = Outlet::all(['id', 'name']);

            // Ambil semua central kitchen dengan kolom id dan name
            $centralKitchens = CentralKitchen::all(['id', 'name']);

            // Gabungkan kedua koleksi menjadi satu collection
            $this->outletCentralKitchenDropdown = $outlets->merge($centralKitchens)->all();


        } catch (Exception $exception) {
            Log::error('gagal mengambil data outlet dan central kitchen di dropdown daftar gudang');
            Log::error($exception->getTraceAsString());
            Log::error($exception->getMessage());
        }
    }

    public function addItem()
    {
        if ($this->selected == '') {
            $this->notSelected = true;
            notify()->warning('Pilih outlet atau central kitchen terlebih dahulu', 'Peringatan');
            return;
        }
        $this->reset('notSelected');
        $this->redirect("/composition/item/create-item/?qId={$this->selected}", true);
    }
}
