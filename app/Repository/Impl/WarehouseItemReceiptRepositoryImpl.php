<?php

namespace App\Repository\Impl;

use App\Models\StockItem;
use App\Models\WarehouseItem;
use App\Models\WarehouseItemReceipt;
use App\Models\WarehouseItemReceiptDetail;
use App\Repository\WarehouseItemReceiptRepository;
use App\Service\Impl\CogsValuationCalc;
use App\Service\InventoryValuationCalc;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;

class WarehouseItemReceiptRepositoryImpl implements WarehouseItemReceiptRepository
{

    private InventoryValuationCalc $inventoryValuationCalc;

    /**
     * update code data  (code, increment) dari warehouse item receipt yang sudah ada
     * @param string $warehouseReceiptId
     * @param string $code
     * @param string $increment
     * @return bool
     */
    public function updateCodeDataExistingItemReceipt(string $warehouseReceiptId, string $code, string $increment): bool
    {
        try {
            $warehouseReceipt = $this->findWarehouseItemReceiptById($warehouseReceiptId);

            return $warehouseReceipt->update([
                'code' => $code,
                'increment' => $increment,
            ]);

        } catch (Exception $exception) {
            Log::error("gagal mengupdate code data di item receipt $warehouseReceiptId");
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw  $exception;
        }
    }

    public function findWarehouseItemReceiptById(string $warehouseItemReceiptId): WarehouseItemReceipt
    {
        try {
            return WarehouseItemReceipt::findOrFail($warehouseItemReceiptId);
        } catch (Exception $exception) {
            Log::error('gagal mendapatkan data warehouse item receipt berdasarkan id warehouse item receipt');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw  $exception;
        }
    }

    /**
     * buat warehouse item receipt detail
     * @param string $warehouseItemReceiptDetailId
     * @param array $items
     * @return bool
     */
    public function createWarehouseItemReceiptDetail(array $items): bool
    {
        try {
            // Update details
            foreach ($items as $item) {
                WarehouseItemReceiptDetail::where('id', $item['id'])->update([
                    'qty_accept' => $item['qty_accept']
                ]);
            }

            return true;
        } catch (Exception $exception) {
            Log::error('gagal mendapatkan data warehouse item receipt berdasarkan id warehouse item receipt');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw  $exception;
        }
    }

    public function creteNewWarehouseItemReceiptHistory(string $warehouseReceiptId, string $desc, string $status): bool
    {
        try {
            $warehouseItemReceipt = $this->findWarehouseItemReceiptById($warehouseReceiptId);
            $warehouseItemReceipt->history()->create([
                'desc' => $desc,
                'status' => $status,
            ]);
            return $warehouseItemReceipt->save();
        } catch (Exception $exception) {
            Log::error('gagal membuat warehouse item receipt history bary dari fungsi warehouse item receipt repository');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }
    }

    /**
     * melakukan penyimpanan inventory valuation
     * @param array $items
     * @return void
     */
    public function setInventoryValuation(string $warehouseId, array $items)
    {
        try {

            $this->inventoryValuationCalc = app()->make(CogsValuationCalc::class);
            $itemsId = [];

            // Dapatkan id items
            foreach ($items as $item) {
                $itemsId[] = $item['id'];
            }

            $warehouseItems = WarehouseItem::where('warehouses_id', $warehouseId)->whereIn('items_id', $itemsId)->get();

            foreach ($warehouseItems as $key => $warehouseItem) {

                // dapatkan data inventory valuation terakhir
                $itemReceiptDetail = WarehouseItemReceiptDetail::where('items_id', $warehouseItem->items_id)->first();
                $inventoryValue = $warehouseItem->stockItem->last()->inventory_value;
                $oldQty = $warehouseItem->stockItem->last()->qty_on_hand;
                $oldAvgCost = $warehouseItem->stockItem->last()->avg_cost;
                $incomingQty = $itemReceiptDetail->qty_accept;
                $price = $items[$key]['price'];


                $result = $this->inventoryValuationCalc->calculateAvgPrice($inventoryValue, $oldQty, $oldAvgCost, $incomingQty, $price, false);
                Log::debug('hitung cogs');
                Log::debug($result);

                // isi cogs
                $stockItem = StockItem::create([
                    'warehouse_items_id' => $warehouseItem->id,
                    'incoming_qty' => $result['incoming_qty'],
                    'incoming_value' => $result['incoming_value'],
                    'qty_on_hand' => $result['qty_on_hand'],
                    'avg_cost' => $result['avg_cost'],
                    'last_cost' => $result['last_cost'],
                ]);


                Log::debug($stockItem);
            }

        } catch (Exception $exception) {
            Log::error('gagal memberikan inventory valuation');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }
    }

    /**
     * dapatkan data last code warehouse item receipt berdasarkan id warehouse yang tidak null
     * @param string $warehouseId
     * @return array
     */
    public function getLastCodeDataByWarehouseId(string $warehouseId): array
    {
        try {
            $itemReceipt = WarehouseItemReceipt::where('warehouses_id', $warehouseId)
                ->whereNotNull('code')
                ->latest('created_at')
                ->first();

            if ($itemReceipt == null) {
                return [];
            }


            return [
                'code' => $itemReceipt->code,
                'increment' => $itemReceipt->increment,
                'created_at' => $itemReceipt->created_at,
            ];
        } catch (Exception $exception) {
            Log::error("gagal mendapatkan data kode warehouse receipt berdasarkan warehouse id $warehouseId dari fungsi getLastCodeDataByWarehouseId repository");
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw  $exception;
        }
    }

    private function findLastStockItem(string $warehouseItemsId)
    {

    }
}
