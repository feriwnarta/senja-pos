<?php

namespace App\Livewire\Purchase;

use App\Jobs\Purchase\PurchaseShipped;
use App\Service\Impl\PurchaseServiceImpl;
use App\Service\PurchaseService;
use App\Traits\Jobs;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class PurchasedDetail extends Component
{

    use Jobs;

    #[Url(as: 'pId', keep: true)]
    public string $purchaseId;
    #[Url(as: 'status', keep: true)]
    public string $purchaseStatus;
    public $readyToLoad = false;
    private PurchaseService $purchaseService;

    public function loadPosts()
    {
        $this->readyToLoad = true;
    }


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

        $history = [
            'desc' => 'Purchase dikirim oleh supplier, bersiap untuk melakukan penerimaan',
            'status' => 'Dikirim'
        ];

        $response = $this->ajaxDispatch(new PurchaseShipped($this->purchaseId, $history));
        Log::info($response);

        if ($response['success']) {
            notify()->success('Berhasil membuat pembelian');
            $this->js("alert('Berhasil membuat pembelian')");
        } else {
            notify()->error('Gagal membuat pembelian');
            $this->js("alert('Gagal membuat pembelian')");
        }

    }

    public function render()
    {
        return view('livewire.purchase.purchased-detail');
    }
}
