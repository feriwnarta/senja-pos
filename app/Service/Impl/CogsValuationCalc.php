<?php

namespace App\Service\Impl;

use App\Service\InventoryValuationCalc;
use Illuminate\Support\Facades\Log;

class CogsValuationCalc extends InventoryValuationCalc
{

    function initialAvg(float $initialStock, float $initialAvgCost): array
    {
        // TODO: Last cost terakhir
        $totalFirstCost = $initialStock * $initialAvgCost;

        return [
            'incoming_qty' => $initialStock,
            'incoming_value' => number_format($totalFirstCost, 3),
            'price_diff' => 0,
            'inventory_value' => number_format($totalFirstCost, 3),
            'qty_on_hand' => $initialStock,
            'avg_cost' => number_format($initialAvgCost, 3),
            'last_cost' => 0,
        ];
    }

    public function calculateAvgPrice(float $inventoryValue, float $oldQty, float $oldAvgCost, float $incomingQty, float $purchasePrice): array
    {
        // Formula: (old qty * old avg cost) + (incoming qty * purchase price) / total qty

        $incomingValue = number_format($incomingQty * $purchasePrice, 3);
        $inventoryValue = number_format($inventoryValue + $incomingValue, 3);
        $qtyOnHand = number_format($oldQty + $incomingQty, 3);

        Log::debug('incoming value : ' . $incomingValue);
        Log::debug("inventory value : $inventoryValue");
        Log::debug("onhand  : $qtyOnHand");

        // Calculate the new average cost
        $avgCost = ($oldQty * $oldAvgCost + $incomingQty * $purchasePrice) / $qtyOnHand;
        $avgCost = number_format($avgCost, 3);

        Log::debug("AVG " . $avgCost);


        // Calculate the difference between the new average cost and the purchase price
        $priceDiff = $avgCost - $purchasePrice;

        return [
            'incoming_qty' => $incomingQty,
            'incoming_value' => number_format($incomingValue, 3),
            'price_diff' => number_format($priceDiff, 3),
            'inventory_value' => number_format($inventoryValue, 3),
            'qty_on_hand' => $qtyOnHand,
            'avg_cost' => number_format($avgCost, 3),
            'last_cost' => number_format($purchasePrice, 3),
        ];
    }


}
