<?php

namespace App\Service;

use App\Models\RequestStock;

interface WarehouseTransactionService
{

    public function generateCodeRequest(bool $isOutlet, string $id): ?array;

    public function createRequest(bool $isOutlet, string $id): RequestStock;
}
