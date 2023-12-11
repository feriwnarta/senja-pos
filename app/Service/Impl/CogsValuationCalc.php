<?php

namespace App\Service\Impl;

use App\Service\InventoryValuationCalc;
use Illuminate\Support\Facades\Log;

class CogsValuationCalc extends InventoryValuationCalc
{

    function initialAvg(float $initialStock, float $initialAvgCost, float $initialLastCost): array
    {
        // TODO: Last cost terakhir
        $totalFirstCost = $initialStock * $initialAvgCost;

        Log::debug($totalFirstCost);

        return [
            'incoming_qty' => $initialStock,
            'incoming_value' => number_format(floatval($totalFirstCost), 3, '', ''),
            'price_diff' => 0,
            'inventory_value' => number_format(floatval($totalFirstCost), 3, '', ''),
            'qty_on_hand' => $initialStock,
            'avg_cost' => number_format(floatval($initialAvgCost), 3, '', ''),
            'last_cost' => number_format(floatval($initialLastCost), 3, '', ''),
        ];


    }

    public function calculateAvgPrice(float $inventoryValue, float $oldQty, float $oldAvgCost, float $incomingQty, float $purchasePrice): array
    {
        // Formula: (old qty * old avg cost) + (incoming qty * purchase price) / total qty

        $incomingValue = $incomingQty * $purchasePrice;
        $inventoryValue = $inventoryValue + $incomingValue;
        $qtyOnHand = $oldQty + $incomingQty;


        // Calculate the new average cost
        $avgCost = ($oldQty * $oldAvgCost + $incomingQty * $purchasePrice) / $qtyOnHand;
        $avgCost = $avgCost;

        Log::debug("AVG " . $avgCost);


        // Calculate the difference between the new average cost and the purchase price
        $priceDiff = $avgCost - $purchasePrice;

        return [
            'incoming_qty' => $incomingQty,
            'incoming_value' => number_format($incomingValue, 3, '', ''),
            'price_diff' => number_format($priceDiff, 3, '', ''),
            'inventory_value' => number_format($inventoryValue, 3, '', ''),
            'qty_on_hand' => $qtyOnHand,
            'avg_cost' => number_format($avgCost, 3, '', ''),
            'last_cost' => number_format($purchasePrice, 3, '', ''),
        ];
    }


}
