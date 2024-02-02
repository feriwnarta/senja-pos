<?php

namespace App\Service;

use App\Models\WarehouseItemReceiptRef;

interface WarehouseItemReceiptService
{
    public function accept(WarehouseItemReceiptRef $reference, string $itemReceiptId, string $warehouseId, string $warehouseCode, array $items): bool;

    public function generateCodeReceipt(string $itemReceiptId, string $warehouseId, string $warehouseCode);

}
