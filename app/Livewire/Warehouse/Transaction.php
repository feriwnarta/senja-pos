<?php

namespace App\Livewire\Warehouse;

use App\Models\RequestStock;
use App\Models\Warehouse;
use App\Models\WarehouseItemReceiptRef;
use App\Models\WarehouseOutbound;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class Transaction extends Component
{
    public Collection $warehouses;

    public string $toggle = '';

    public string $selected = 'all';
    #[Url(as: 'option', history: true)]
    public string $urlQuery = 'request';
    public string $id = '';
    public bool $isWarehouseSelected = true;
    private string $type = 'outlet';

    public function mount()
    {
        $this->getOutletCentralKitchen();
        $validOption = ['request', 'stockIn', 'stockOut'];


        if (!in_array($this->urlQuery, $validOption)) {
            $this->urlQuery = 'request';
        }

        $this->toggle = $this->urlQuery;


        $this->getRequestStock();
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

    private function getRequestStock()
    {
        try {

            return RequestStock::paginate(10);

        } catch (Exception $exception) {
            Log::error('gagal mendapatkan request stock di transaksi gudang');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }
    }

    public function boot()
    {
        if ($this->selected != 'all') {
            $this->selectWarehouse();
        }
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
            $this->isWarehouseSelected = false;
            notify()->warning('Harap pilih gudang terlebih dahulu', 'Peringatan');
            return;
        }

        // jika toggle berupa request
        if ($this->urlQuery == 'request') {
            $this->isWarehouseSelected = true;
            $this->redirect("/warehouse/transaction/add-transaction?option=request&type={$this->type}&id={$this->id}", true);
        }

    }

    public function toggleChange()
    {
        $this->urlQuery = $this->toggle;
    }

    public function render()
    {

        $validUrl = ['request', 'stockIn', 'stockOut'];

        if ($this->urlQuery == '' && !in_array($validUrl)) {
            return 'Pastikan url valid';
        }


        if ($this->urlQuery == 'request') {
            $data = RequestStock::when($this->id, function ($query) {
                return $query->where('warehouses_id', $this->id);
            })->orderBy('id', 'DESC')->paginate(10);
        }

        if ($this->urlQuery == 'stockIn') {
            $data = WarehouseItemReceiptRef::with('receivable')->paginate(10);
            $data->load('itemReceipt.history');
        }

        if ($this->urlQuery == 'stockOut') {
            $data = WarehouseOutbound::when($this->id, function ($query) {
                return $query->where('warehouses_id', $this->id);
            })->orderBy('id', 'DESC')->paginate(10);
        }


        return view('livewire.warehouse.transaction', [
            'requestStock' => $data
        ]);


    }

    public function detailReceipt($id)
    {

        if ($id == '') {
            notify()->error('Parameter id tidak boleh kosong');
            return;
        }

        $this->redirect("/warehouse/transaction/detail-receipt/?refId=$id", true);
    }


    public function detail(string $id)
    {
        $this->redirect("/warehouse/transaction/detail-out?wouId=$id", true);
    }
}
