<?php

namespace App\Service\Impl;

use App\Service\InventoryValuationCalc;

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

        $incomingValue = $incomingQty * $purchasePrice;
        $inventoryValue = $inventoryValue + $incomingValue;
        $qtyOnHand = $oldQty + $incomingQty;

        // Calculate the new average cost
        $avgCost = ($oldQty * $oldAvgCost + $incomingQty * $purchasePrice) / $qtyOnHand;

        // Calculate the difference between the new average cost and the purchase price
        $priceDiff = $avgCost - $purchasePrice;

        // Calculate last_cost based on accounting rules
        if ($incomingQty >= 0) {
            // Jika incomingQty >= 0 (pemasukan), set last_cost sesuai dengan avg_cost
            $lastCost = $avgCost;
        } else {
            // Jika incomingQty < 0 (pengeluaran), last_cost tetap sesuai dengan last_cost sebelumnya
            $lastCost = $oldAvgCost; // Anda mungkin perlu menyesuaikan ini sesuai dengan logika bisnis yang tepat
        }

        return [
            'incoming_qty' => $incomingQty,
            'incoming_value' => number_format($incomingValue, 3),
            'price_diff' => number_format($priceDiff, 3),
            'inventory_value' => number_format($inventoryValue, 3),
            'qty_on_hand' => number_format($qtyOnHand, 3),
            'avg_cost' => number_format($avgCost, 3),
            'last_cost' => number_format($lastCost, 3),
        ];
    }


}
