<?php

namespace App\Service;

interface PurchaseService
{

    public function processPurchaseRequestFromReqStock(string $purchaseReqId);

    public function createPurchaseNetFromRequestStock(string $purchaseReqId, string $supplierId, string $paymentScheme, string $dueDate, array $dataPurchase);

    public function generateCodePurchase(string $supplierCode);

    public function generateCodeRequestFromReqStock(string $warehouseId, string $purchaseRequestId);
    
}
