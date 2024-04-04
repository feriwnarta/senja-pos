<?php

namespace App\Service\Warehouse;

use App\Dto\WarehouseItemReceiptDTO;
use App\Models\StockItem;
use App\Service\Impl\CogsValuationCalc;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseItemReceiptPurchaseService extends WarehouseItemReceiptService implements \App\Contract\Warehouse\WarehouseItemReceiptService
{

    public function receipt(WarehouseItemReceiptDTO $itemReceiptDTO)
    {
        try {

            DB::transaction(function () use ($itemReceiptDTO) {

                $newDtoWithReceiptId = $this->setItemReceiptId($itemReceiptDTO);
                $itemReceiptId = $newDtoWithReceiptId->getId();

                // generate code item masuk
                Log::info('generate kode item receipt');
                $result = $this->generateCode($itemReceiptId);


                if (empty($result)) {
                    Log::error(json_encode([
                        'message' => "hasil generate kode array kosong saat penerimaan barang produksi",
                        'data' => $itemReceiptDTO
                    ]));
                    report(new Exception('hasil generate kode array kosong saat penerimaan barang produksi'));
                    return;
                }

                // set code ke item receipt yang sudah dibuat
                $code = $result['code'];
                $increment = $result['increment'];


                Log::info('set kode item receipt');
                // set kode dan incerement ke warehouse item receipt yang sudah dibuat
                $isSetCode = $this->repository->setCodeExistingWarehouseItemReceipt($itemReceiptId, $code, $increment);


                // jika is set code false, menandakan bahwa query update kode ke item receipt yang sudah ada gagal
                if (!$isSetCode) {
                    Log::error(json_encode([
                        'message' => 'gagal menyimpan kode ke item receipt yang sudah dibuat',
                        "data" => [
                            $newDtoWithReceiptId,
                            $result
                        ],
                    ]));
                    report(new Exception('gagal menyimpan kode ke item receipt yang sudah dibuat'));
                    return;
                }

                // update nilai jumlah diterima
                Log::info('set jumlah dan item yang diterima ke penerimaan detail');
                // buat detail penerimaan item beserta nilai quantity yang diterimanya
                $isUpdateAmountReceived = $this->repository->updateAmountReceivedExistingDetails($newDtoWithReceiptId->getDataItemReceipt());
                if (!$isUpdateAmountReceived) {
                    Log::error(json_encode([
                        "message" => "gagal update Amount Received Existing Details dipenerimaan barang produksi",
                        "data" => [
                            $newDtoWithReceiptId,
                        ]
                    ]));
                    report(new Exception('gagal update Amount Received Existing Details dipenerimaan barang produksi'));
                    return;
                }


                // proses terima jumlah yang dibeli di purchase detail
                $warehouseItemReceiptRef = $this->repository->findWarehouseItemReceiptRefById($newDtoWithReceiptId->getWarehouseItemReceiptRefId());
                $purchase = $warehouseItemReceiptRef->receivable;


                $dataItemPurchase = [];

                foreach ($newDtoWithReceiptId->getDataItemReceipt() as $item) {
                    $itemId = $item['item_id'];
                    $qtyAccept = $item['qty_accept'];
                    $purchaseDetail = $purchase->detail->where('items_id', $itemId);

                    if ($purchaseDetail->isNotEmpty()) {

                        $purchaseDetail = $purchaseDetail->first();
                        $purchaseDetail->qty_accept = $qtyAccept;
                        $purchaseDetail->save();

                        $dataItemPurchase[] = [
                            'item_id' => $purchaseDetail->items_id,
                            'singlePrice' => $purchaseDetail->single_price,
                            'total_price' => $purchaseDetail->total_price
                        ];
                    }
                }

                $warehouseId = $this->repository->getWarehouseIdByWarehouseReceipt($itemReceiptId);
                // insert ke stock inventory valuation
                $stockItem = $this->insertStockValuation($warehouseId, $dataItemPurchase, $newDtoWithReceiptId->getDataItemReceipt());

                if (is_null($stockItem)) {
                    Log::error(json_encode([
                        'message' => 'gagal melakukan penerimaan item masuk dari pembelian setelah menghitung stock inventory valuation',
                        'data' => [
                            "dto" => $itemReceiptDTO,
                            "warehouse_id" => $warehouseId,
                        ]
                    ]));
                    report(new Exception('gagal melakukan penerimaan item masuk dari pembelian setelah menghitung stock inventory valuation'));
                    abort(400);
                }

                // buat history item receipt
                Log::info('buat history penerimaan');
                $this->repository->createWarehouseItemReceiptHistory($itemReceiptId, 'Sukses melakukan penerimaan barang dari produksi', 'Diterima');

                // buat history purchase
                Log::info('buat history purchase');
                $this->repository->createPurchaseHistory($purchase->id, 'Sukses melakukan penerimaan barang dari pembelian', 'Diterima');


            });

        } catch (Exception $exception) {
            Log::error(json_encode([
                "message" => "gagal melakukan penerimaan item masuk dari pembelian",
                "data" => $itemReceiptDTO
            ]));
            report($exception);
            abort(400);
        }


    }

    private function insertStockValuation(string $warehouseId, array $dataItemPurchase, array $itemDetails): ?StockItem
    {


        foreach ($itemDetails as $item) {
            $itemId = $item['item_id'];
            $qty = $item['qty_accept'];


            $totalCost = $this->calculateTotalCost($dataItemPurchase, $itemId, $qty);

            if (empty($totalCost)) {
                report(new Exception('saat menghitung insert stock valuation terdapat kasalahan total cost tidak berhasil didapatkan karena data item purchase tidak valid dengan item penerimaan'));
                abort(400);
            }


            $purchasePrice = $totalCost['last_cost'];

            $warehouseItemId = $this->repository->getWarehouseItemId($warehouseId, $itemId);
            $stockItem = $this->repository->getStockItemByWarehouseItemId($warehouseItemId);

            $cogsValuationCalcData = $this->getCogsValuationCalcData($qty, $purchasePrice, is_null($stockItem), $stockItem);


            if (empty($cogsValuationCalcData)) {
                throw new Exception('Failed to calculate initial avg cost for item receipt from purchasing');
            }

            return $this->repository->insertNewStockItem($warehouseItemId, $cogsValuationCalcData);
        }

        return null;
    }

    private function calculateTotalCost(array $dataItemPurchase, string $itemId, int $qty): array
    {
        foreach ($dataItemPurchase as $itemPurchase) {
            if ($itemPurchase['item_id'] == $itemId) {
                return [
                    'total' => $qty * $itemPurchase['singlePrice'],
                    'last_cost' => $itemPurchase['singlePrice'],
                ];
            }
        }

        return []; // Tidak ada item yang cocok
    }

    private function getCogsValuationCalcData(int $qty, float $purchasePrice, bool $isNewItem, ?StockItem $stockItem): array
    {
        $cogs = new CogsValuationCalc();

        if ($isNewItem) {

            return $cogs->initialAvg($qty, $purchasePrice, $purchasePrice);
        } else {
            return $cogs->calculateAvgPrice(
                $stockItem->inventory_value,
                $stockItem->qty_on_hand,
                $stockItem->avg_cost,
                $qty,
                $purchasePrice,
                false
            );
        }
    }

    private function calculateAvgCost(int $totalCost, int $qty)
    {
        return $totalCost / $qty;
    }


}
