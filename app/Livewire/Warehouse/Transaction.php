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
use Livewire\WithPagination;
use Mockery\Exception;

class Transaction extends Component
{

    use WithPagination;

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

    public function view($requestId, $type)
    {

        if ($type == 'request') {
            $this->type = $type;
            $this->viewRequest($requestId);
        }

    }

    private function viewRequest(string $requestId)
    {
        try {
            $requestStock = RequestStock::findOrFail($requestId);
            $history = $requestStock->requestStockHistory->last()->status;

            if ($history == 'Draft') {
                $this->viewRequestDraft($requestStock, $requestId);
                return;
            }

            $this->redirect("/warehouse/transaction/request-stock/view/{$requestId}");


        } catch (Exception $exception) {
            Log::error(json_encode([
                'message' => 'gagal dapatkan detal permintaan stock',
                'request id' => $requestId
            ]));

            notify()->error('Gagal');
        }
    }

    private function viewRequestDraft(RequestStock $requestStock, string $requestId)
    {

        $warehouseId = $requestStock->warehouse->id;

        // jika request adalah gudang outlet
        $isOutlet = $requestStock->warehouse->outlet->isNotEmpty();
        if ($isOutlet) {
            return;
        }

        // jika request adalah gudang central
        $isCentralKitchen = $requestStock->warehouse->centralKitchen->isNotEmpty();
        if ($isCentralKitchen) {
            $this->type = 'centralKitchen';
            $this->redirect("/warehouse/transaction/add-transaction?option=request&type={$this->type}&id={$warehouseId}&reqId={$requestId}", true);
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
            $data = WarehouseItemReceiptRef::with('receivable')->orderBy('id', 'DESC')->paginate(10);
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
            Log::error("id detail receipt kosong = $id");
            return;
        }

        $this->redirect("/warehouse/transaction/detail-receipt/?refId=$id", true);
    }

    public function detail(string $id)
    {
        $this->redirect("/warehouse/transaction/detail-out?wouId=$id", true);
    }

}
