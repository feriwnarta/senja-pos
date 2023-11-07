<?php

namespace App\Service;

use App\Models\Warehouse;

interface WarehouseService
{

    public function getDetailWarehouse(string $id): Warehouse;

}
