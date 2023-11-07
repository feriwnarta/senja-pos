<?php

namespace App\Service\Impl;

use App\Models\Warehouse;
use App\Service\WarehouseService;

class WarehouseServiceImpl implements WarehouseService
{

    public function getDetailWarehouse(string $id): Warehouse
    {

        $warehouse = Warehouse::find($id);

        if (!$warehouse) {
            throw new \Exception("warehouse not found by id : $id", code: 1);
        }

        return $warehouse;
    }
}
