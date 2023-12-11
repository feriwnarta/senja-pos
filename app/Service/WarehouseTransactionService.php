<?php

namespace App\Service;

interface WarehouseTransactionService
{

    public function generateCodeRequest(bool $isOutlet, string $id);
}
