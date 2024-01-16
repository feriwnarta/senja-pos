<?php

namespace App\Livewire\Purchase;

use App\Models\Purchase;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Purchasing extends Component
{
    public function render()
    {
        return view('livewire.purchase.purchasing', ['purchases' => $this->getPurchase()]);
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

    public function detailPurchase($id)
    {
        if ($id == null || $id == '') {
            return;
        }

        $this->redirect("/purchase/detail?pId=$id", true);
    }

}
