<?php

namespace App\Jobs\Purchase;

use App\Service\Impl\PurchaseServiceImpl;
use App\Service\PurchaseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreatePurchaseRequestFromStock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $purchaseReqId;
    private PurchaseService $purchaseService;

    /**
     * Create a new job instance.
     */
    public function __construct(string $purchaseReqId)
    {
        $this->purchaseReqId = $purchaseReqId;
        $this->purchaseService = app()->make(PurchaseServiceImpl::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): bool
    {
        return $this->purchaseService->processPurchaseRequestFromReqStock($this->purchaseReqId);
    }
}
