<?php

namespace App\Contract\Warehouse;

use App\Dto\WarehouseDTO;
use App\Models\Area;
use App\Models\Rack;
use App\Models\Warehouse;

interface WarehouseRepository
{

    public function createWarehouse(WarehouseDTO $warehouseDTO): Warehouse;

    public function createRelationCentral(Warehouse $warehouse, WarehouseDTO $warehouseDTO): array;
    public function createRelationOutlet(Warehouse $warehouse, WarehouseDTO $warehouseDTO): array;

    public function createAreas(string $warehouseId, string $name):  Area;
    public function createRacks(string $areaId, string $name): Rack;

}
