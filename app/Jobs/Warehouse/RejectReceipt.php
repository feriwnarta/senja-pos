<?php

namespace App\Jobs\Warehouse;

use App\Service\Impl\WarehouseItemReceiptServiceImpl;
use App\Service\WarehouseItemReceiptService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RejectReceipt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $itemReceiptRefId;
    private WarehouseItemReceiptService $itemReceiptService;

    /**
     * Create a new job instance.
     */
    public function __construct(string $itemReceiptRefId)
    {
        $this->itemReceiptRefId = $itemReceiptRefId;
        $this->itemReceiptService = app()->make(WarehouseItemReceiptServiceImpl::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): bool
    {
        return $this->itemReceiptService->reject($this->itemReceiptRefId);
    }
}
