<?php

namespace App\Jobs\Warehouse;

use App\Models\WarehouseItemReceiptRef;
use App\Service\Impl\WarehouseItemReceiptServiceImpl;
use App\Service\WarehouseItemReceiptService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AcceptReceipt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private WarehouseItemReceiptRef $itemReceiptRef;
    private string $itemReceiptRefId;
    private string $warehouseId;
    private string $warehouseCode;
    private array $items;

    private WarehouseItemReceiptService $itemReceiptService;


    /**
     * Create a new job instance.
     */
    public function __construct(array $receipt, $itemReceiptRef)
    {

        if (!isset($receipt) || empty($receipt) || !isset($itemReceiptRef)) {
            return;
        }

        $this->itemReceiptRefId = $receipt['itemReceiptRefId'];
        $this->warehouseId = $receipt['warehouseId'];
        $this->warehouseCode = $receipt['warehouseCode'];
        $this->items = $receipt['items'];
        $this->itemReceiptRef = $itemReceiptRef;

        $this->itemReceiptService = app()->make(WarehouseItemReceiptServiceImpl::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): bool
    {

        if (!isset($this->itemReceiptService)) {
            throw new Exception('parameter yang dikirim ke job ini kosong atau null');
        }

        return $this->itemReceiptService->accept($this->itemReceiptRef, $this->itemReceiptRefId, $this->warehouseId, $this->warehouseCode, $this->items);
    }
}
