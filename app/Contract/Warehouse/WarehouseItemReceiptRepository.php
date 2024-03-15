<?php

namespace App\Contract\Warehouse;

use App\Models\WarehouseItemReceipt;
use App\Models\WarehouseItemReceiptRef;

interface WarehouseItemReceiptRepository
{
    public function findWarehouseItemReceiptRefById(string $id): WarehouseItemReceiptRef;
    public function findWarehouseItemReceiptById(string $id):WarehouseItemReceipt;

    public function setCodeExistingWarehouseItemReceipt(string $warehouseItemReceiptId, string $code, int $increment): bool;

    public function updateAmountReceivedExistingDetails(array $itemDetails): bool;
}
