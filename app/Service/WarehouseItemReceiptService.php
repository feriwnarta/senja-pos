<?php

namespace App\Service;

interface WarehouseItemReceiptService
{
    public function accept(string $itemReceiptId, string $warehouseId, string $warehouseCode, array $items): bool;

    public function generateCodeReceipt(string $itemReceiptId, string $warehouseId, string $warehouseCode);

}
