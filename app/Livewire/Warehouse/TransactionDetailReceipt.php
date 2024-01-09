<?php

namespace App\Livewire\Warehouse;

use App\Models\RequestStock;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class TransactionDetailReceipt extends Component
{
    #[Url(as: 'stckReqId', history: true)]
    public string $reqId = '';

    public string $error = '';

    public RequestStock $requestStock;


    public function render()
    {
        return view('livewire.warehouse.transaction-detail-receipt');
    }

    public function mount()
    {
        $urlCondition = $this->urlIsEmpty($this->reqId);

        // jika url condition bernilai true berarti url benar benar kosong dan tidak mengandung id
        if ($urlCondition) {
            return;
        }

        $requestStock = $this->getRequestStockById($this->reqId);
        $this->requestStock = $requestStock;
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

    public function getRequestStockById(string $requestId)
    {

        try {
            // dapatkan data request stock berdasarkan request id
            return RequestStock::findOrFail($requestId);

        } catch (Exception $exception) {
            Log::error("gagal mendapatkan data request stock di {$exception->getFile()}");
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

    }

}
