<?php

namespace App\Repository\Impl;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\WarehouseItemReceipt;
use App\Models\WarehouseItemReceiptDetail;
use App\Models\WarehouseItemReceiptHistory;
use App\Repository\PurchaseRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PurchaseRepositoryImpl implements PurchaseRepository
{

    public function createPurchaseHistory(string $id, array $data): Purchase
    {
        try {

            if (!isset($data) || !isset($id)) {
                throw new Exception('parameter yang dikirim ke fungsi create purchase history kosong');
            }

            // panggil find purchase by id
            $purchase = $this->findPurchaseById($id);

            $purchase->history()->create([
                'desc' => $data['desc'],
                'status' => $data['status'],
            ]);

            Log::info('pengiriman dibuat');

            return $purchase;
        } catch (Exception $exception) {
            Log::error('gagal membuat purchase history baru berdasarkan id' . $id);
            Log::error('dengan data' . json_encode($data, JSON_PRETTY_PRINT));
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }

    public function findPurchaseById(string $id): Purchase
    {
        try {

            return Purchase::findOrFail($id);

        } catch (Exception $exception) {
            Log::error("Gagal mendapatkan purchase berdasarkan id $id");
            Log::error($exception->getMessage());
            Log::error($exception->getMessage());
            throw $exception;
        }
    }


    public function insertWarehouseReceiptData(string $warehouseId, string $referenceId): WarehouseItemReceipt
    {
        try {

            // buat item receipt
            return WarehouseItemReceipt::create([
                'warehouse_item_receipt_refs_id' => $referenceId,
                'warehouses_id' => $warehouseId,
            ]);

        } catch (Exception $exception) {
            Log::error('gagal membuat warehouse item receipt');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }

    public function createWarehouseReceiptHistory(string $warehouseItemReceiptId, string $desc, string $status): WarehouseItemReceiptHistory
    {
        try {

            // buat item receipt
            return WarehouseItemReceiptHistory::create([
                'warehouse_item_receipts_id' => $warehouseItemReceiptId,
                'desc' => $desc,
                'status' => $status,
            ]);
        } catch (Exception $exception) {
            Log::error('gagal membuat warehouse item receipt history');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }

    public function getPurchaseItems(string $purchaseId): Collection
    {
        try {
            return PurchaseDetail::where('purchases_id', $purchaseId)->select('items_id', 'qty_buy')->get();
        } catch (Exception $exception) {
            Log::error('gagal mendapatkan purchase items details');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }

    public function createWarehouseItemReceiptDetail(string $warehouseItemReceiptId, Collection $itemData): bool
    {
        try {
            $dataToInsert = $itemData->map(function ($item) use ($warehouseItemReceiptId) {
                return [
                    'id' => Str::uuid(),
                    'warehouse_items_receipts_id' => $warehouseItemReceiptId,
                    'items_id' => $item->items_id,
                    'qty_send' => $item->qty_buy,
                    'created_by' => auth()->user() == null ? 'USER NOT LOGIN' : auth()->user()->id,
                ];
            });

            return WarehouseItemReceiptDetail::insert($dataToInsert->toArray());

        } catch (Exception $exception) {
            Log::error('gagal menyimpan warehouse item receipt details');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }
}
