<?php

namespace App\Livewire\Purchase;

use App\Models\PurchaseRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class PurchaseRequestDetail extends Component
{

    #[Url(as: 'reqId', keep: true)]
    public string $requestId = '';

    #[Url(as: 'status', keep: true)]
    public string $status = '';

    public string $currentDateTime = '';
    public array $suppliers;
    private PurchaseRequest $purchaseRequestDetail;


    public function mount()
    {
        $this->currentDateTime = Carbon::now();
        // ambil data supplier jika statusnya adalah diproses

        // lakukan inisialisasi data yang dibutuhkan untuk proses ini
        $this->initData();

    }

    private function initData()
    {
        $purchaseRequestDetail = $this->getPurchasingDetail($this->requestId);
        if ($purchaseRequestDetail != null) {
            $this->purchaseRequestDetail = $purchaseRequestDetail;
            if (isset($purchaseRequestDetail->history->last()->status)) {
                $this->setStatus($purchaseRequestDetail->history->last()->status);
                $this->proccessGetSupplier();
            }
        }
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

    private function setStatus(string $status)
    {
        $this->status = $status;
    }

    private function proccessGetSupplier()
    {
        if ($this->status == 'Diproses') {
            $suppliers = $this->getSuppliers();
            if ($suppliers != null) {
                $this->suppliers = $suppliers->toArray();
                Log::debug($this->suppliers);
            }
        }
    }

    private function getSuppliers()
    {

        try {
            return \App\Models\Supplier::cursor()->take(100)->each(function ($supplier) {
                return $supplier;
            });

        } catch (Exception $exception) {
            Log::error('gagal dapatkan data supplier di pesanan pembelian');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

    }

    public function render()
    {
        return view('livewire.purchase.purchase-request-detail', ['purchaseRequests' => $this->purchaseRequestDetail]);
    }

    public function processRequest($id)
    {

        try {

            $purchaseRequest = $this->findPurchaseRequestById($id);
            $history = $purchaseRequest->history->last();
            $status = $history->status;


            if ($status != 'Permintaan baru') {
                notify()->error('Ada sesuatu yang salah silahkan hubungi admin');
                Log::error('ada sesuatu yang salah saat mengelola permintaan pembelian baru karena statusnya bukan permintaan baru');
                return;
            }
            DB::beginTransaction();
            // buat status baru
            $result = $history->create([
                'purchase_requests_id' => $id,
                'desc' => 'Permintaan pembelian diproses',
                'status' => 'Diproses',
            ])->save();

            if ($result) {
                DB::commit();
                notify()->success('Berhasil proses pembelian');


                return;
            }

            DB::rollBack();
            notify()->error('gagal proses permintaan');

        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('gagal melakukan proses permintaan');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }


    }

    private function findPurchaseRequestById($id)
    {
        return PurchaseRequest::with('history')->findOrFail($id);
    }

}
