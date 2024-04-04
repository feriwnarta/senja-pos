<?php

namespace App\Repository\Warehuouse;

use App\Dto\WarehouseDTO;
use App\Models\Area;
use App\Models\Rack;
use App\Models\Warehouse;

class WarehouseRepository implements \App\Contract\Warehouse\WarehouseRepository
{

    public function createWarehouse(WarehouseDTO $warehouseDTO): Warehouse
    {
        $warehouse = new Warehouse();
        $warehouse->warehouse_code = $warehouseDTO->getCode();
        $warehouse->name = $warehouseDTO->getName();
        $warehouse->address = $warehouseDTO->getAddress();
        $warehouse->save();
        return $warehouse;
    }


    /**
     * buat warehouse untuk central
     * @param WarehouseDTO $warehouseDTO
     * @return Warehouse
     */
    public function createRelationCentral(Warehouse $warehouse, WarehouseDTO $warehouseDTO): array
    {
        return $warehouse->centralKitchen()->syncWithoutDetaching($warehouseDTO->getSourceId());
    }

    /**
     * buat warehouse untuk outlet
     * @param WarehouseDTO $warehouseDTO
     * @return Warehouse
     */
    public function createRelationOutlet(Warehouse $warehouse, WarehouseDTO $warehouseDTO): array
    {
        return $warehouse->outlet()->syncWithoutDetaching($warehouseDTO->getSourceId());
    }

    public function createAreas(string $warehouseId, string $name): Area
    {
        $area = new Area();
        $area->warehouses_id = $warehouseId;
        $area->name = $name;
        $area->save();
        return $area;
    }

    public function createRacks(string $areaId, string $name): Rack
    {
        $rack = new Rack();
        $rack->areas_id = $areaId;
        $rack->name = $name;
        $rack->save();
        return  $rack;
    }
}
