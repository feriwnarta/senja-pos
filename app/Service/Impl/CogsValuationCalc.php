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

    public function calculateAvgPrice(float $inventoryValue, float $oldQty, float $oldAvgCost, float $incomingQty, float $purchasePrice, bool $isReduce): array
    {
        // Formula: (old qty * old avg cost) + (incoming qty * purchase price) / total qty

        if ($isReduce) {
            $incomingQty = -$incomingQty;
        }

        Log::debug($oldQty);
        Log::debug($oldAvgCost);
        Log::debug($incomingQty);
        Log::debug($purchasePrice);


        $incomingValue = $incomingQty * $purchasePrice;
        $inventoryValue = $inventoryValue + $incomingValue;
        $qtyOnHand = $oldQty + $incomingQty;

        if ($qtyOnHand == 0) {
            $avgCost = 0;
        } else {
            $avgCost = ($oldQty * $oldAvgCost + $incomingQty * $purchasePrice) / $qtyOnHand;
        }

        // Calculate the new average cost


        // Format the values with two decimal places
        $formattedIncomingValue = number_format($incomingValue, 2, '.', '');
        $formattedPriceDiff = number_format($avgCost - $purchasePrice, 2, '.', '');
        $formattedInventoryValue = number_format($inventoryValue, 2, '.', '');
        $formattedAvgCost = number_format($avgCost, 2, '.', '');
        $formattedLastCost = number_format($purchasePrice, 2, '.', '');

        return [
            'incoming_qty' => $incomingQty,
            'incoming_value' => $formattedIncomingValue,
            'price_diff' => $formattedPriceDiff,
            'inventory_value' => $formattedInventoryValue,
            'qty_on_hand' => $qtyOnHand,
            'avg_cost' => $formattedAvgCost,
            'last_cost' => $formattedLastCost,
        ];
    }


}
