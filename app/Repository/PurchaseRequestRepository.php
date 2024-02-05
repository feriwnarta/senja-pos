<?php

namespace App\Repository;

use App\Models\PurchaseRequest;

interface PurchaseRequestRepository
{
    public function findPurchaseRequestById(string $purchaseRequestId): PurchaseRequest;
}
