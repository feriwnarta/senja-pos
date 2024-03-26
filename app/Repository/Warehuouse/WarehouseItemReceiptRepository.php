<?php

namespace App\Repository\Warehuouse;

use App\Models\CentralProduction;
use App\Models\CentralProductionFinish;
use App\Models\Item;
use App\Models\PurchaseHistory;
use App\Models\StockItem;
use App\Models\WarehouseItem;
use App\Models\WarehouseItemReceipt;
use App\Models\WarehouseItemReceiptDetail;
use App\Models\WarehouseItemReceiptHistory;
use App\Models\WarehouseItemReceiptRef;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class WarehouseItemReceiptRepository implements \App\Contract\Warehouse\WarehouseItemReceiptRepository
{

    public function setCodeExistingWarehouseItemReceipt(string $warehouseItemReceiptId, string $code, int $increment): bool
    {
        $warehouseItemReceipt = $this->findWarehouseItemReceiptById($warehouseItemReceiptId);
        $warehouseItemReceipt->code = $code;
        $warehouseItemReceipt->increment = $increment;
        return $warehouseItemReceipt->save();
    }

    public function findWarehouseItemReceiptById(string $id): WarehouseItemReceipt
    {
        return WarehouseItemReceipt::findOrFail($id);
    }

    /**
     * update jumlah quantity penerimaan dari warehouse item details yang sudah ada
     *
     * @param array $itemDetails
     * @return bool
     * @throws Exception
     */
    public function updateAmountReceivedExistingDetails(array $itemDetails): bool
    {

        foreach ($itemDetails as $item) {
            $detail = WarehouseItemReceiptDetail::findOrFail($item['id']);
            $detail->qty_accept = $item['qty_accept'];
            $result = $detail->save();

            // jika penyimpanan gagal maka lemparkan error
            if (!$result) {
                throw new Exception(json_encode([
                    'message' => 'update updateAmountReceivedExistingDetails gagal',
                    'model' => $detail
                ]));
            }
        }
        return true;
    }

    public function getCostProduction(string $productionId, string $itemId): array
    {
        $production = $this->findProductionById($productionId);
        $componentCost = $production->finishes->where('item_id', $itemId);

        if ($componentCost->isEmpty()) {
            throw new Exception("komponent cost tidak ditermukan itemId = $itemId, productionId = $productionId");
        }

        $componentCost = $componentCost->first();
        $componentCostDetail = $componentCost->details;

        if ($componentCostDetail->isEmpty()) {
            throw new Exception("detail komponent cost tidak ditermukan componentCostid = {$componentCost->id} itemId = $itemId, productionId = $productionId");
        }

        $totalCost = $componentCostDetail->map(function ($component) {
            // hitung avg cost dari bahan yang digunakan
            $qty = $component->amount_used;
            $avgCost = $component->avg_cost;
            return $qty * $avgCost;
        })->toArray();

        return [
            'production_id' => $productionId,
            'item_id' => $itemId,
            'total_cost' => $totalCost[0],
        ];
    }

    public function findProductionById(string $productionId): CentralProduction
    {
        return CentralProduction::findOrFail($productionId);
    }

    public function getItemRoute(string $itemId): string
    {
        return Item::findOrFail($itemId)->route;
    }

    public function getProductionRemaining(string $productionId, string $itemId): array
    {
        $production = $this->findProductionById($productionId);

        $productionRemaining = $production->remaining;

        if ($productionRemaining->isEmpty()) {
            return [];
        }


        $productionRemainingDetail = $productionRemaining->first()->detail->where('items_id', $itemId);
        if ($productionRemainingDetail->isEmpty()) {
            return [];
        }

        $productionRemainingDetail = $productionRemainingDetail->first();
        $avgCost = $productionRemainingDetail->avg_cost;
        $qty = $productionRemainingDetail->qty_remaining;

        return [
            'production_id' => $productionId,
            'item_id' => $itemId,
            'qty' => $qty,
            'avg_cost' => $avgCost
        ];
    }

    public function getLastCodeByWarehouse(string $warehouseId): array
    {
        $itemReceipt = WarehouseItemReceipt::where('warehouses_id', $warehouseId)
            ->whereNotNull('code')
            ->latest('created_at')
            ->first();

        if (is_null($itemReceipt)) {
            return [
            ];
        }


        return [
            'increment' => $itemReceipt->increment,
            'created_at' => $itemReceipt->created_at,
        ];
    }

    public function getWarehouseItemReceiptIdByRef(string $itemReceiptRefId): string
    {
        $receiptRef = $this->findWarehouseItemReceiptRefById($itemReceiptRefId);
        return $receiptRef->itemReceipt->id;
    }

    public function findWarehouseItemReceiptRefById(string $id): WarehouseItemReceiptRef
    {
        return WarehouseItemReceiptRef::findOrFail($id);
    }

    public function getWarehouseCodeByWarehouseReceipt(string $warehouseItemReceiptId): ?string
    {
        $existingReceipt = $this->findWarehouseItemReceiptById($warehouseItemReceiptId);
        return $existingReceipt->warehouse->warehouse_code;
    }

    public function getWarehouseIdByWarehouseReceipt(string $warehouseItemReceiptId): string
    {
        $existingReceipt = $this->findWarehouseItemReceiptById($warehouseItemReceiptId);
        return $existingReceipt->warehouse->id;
    }

    public function getWarehouseItemId(string $warehouseId, string $itemId): string
    {
        $warehouseItem = WarehouseItem::where('warehouses_id', $warehouseId)
            ->where('items_id', $itemId)->get();

        if ($warehouseItem->isEmpty()) {
            throw new Exception("warehouse item tidak ditemukan oleh warehouse id = $warehouseId, dengan item id = $itemId");
        }

        return $warehouseItem->first()->id;
    }

    public function getStockItemByWarehouseItemId(string $warehouseItemId): ?StockItem
    {
        $stockItem = StockItem::where('warehouse_items_id', $warehouseItemId)->get();
        if ($stockItem->isEmpty()) {
            return null;
        }
        return $stockItem->last();
    }

    public function insertNewStockItem(string $warehouseItemId, array $cogsValuationCalcData): StockItem
    {
        if (empty($cogsValuationCalcData)
            || !isset($cogsValuationCalcData['incoming_qty'])
            || !isset($cogsValuationCalcData['incoming_value'])
            || !isset($cogsValuationCalcData['price_diff'])
            || !isset($cogsValuationCalcData['inventory_value'])
            || !isset($cogsValuationCalcData['qty_on_hand'])
            || !isset($cogsValuationCalcData['avg_cost'])
            || !isset($cogsValuationCalcData['last_cost'])
        ) {
            throw new Exception('pastikan parameter cogs valuation calc data tidak kosong atau parameternya sesuai');
        }


        return StockItem::create([
            'warehouse_items_id' => $warehouseItemId,
            'incoming_qty' => $cogsValuationCalcData['incoming_qty'],
            'incoming_value' => $cogsValuationCalcData['incoming_value'],
            'price_diff' => $cogsValuationCalcData['price_diff'],
            'inventory_value' => $cogsValuationCalcData['inventory_value'],
            'qty_on_hand' => $cogsValuationCalcData['qty_on_hand'],
            'avg_cost' => $cogsValuationCalcData['avg_cost'],
            'last_cost' => $cogsValuationCalcData['last_cost'],
        ]);
    }

    public function getProductionFinishDetailsByProductionId(string $productionId): Collection
    {
        $productionFinish = $this->getProductionFinishesByProductionId($productionId);

        if ($productionFinish->details->isEmpty()) {
            throw new Exception("central production finish details tidak ditemukan dari production id = $productionId");
        }

        return $productionFinish->details;

    }

    public function getProductionFinishesByProductionId(string $productionId): CentralProductionFinish
    {
        $productionFinish = CentralProductionFinish::where('central_productions_id', $productionId)->get();

        if ($productionFinish->isEmpty()) {
            throw new Exception("central production finish tidak ditemukan dari production id = $productionId");
        }

        return $productionFinish->first();
    }

    public function createWarehouseItemReceiptHistory(string $warehouseItemReceiptId, string $description, string $status): WarehouseItemReceiptHistory
    {
        $itemReceiptHistory = new WarehouseItemReceiptHistory();
        $itemReceiptHistory->warehouse_item_receipts_id = $warehouseItemReceiptId;
        $itemReceiptHistory->desc = $description;
        $itemReceiptHistory->status = $status;
        $itemReceiptHistory->save();

        return $itemReceiptHistory;
    }

    public function createPurchaseHistory(string $purchaseId, string $description, string $status): PurchaseHistory
    {
        $purchaseHistory = new PurchaseHistory();
        $purchaseHistory->purchases_id = $purchaseId;
        $purchaseHistory->desc = $description;
        $purchaseHistory->status = $status;
        $purchaseHistory->save();
        return $purchaseHistory;
    }
}
