<?php

namespace App\Livewire\Purchase;

use App\Service\Impl\PurchaseServiceImpl;
use App\Service\PurchaseService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class PurchasedDetail extends Component
{

    #[Url(as: 'pId', keep: true)]
    public string $purchaseId;
    #[Url(as: 'status', keep: true)]
    public string $purchaseStatus;

    private PurchaseService $purchaseService;


    public function boot()
    {
        if (!isset($this->purchaseId) || $this->purchaseId == '') {
            Log::info('redirect');
            $this->redirect('/purchase?option=purchase', true);
            return;
        }

        $this->purchaseService = app()->make(PurchaseServiceImpl::class);

        // ambil status pembelian, lalu set ke purchase status
        $status = $this->purchaseService->getPurchaseStatus($this->purchaseId);
        $this->purchaseStatus = $status;
    }


    /**
     * lakukan proses kirim barang
     * @return void
     */
    public function purchasedSend()
    {

        $dataHistory = [
            'desc' => 'Purchase dikirim oleh supplier, bersiap untuk melakukan penerimaan',
            'status' => 'Dikirim'
        ];
        // panggil service Purchase yang menjalankan logic pengiriman
        $result = $this->purchaseService->purchaseHasBeenShipped($this->purchaseId, $dataHistory);


        // pengiriman gagal
        if ($result == null) {
            $this->js("alert('gagal')");
            notify()->error('Gagal membuat pengiriman');
            return;
        }

        $this->js("alert('berhasil')");
        notify()->success('Berhasil membuat pengiriman');
    }

    public function render()
    {
        return view('livewire.purchase.purchased-detail');
    }
}
