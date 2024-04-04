<?php

namespace App\Livewire\Purchase;

use App\Models\PurchaseRequest;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;

class PurchaseDetail extends Component
{

    #[Url(as: 'reqId', keep: true)]
    public string $reqId = '';
    public string $currentDateTime = '';

    public function mount()
    {
        if ($this->reqId == '') {
            return;
        }

        $this->currentDateTime = Carbon::now();
    }


    public function render()
    {
        $purchaseRequest = $this->findPurchaseRequestById(id: $this->reqId);

        return view('livewire.purchase.purchase-detail', ['purchaseRequest' => $purchaseRequest]);
    }

    private function findPurchaseRequestById($id)
    {
        return PurchaseRequest::with('reference.requestable', 'history', 'detail.item')->findOrFail($id);
    }
}
