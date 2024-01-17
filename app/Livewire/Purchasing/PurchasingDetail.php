<?php

namespace App\Livewire\Purchasing;

use App\Models\Purchase;
use App\Models\PurchaseRequest;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class PurchasingDetail extends Component
{

    #[Url(as: 'reqId', history: true)]
    public string $requestId = '';

    #[Url(as: 'option')]
    public string $option = '';

    public function render()
    {
        return view('livewire.purchasing.purchasing-detail', ['purchases' => $this->getPurchasingDetail($this->requestId)]);
    }

    private function getPurchasingDetail(string $requestId)
    {
        try {
            return PurchaseRequest::with('reference.requestable', 'history', 'detail.item')->findOrFail($requestId);
        } catch (Exception $exception) {
            Log::error("gagal mendapatkan data detail purchasing {$this->id}");
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }
    }


    /**
     * lakukan validasi permintaan pembelian
     * @param $id
     * @return array|void
     */
    public function validatePurchase($id)
    {
        try {
            DB::beginTransaction();
            Log::info('asdasd');
            $purchase = $this->findPurchaseById($id);
            $latestStatus = $purchase->history->last()->status;

            // validasi hanya boleh berjalan selama status purchase adalah diminta
            if ($latestStatus != 'Diminta') {
                return;
            }

            // buat validasi status di purchase history
            $result = $purchase->history()->create(['desc' => 'Validasi permintaan pembelian oleh purchasing',
                'status' => 'Divalidasi'
            ]);

            if ($result) {
                Log::info('berhasil melakukan validasi');
                notify()->success('Berhasil validasi');

                $this->option = $result->status;
                Log::info($this->option);
                DB::commit();
                return;
            }

            DB::rollBack();
            notify()->error('Gagal validasi');
        } catch (Exception $exception) {
            DB::rollBack();
            notify()->error('Gagal validasi');
            Log::error('gagal validasi permintaan pembelian');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }
    }


    private function findPurchaseById($id)
    {
        return Purchase::findOrFail($id);
    }
}
