<?php

namespace App\Livewire\Purchase;

use App\Models\Purchase;
use App\Models\PurchaseRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class Purchasing extends Component
{

    #[Url(as: 'option', history: true, keep: true)]
    public string $toggle = 'request';

    public function mount()
    {
        $this->handleRequest();
    }

    /**
     * handle toggle request
     * ambil data semua permintaan pembelian
     * @return void
     */
    private function handleRequest()
    {
        try {

            return PurchaseRequest::with('reference.requestable', 'history')
                ->whereHas('history', function ($query) {
                    $query->where('status', 'Permintaan baru');
                })->orderBy('created_at', 'DESC')
                ->paginate(10);


        } catch (Exception $exception) {
            notify()->error('gagal mengambil data pembelian');
            Log::error('gagal mengambil data permintaan pembelian');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

    }

    public function render()
    {
        $result = $this->handleOption();

        return view('livewire.purchase.purchasing', ['purchases' => $result]);
    }

    public function handleOption()
    {
        switch ($this->toggle) {
            case  "request":
                return $this->handleRequest();
        }

    }

    public function detailPurchase($id)
    {
        if ($id == null || $id == '') {
            return;
        }

        $this->redirect("/purchase-request/detail?reqId=$id", true);
    }

    /**
     * handler dari button group
     * fungsi ini akan menangani perubahan table pembelian berdasarkan toggle yang diklik
     * @return void
     */
    public function toggleChange()
    {


    }

    private function getPurchase()
    {
        try {
            // ambil semua purchasing
            return Purchase::with('reference.purchasable', 'history')->paginate(10);
        } catch (Exception $exception) {
            Log::error('dapatkan daftar purchasing');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }
    }

}
