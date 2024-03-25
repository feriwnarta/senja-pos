<?php

namespace App\Livewire\Purchase;

use App\Jobs\Purchase\CreatePurchase;
use App\Jobs\Purchase\CreatePurchaseRequestFromStock;
use App\Models\PurchaseRequest;
use App\Service\PurchaseService;
use App\Traits\Jobs;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Number;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class PurchaseRequestDetail extends Component
{

    use Jobs;

    #[Url(as: 'reqId', keep: true)]
    public string $requestId = '';

    #[Url(as: 'status', keep: true)]
    public string $status = '';

    public string $currentDateTime = '';
    public array $suppliers;

    public ?string $supplier = null;
    public string $payment = 'NET';

    public array $paymentType = ['NET', 'PIA'];
    public array $paymentDueDates = [
        3, 7, 14, 30
    ];

    public string $dueDate = '';
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

        $response = $this->ajaxDispatch(new CreatePurchaseRequestFromStock($id));

        if ($response['success']) {
            $this->redirect("/purchase-request/detail?reqId={$this->requestId}", true);
            notify()->success('Berhasil memproses permintaan');
        } else {
            notify()->error('Gagal memproses permintaan');
        }

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
     *
     * @return void
     */
    private function store()
    {

        $response = $this->ajaxDispatch(new CreatePurchase($this->isMultipleSupplier, $this->requestId, $this->supplier, $this->payment, $this->dueDate, $this->componentItems));

        Log::info($response);

        if ($response['success']) {
            $id = $this->purchaseRequestDetail->purchaseReference->first()->purchase->first()->id;
            $this->redirect("/purchased/detail?pId=$id", true);
            notify()->success('Berhasil membuat pembelian');
        } else {
            notify()->error('Gagal membuat pembelian');
        }
    }

    #[On('set-due-date')]
    public function setDueDate($date)
    {
        $this->paymentDueDates[] = $date;
        $this->dueDate = $date;
    }

    public function handlePaymentDateChange()
    {

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

    private function findPurchaseRequestById($id)
    {
        return PurchaseRequest::with('history')->findOrFail($id);
    }

}
