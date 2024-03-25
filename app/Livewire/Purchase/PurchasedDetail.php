<?php

namespace App\Livewire\Purchase;

use App\Jobs\Purchase\PurchaseShipped;
use App\Models\Purchase;
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
    public Purchase $purchase;
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
        $purchase = $this->findPurchaseById($this->purchaseId);

        if (is_null($purchase)) {
            $this->redirect("/purchase?option=purchase", true);
        }

        $this->purchase = $purchase;
    }

    private function findPurchaseById(string $id)
    {
        return Purchase::find($id);
    }


    /**
     * lakukan proses kirim barang
     *
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
            notify()->success('Berhasil mengirim');
            $this->redirect("/purchased/detail?pId={$this->purchaseId}", true);
        } else {
            notify()->error('Berhasil mengirim');
        }

    }

    public function render()
    {
        return view('livewire.purchase.purchased-detail');
    }
}
