<?php

namespace App\Livewire\Warehouse;

use App\Models\Warehouse;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class Transaction extends Component
{
    public Collection $warehouses;

    public string $toggle = 'request';

    public string $selected = 'all';
    #[Url(keep: true, as: 'option')]
    public string $urlQuery = 'request';
    public string $id = '';
    private string $type = 'outlet';

    public function render()
    {
        return view('livewire.warehouse.transaction');
    }

    public function mount()
    {
        $this->getOutletCentralKitchen();

        if ($this->urlQuery != 'request' && $this->urlQuery != 'stockIn' && $this->urlQuery != 'stockOut') {
            $this->urlQuery = 'request';
        }
    }

    private function getOutletCentralKitchen()
    {
        try {

            $warehouses = Warehouse::all(['id', 'name']);

            // Gabungkan kedua koleksi menjadi satu collection
            $this->warehouses = $warehouses;


        } catch (Exception $exception) {
            Log::error('gagal mengambil data outlet dan central kitchen di dropdown transaksi');
            Log::error($exception->getTraceAsString());
            Log::error($exception->getMessage());
        }
    }

    public function boot()
    {
        $this->selectWarehouse();
    }

    public function selectWarehouse()
    {
        try {
            $warehouse = Warehouse::findOrFail($this->selected);


            if (!$warehouse->outlet->isEmpty()) {
                $this->id = $warehouse->id;
                return;
            }

            if (!$warehouse->centralKitchen->isEmpty()) {
                $this->id = $warehouse->id;
                $this->type = 'centralKitchen';
                return;
            }


            // TODO: validasi error id tidak ketemu di ck atau outlet
        } catch (Exception $exception) {
            Log::error($exception->getTraceAsString());
            Log::error($exception->getMessage());
        }
    }

    public function create()
    {

        // TODO: jika id kosong maka buat pesan error
        if ($this->id == '') {
            return;
        }

        Log::debug($this->type);

        // jika toggle berupa request
        if ($this->urlQuery == 'request') {
            $this->redirect("/warehouse/transaction/add-transaction?option=request&type={$this->type}&id={$this->id}", true);
        }

    }

    public function toggleChange()
    {
        $this->urlQuery = $this->toggle;
    }
}
