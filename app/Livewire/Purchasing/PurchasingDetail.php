<?php

namespace App\Livewire\Purchasing;

use App\Models\Purchase;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class PurchasingDetail extends Component
{

    #[Url(as: 'pId', history: true)]
    public string $purchaseId = '';

    public function render()
    {
        return view('livewire.purchasing.purchasing-detail', ['purchase' => $this->getPurchasingDetail($this->purchaseId)]);
    }

    private function getPurchasingDetail(string $purchaseId)
    {
        try {
            return Purchase::with('reference.purchasable', 'history')->findOrFail($purchaseId);
        } catch (Exception $exception) {
            Log::error("gagal mendapatkan data detail purchasing {$this->id}");
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }
    }
}
