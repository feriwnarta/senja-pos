<?php

namespace App\Livewire\Warehouse;

use App\Models\WarehouseItemReceiptRef;
use App\Service\Impl\WarehouseItemReceiptServiceImpl;
use App\Service\WarehouseItemReceiptService;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class TransactionDetailReceipt extends Component
{
    #[Url(as: 'refId', history: true)]
    public string $receiptRefId = '';

    public string $error = '';

    public WarehouseItemReceiptRef $itemReceiptRef;

    public array $dataItemReceipt = [];

    private WarehouseItemReceiptService $itemReceiptService;


    public function render()
    {
        return view('livewire.warehouse.transaction-detail-receipt');
    }

    public function mount()
    {
        $urlCondition = $this->urlIsEmpty($this->receiptRefId);

        // jika url condition bernilai true berarti url benar benar kosong dan tidak mengandung id
        if ($urlCondition) {
            return;
        }

        $itemReceiptRef = $this->getItemReceiptDetail($this->receiptRefId);
        $this->itemReceiptRef = $itemReceiptRef;
    }

    /**
     * deteksi url apakah string kosong
     * @return void
     */
    public function urlIsEmpty($url)
    {

        // jika url kosong berikan pesan error stock req id nya harus terisi
        if ($url == '') {
            $this->error = 'Detail stok masuk tidak valid, harap kembali dan muat ulang';
            return true;
        }

        return false;

    }

    /**
     * dapatkan detail data item receipt dari table warehouse item receipt refs
     * @param string $receiptRefId
     * @return void
     */
    public function getItemReceiptDetail(string $receiptRefId)
    {
        try {
            return WarehouseItemReceiptRef::with([
                'receivable',
                'itemReceipt.history',
                'itemReceipt.warehouse',
                'itemReceipt.details.items',
            ])
                ->findOrFail($receiptRefId);
        } catch (Exception $exception) {
            Log::error("gagal mendapatkan data request stock di {$exception->getFile()}");
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

    }


    /**
     * lakukan proses penerimaan item receipt
     * @param $itemReceiptRefId
     * @return void
     */
    public function acceptItemReceipt($itemReceiptRefId, $warehouseId, $warehouseCode)
    {
        Log::info("Proses penerimaan item receipt $itemReceiptRefId");

        if (!isset($itemReceiptRefId, $warehouseId, $warehouseCode) || $warehouseCode == '') {
            notify()->error('Ada data yang tidak tersedia. Mohon cek kembali.');
            Log::error('salah satu parameter $itemReceiptRefId, $warehouseId, $warehouseCode ada yg kosong atau belum diset');
            return;
        }

        if (empty($this->dataItemReceipt)) {
            notify()->error('Ada data yang tidak tersedia. Mohon cek kembali.');
            Log::error('data item receipt kosong');
            return;
        }

        $this->validate([
            'dataItemReceipt.*.qty_accept' => 'numeric|min:1'
        ]);

        $this->processAcceptReceipt($itemReceiptRefId, $warehouseId, $warehouseCode, $this->dataItemReceipt);

    }

    private function processAcceptReceipt($itemReceiptRefId, $warehouseId, $warehouseCode, $items)
    {
        try {
            $this->itemReceiptService = app()->make(WarehouseItemReceiptServiceImpl::class);
            $result = $this->itemReceiptService->accept($itemReceiptRefId, $warehouseId, $warehouseCode, $items);

            if ($result) {
                notify()->success('Berhasil melakukan penerimaan');
                return;
            }

            notify()->error('Gagal melakukan penerimaan');
            return;
        } catch (Exception $exception) {
            Log::error('Gagal melakukan penerimaan barang di TransactonDetailReceipt');
            Log::error($exception);
            Log::error($exception->getMessage());
            notify()->error('Gagal melakukan penerimaan');
            return;

        }
    }


}
