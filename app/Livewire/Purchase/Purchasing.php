<?php

namespace App\Livewire\Purchase;

use App\Models\Purchase;
use App\Models\PurchaseRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Purchasing extends Component
{

    use WithPagination;

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
            notify()->error('gagal mengambil data pembelian (request)');
            Log::error('gagal mengambil data permintaan pembelian');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

    }


    public function detailPurchased($id)
    {
        if ($id != '') {
            $this->redirect("purchased/detail?pId=$id", true);
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
            case "purchase" :
                return $this->handlePurchase();
        }

    }

    private function handlePurchase()
    {
        try {
            return $purchases = Purchase::with(['detail' => function ($query) {
                $query->latest()->limit(1);
            }, 'reference.purchasable', 'supplier', 'history' => function ($query) {
                $query->latest()->limit(1);
            }])
                ->withSum('detail', 'total_price') // Menghitung total total_price di relasi detail
                ->orderBy('created_at', 'DESC')
                ->paginate(10);

        } catch (Exception $exception) {
            Log::error('gagal mengambil data pembelian (Purchase) ');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

    }

    public function detailPurchaseRequest($id)
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
