<?php

namespace App\Contract\Warehouse;

use App\Dto\WarehouseDTO;
use App\Models\Warehouse;

interface WarehouseService
{
    public function create(?WarehouseDTO $warehouseDTO): Warehouse;
}
