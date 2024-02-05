<?php

namespace App\Jobs\Purchase;

use App\Models\Purchase;
use App\Service\Impl\PurchaseServiceImpl;
use App\Service\PurchaseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PurchaseShipped implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $purchaseId;
    private array $history;
    private PurchaseService $purchaseService;

    /**
     * @param string $purchaseId
     * @param array $history
     */
    public function __construct(string $purchaseId, array $history)
    {
        $this->purchaseId = $purchaseId;
        $this->history = $history;
        $this->purchaseService = app()->make(PurchaseServiceImpl::class);
    }


    /**
     * Execute the job.
     */
    public function handle(): ?Purchase
    {
        return $this->purchaseService->purchaseHasBeenShipped($this->purchaseId, $this->history);
    }
}
