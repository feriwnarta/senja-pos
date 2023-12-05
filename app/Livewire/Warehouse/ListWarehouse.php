<?php

namespace App\Livewire\Warehouse;

use App\Models\CentralKitchen;
use App\Models\Outlet;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class ListWarehouse extends Component
{

    use WithPagination;

    public string $search = '';

    public string $selected = '';

    public array $outletCentralKitchenDropdown;

    public function addWarehouse()
    {
        $this->redirect("/warehouse/list-warehouse/add-warehouse?qId={$this->selected}", navigate: true);
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

    public function rendered($view, $html)
    {
        $this->dispatch('set-width-title');

    }

    public function render()
    {
        return view('livewire.warehouse.list-warehouse');
    }

}
