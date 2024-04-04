<?php

namespace App\Repository;

use App\Models\Purchase;
use App\Models\WarehouseItemReceipt;
use Illuminate\Database\Eloquent\Collection;

interface PurchaseRepository
{

    public function findPurchaseById(string $id): Purchase;

    public function createPurchaseHistory(string $id, array $data): Purchase;

    public function insertWarehouseReceiptData(string $warehouseId, string $referenceId): WarehouseItemReceipt;

    public function createWarehouseItemReceiptDetail(string $warehouseItemReceiptId, Collection $itemData): bool;

    public function getPurchaseItems(string $purchaseId): Collection;

}
