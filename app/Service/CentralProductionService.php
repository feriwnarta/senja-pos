<?php

namespace App\Service;

use App\Models\CentralProduction;

interface CentralProductionService
{

    public function generateCode(string $requestStockId, string $centralKitchenId): array;


    public function createProduction(string $requestStockId, string $centralKitchenId): ?CentralProduction;


    public function saveComponent(string $productionId, array $component);


}
