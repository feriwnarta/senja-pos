<?php

namespace App\Service;

use App\Models\CentralProduction;

interface CentralProductionService
{

    public function generateCode(string $requestStockId, string $centralKitchenId): array;


    public function createProduction(string $requestStockId, string $centralKitchenId, array $dataForProductionFinishes): ?CentralProduction;

    public function cancelCreateProduction(CentralProduction $centralProduction);


    public function saveComponent(string $productionId, array $component);

    public function getSaveComponent(CentralProduction $production);

    public function saveEditComponent(CentralProduction $centralProduction, array $components);


    // TODO: Perbaiki service ini seharusnya ada diwarehouse service
    public function joinSameItemRequestMaterial(array $materials);

    public function requestMaterialToWarehouse(CentralProduction $production, array $materials, string $warehouseId, string $productionId, string $requestId);

    public function genereateCodeItemOut(string $warehouseId);


    public function saveCodeItemOut(string $outboundId, string $warehouseId);

    public function processItemReceiptProduction(array $items, string $outboundId, CentralProduction $production);

    public function finishProduction(array $items, string $productionId, string $note);

    public function generateCodeProductionShipping(string $productionId, string $centralKitchenId, string $centralKitchenCode);

    public function createProductionShipping(string $productionId, string $centralKitchenId, string $centralKitchenCode);

    public function createItemReceipt(string $warehouseId, array $itemReceiptDetail, CentralProduction $centralProduction);

}
