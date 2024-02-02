<?php

namespace App\Repository;

use App\Models\WarehouseItemReceipt;

interface WarehouseItemReceiptRepository
{

    public function findWarehouseItemReceiptById(string $warehouseItemReceiptId): WarehouseItemReceipt;

    public function getLastCodeDataByWarehouseId(string $warehouseId): array;

    public function updateCodeDataExistingItemReceipt(string $warehouseReceiptId, string $code, string $increment): bool;

    public function createWarehouseItemReceiptDetail(array $items): bool;

    public function creteNewWarehouseItemReceiptHistory(string $warehouseReceiptId, string $desc, string $status): bool;


}
