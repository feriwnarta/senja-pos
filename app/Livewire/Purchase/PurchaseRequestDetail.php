<?php

namespace App\Livewire\Purchase;

use App\Models\PurchaseRequest;
use App\Service\Impl\PurchaseServiceImpl;
use App\Service\PurchaseService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Number;
use Livewire\Attributes\On;
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

    public ?string $supplier = null;
    public string $payment = 'NET';

    public array $paymentType = ['NET', 'PIA'];

    public string $dueDate = '3';
    public string $paymentTemp;


    public bool $isMultipleSupplier = false;
    public bool $isMultiplePayment = false;
    public string $note = '';
    public PurchaseRequest $purchaseRequestDetail;
    public string $indexPayment = '';

    public array $componentItems = [];
    private PurchaseService $purchaseService;

    public function handleValuePurchaseAmount($index)
    {
        $formattedUnitPrice = $this->componentItems[$index]['unitPrice'];
        $numericUnitPrice = floatval(str_replace(',', '', $formattedUnitPrice)); // Remove commas and convert to float
        $this->componentItems[$index]['unitPrice'] = Number::format(floatval($numericUnitPrice));
        $this->componentItems[$index]['totalAmount'] = $this->componentItems[$index]['purchaseAmount'] * $numericUnitPrice;
    }

    public function handleItemPaymentChange($index)
    {
        $this->paymentTemp = $this->componentItems[$index]['payment'];
        $this->indexPayment = $index;
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


            $this->purchaseService = app()->make(PurchaseServiceImpl::class);
            $result = $this->purchaseService->processPurchaseRequestFromReqStock($id);

            if ($result) {
                notify()->success('Berhasil membuat permintaan');
                return;
            }

            notify()->error('gagal proses permintaan');
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('gagal melakukan proses permintaan');
            notify()->error('gagal proses permintaan, hubungi administrator');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }


    }

    private function findPurchaseRequestById($id)
    {
        return PurchaseRequest::with('history')->findOrFail($id);
    }

    public function createPurchase()
    {

        Log::info('membuat purchasing');
        Log::info($this->componentItems);

        $this->validate(
            [
                'componentItems.*.unitPrice' => 'required',
            ]
        );

        Log::info('validasi berjalan');

        // lakukan proses pembuatan permintaan pembelian
        $this->store();
    }

    /**
     * proses pembuatan pembelian, harap diperhatikan penggunaan multiple pembelian
     * panggil service pembelian
     * @return void
     */
    private function store()
    {

        $this->purchaseService = app()->make(PurchaseServiceImpl::class);

        // lakukan proses pengecekan apa opsi multi supplier terpilih
        // jika iya maka akan tercipta proses multiple purchasing
        if ($this->isMultipleSupplier) {
            // jalankan service fungsi multi supplier
            Log::info('jalankan pembelian secara multi supplier');
            return;
        }

        try {
            // panggil fungsi buat purchase dari request stock
            $resultCreatePurchase = $this->purchaseService->createPurchaseNetFromRequestStock($this->requestId, $this->supplier, $this->payment, $this->dueDate, $this->componentItems);

            if ($resultCreatePurchase) {
                notify()->success('Berhasil membuat pembelian');
            }
        } catch (Exception $exception) {
            Log::error('gagal membuat pembelian');
            notify()->error('Gagal membuat pembelian');
        }


    }

    #[On('set-due-date')]
    public function setDueDate($date)
    {
        $this->dueDate = $date;
    }

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

                // set supplier data
                $this->proccessGetSupplier();

                if (!empty($this->suppliers) && !isset($this->supplierId)) {
                    $this->supplierId = $this->suppliers[0]['id'];
                    $this->supplier = $this->suppliers[0]['id'];
                    Log::info('init data');
                }
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

}