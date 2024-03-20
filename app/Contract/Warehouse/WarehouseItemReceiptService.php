<?php

namespace App\Contract\Warehouse;

use App\Dto\WarehouseItemReceiptDTO;

interface WarehouseItemReceiptService
{
    public function receipt(WarehouseItemReceiptDTO $itemReceiptDTO);
}
