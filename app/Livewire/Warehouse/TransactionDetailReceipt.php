<?php

namespace App\Livewire\Warehouse;

use App\Models\WarehouseItemReceiptRef;
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
                'receivable.result.targetItem',
            ])
                ->findOrFail($receiptRefId);
        } catch (Exception $exception) {
            Log::error("gagal mendapatkan data request stock di {$exception->getFile()}");
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

    }

}
