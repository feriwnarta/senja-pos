<?php

namespace App\Service;

abstract class InventoryValuationCalc
{

    abstract function initialAvg(float $initialStock, float $initialAvgCost, float $initialLastCost): array;

    abstract function calculateAvgPrice(float $inventoryValue, float $oldQty, float $oldAvgCost, float $incomingQty, float $purchasePrice, bool $isReduce): array;


}
