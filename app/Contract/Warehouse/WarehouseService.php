<?php

namespace App\Contract\Warehouse;

use App\Dto\WarehouseDTO;
use App\Dto\WarehouseEnum;
use App\Models\Warehouse;

interface WarehouseService
{
    public function create(WarehouseDTO $warehouseDTO);
}
