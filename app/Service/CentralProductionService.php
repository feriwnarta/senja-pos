<?php

namespace App\Service;

use App\Models\CentralProduction;

interface CentralProductionService
{

    public function generateCode(string $requestStockId, string $centralKitchenId): array;


    public function createProduction(string $requestStockId, string $centralKitchenId): ?CentralProduction;


    public function saveComponent(string $productionId, array $component);

    public function joinSameItemRequestMaterial(array $materials);

    public function requestMaterialToWarehouse(array $materials, string $warehouseId, string $productionId, string $requestId);

    public function genereateCodeItemOut(string $warehouseId);


    public function saveCodeItemOut(string $outboundId, string $warehouseId);


}
