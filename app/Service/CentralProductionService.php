<?php

namespace App\Service;

interface CentralProductionService
{

    public function generateCode(string $requestStockId, string $centralKitchenId): array;


    public function createProduction(string $requestStockId, string $centralKitchenId): string;


}
