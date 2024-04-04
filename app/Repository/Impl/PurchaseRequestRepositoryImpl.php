<?php

namespace App\Repository\Impl;

use App\Models\PurchaseRequest;
use App\Repository\PurchaseRequestRepository;

class PurchaseRequestRepositoryImpl implements PurchaseRequestRepository
{

    public function findPurchaseRequestById(string $purchaseRequestId): PurchaseRequest
    {
        return PurchaseRequest::findOrFail($purchaseRequestId);
    }
}
