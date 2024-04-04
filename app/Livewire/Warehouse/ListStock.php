<?php

namespace App\Livewire\Warehouse;

use App\Models\Warehouse;
use App\Models\WarehouseItem;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ListStock extends Component
{

    public Collection $warehouses;
    public string $selectedWarehouse = '';

    public function render()
    {
        // Ambil stok item berdasarkan warehouse dengan hasil paginate
        $items = WarehouseItem::where('warehouses_id', $this->selectedWarehouse)
            ->paginate(10);


        return view('livewire.warehouse.list-stock', [
            'items' => $items
        ]);

    }

    public function mount()
    {
        // ambil semua warehouse
        $this->warehouses = $this->getAlLWarehouse();

        if ($this->warehouses->isNotEmpty()) {
            // set selected warehouse pertama kali dengan warehouse pertama dari collection hasil get all warehouse
            $this->selectedWarehouse = $this->warehouses->first()->id;
        }


    }

    private function getAlLWarehouse(): Collection
    {
        try {
            return Warehouse::all();
        } catch (Exception $exception) {
            notify()->error('Gagal mengambil data warehouse');
            Log::error('gagal mengambil all warehouse di stok item gudang');
            report($exception);
        }
    }

    public function handleSelectWarehouse()
    {

    }
}
