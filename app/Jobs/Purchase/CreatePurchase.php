<?php

namespace App\Jobs\Purchase;

use App\Service\Impl\PurchaseServiceImpl;
use App\Service\PurchaseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreatePurchase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $isMultiSupplier;
    private string $purchaseRequestId;
    private string $supplierId;
    private string $payment;
    private string $paymentDueDate;
    private array $items;
    private PurchaseService $purchaseService;

    /**
     * @param string $isMultiSupplier
     * @param string $purchaseRequestId
     * @param string $supplierId
     * @param string $payment
     * @param string $paymentDueDate
     * @param array $items
     */
    public function __construct(string $isMultiSupplier, string $purchaseRequestId, string $supplierId, string $payment, string $paymentDueDate, array $items)
    {
        $this->isMultiSupplier = $isMultiSupplier;
        $this->purchaseRequestId = $purchaseRequestId;
        $this->supplierId = $supplierId;
        $this->payment = $payment;
        $this->paymentDueDate = $paymentDueDate;
        $this->items = $items;
        $this->purchaseService = app()->make(PurchaseServiceImpl::class);
    }


    /**
     * Execute the job.
     */
    public function handle(): bool
    {
        return $this->purchaseService->createPurchaseNetFromRequestStock($this->isMultiSupplier, $this->purchaseRequestId, $this->supplierId, $this->payment, $this->paymentDueDate, $this->items);
    }
}
