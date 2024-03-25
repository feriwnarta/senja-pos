<?php

namespace App\Service;

use App\Models\Purchase;

interface PurchaseService
{

    public function processPurchaseRequestFromReqStock(string $purchaseReqId);

    public function createPurchaseNetFromRequestStock(bool $isMultiSupplier, string $purchaseReqId, string $supplierId, string $paymentScheme, string $dueDate, array $dataPurchase);

    public function generateCodePurchase(string $supplierCode);

    public function generateCodeRequestFromReqStock(string $warehouseId, string $purchaseRequestId);

    public function purchaseHasBeenShipped(string $id, array $data): ?Purchase;

    public function getPurchaseStatus(string $id): string;
}
