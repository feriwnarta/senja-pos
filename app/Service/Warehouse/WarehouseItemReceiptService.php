<?php

namespace App\Service\Warehouse;

use App\Contract\Warehouse\WarehouseItemReceiptRepository;
use App\Dto\WarehouseItemReceiptDTO;
use App\Models\CentralProduction;
use App\Models\StockItem;
use App\Models\WarehouseItemReceiptRef;
use App\Service\Impl\CogsValuationCalc;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class WarehouseItemReceiptService implements \App\Contract\Warehouse\WarehouseItemReceiptService
{

    private WarehouseItemReceiptRepository $repository;

    /**
     * @param WarehouseItemReceiptRepository $repository
     */
    public function __construct(WarehouseItemReceiptRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * lakukan proses penerimaan item masuk kegudang
     * penerimaan ini akan melakukan 2 flow masuk item
     * 1. masuk dari pembelian
     * 2. masuk dari produksi
     *+
     *
     * @param WarehouseItemReceiptDTO $itemReceiptDTO
     * @return void
     */
    public function receipt(WarehouseItemReceiptDTO $itemReceiptDTO)
    {
        if (is_null($itemReceiptDTO) && !isset($itemReceiptDTO)) {
            report(new Exception('item receipt dto null'));
            abort(400);
        }


        try {

            DB::transaction(function () use ($itemReceiptDTO) {

                // panggil fungsi yang melakukan pengambilan item receipt id
                // fungsi tersebut mengembalikan item receipt dto baru
                $newDtoWithReceiptId = $this->setItemReceiptId($itemReceiptDTO);
                $model = $this->getWarehouseItemReceiptRef($newDtoWithReceiptId);
                $model = $model->receivable;


                // flow type masuk item berasal dari produksi
                if ($model instanceof CentralProduction) {
                    $this->processProductionAcceptance($model->id, $newDtoWithReceiptId->getId(), $newDtoWithReceiptId->getDataItemReceipt());
                    return;
                }


                // flow type masuk item berasal dari pembelian
                if ($model instanceof Purchase) {
                    return;
                }
            });

        } catch (\Exception $exception) {
            Log::error(json_encode([
                'message' => 'gagal melakukan penerimaan di item masuk gudang',
                'message-exception' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'data' => [
                    'dto' => $itemReceiptDTO
                ]
            ]));
            report($exception);
            abort(400);
        }

    }

    private function setItemReceiptId(WarehouseItemReceiptDTO $itemReceiptDTO): WarehouseItemReceiptDTO
    {
        // ambil item item receipt id terlebih dahulu
        $itemReceiptId = $this->getItemReceiptId($itemReceiptDTO->getWarehouseItemReceiptRefId());
        $itemReceiptDTO->setId($itemReceiptId);
        return $itemReceiptDTO;
    }


    private function getItemReceiptId(string $itemReceiptRefId)
    {
        return $this->repository->getWarehouseItemReceiptIdByRef($itemReceiptRefId);
    }

    /**
     * melakukan pengecekan tipe barang yang akan masuk berasal dari produksi atau pembelian (CentralProduction::class,
     * Purchase::class)
     *
     * @return void
     */
    private function getWarehouseItemReceiptRef(WarehouseItemReceiptDTO $itemReceiptDTO): WarehouseItemReceiptRef
    {
        $itemReceiptRefId = $itemReceiptDTO->getWarehouseItemReceiptRefId();

        // dapatkan model warehouse item receipt ref id dari repository warehouse item receipt repository
        return $this->repository->findWarehouseItemReceiptRefById($itemReceiptRefId);
    }

    /**
     * fungsi ini akan menjalakan flow proses penerimaan gudang yang berasal dari produksi
     *
     * @return void
     */
    private function processProductionAcceptance($productionId, $itemReceiptId, array $itemDetails)
    {
        Log::info('generate kode item receipt');
        $result = $this->generateCode($itemReceiptId);


        if (empty($result)) {
            throw new Exception('hasil generate kode array kosong saat penerimaan barang produksi');
        }

        $code = $result['code'];
        $increment = $result['increment'];

        Log::info('set kode item receipt');
        // set kode dan incerement ke warehouse item receipt yang sudah dibuat
        $isSetCode = $this->repository->setCodeExistingWarehouseItemReceipt($itemReceiptId, $code, $increment);


        if (!$isSetCode) {
            throw new Exception('gagal menyimpan kode ke item receipt yang sudah dibuat');
        }

        Log::info('set jumlah dan item yang diterima ke penerimaan detail');
        // buat detail penerimaan item beserta nilai quantity yang diterimanya
        $isUpdateAmountReceived = $this->repository->updateAmountReceivedExistingDetails($itemDetails);
        if (!$isUpdateAmountReceived) {
            throw new Exception('gagal update Amount Received Existing Details dipenerimaan barang produksi');
        }


        $warehouseId = $this->repository->getWarehouseIdByWarehouseReceipt($itemReceiptId);


        Log::info('insert hasil jadi ke stock valuation');
        // proses melakukan penambahan inventory valuation
        $this->insertStockAndRemaining($productionId, $warehouseId, $itemDetails);


        Log::info('buat history penerimaan');
        $this->repository->createWarehouseItemReceiptHistory($itemReceiptId, 'Sukses melakukan penerimaan barang dari produksi', 'Diterima');


        Log::info('sukses menerima item');
    }

    /**
     * generate kode item masuk
     *
     * @param string $itemReceiptId
     * @return array
     */
    private function generateCode(string $itemReceiptId): array
    {
        // dapatkan kode dan id warehouse
        $warehouseCode = $this->repository->getWarehouseCodeByWarehouseReceipt($itemReceiptId);

        $warehouseId = $this->repository->getWarehouseIdByWarehouseReceipt($itemReceiptId);

        // format bulan dan tahun dan ambil next increment
        $currentMonthYear = $this->formatCurrentMonthYear();

        $nextCode = $this->getNextIncrement($warehouseId, $currentMonthYear);


        $code = "RECEIPT{$warehouseCode}{$currentMonthYear}{$nextCode}";


        return ['code' => $code, 'increment' => $nextCode];
    }

    private function formatCurrentMonthYear(): string
    {
        return Carbon::now()->format('Ym');
    }

    private function getNextIncrement(string $warehouseId, string $currentMonthYear): int
    {
        $latestCodeData = $this->repository->getLastCodeByWarehouse($warehouseId);

        return !empty($latestCodeData) && Carbon::parse($latestCodeData['created_at'])->format('Ym') === $currentMonthYear
            ? $latestCodeData['increment'] + 1
            : 1;
    }

    private function insertStockAndRemaining(string $productionId, string $warehouseId, array $itemDetails)
    {
        $cogs = new CogsValuationCalc();


        foreach ($itemDetails as $item) {
            $itemId = $item['item_id'];
            $qty = $item['qty_accept'];

            $warehouseItemId = $this->repository->getWarehouseItemId($warehouseId, $itemId);

            $remainingData = $this->repository->getProductionRemaining($productionId, $itemId);

            if (!empty($remainingData)) {
                $this->insertRemainingStock($cogs, $warehouseItemId, $remainingData);
            } else {
                $this->insertFinishedStock($cogs, $warehouseItemId, $qty, $productionId);
            }
        }
    }

    private function insertRemainingStock($cogs, $warehouseItemId, $remainingData): StockItem
    {
        $incomingQty = $remainingData['qty'];
        $incomingAvgCost = $remainingData['avg_cost'];
        $oldStock = $this->repository->getStockItemByWarehouseItemId($warehouseItemId);

        $cogsValuationCalcData = $cogs->calculateAvgPrice(
            $oldStock->inventory_value,
            $oldStock->qty_on_hand,
            $oldStock->avg_cost,
            $incomingQty,
            $incomingAvgCost,
            false
        );

        if (empty($cogsValuationCalcData)) {
            throw new Exception('Failed to calculate avg price for remaining items in production');
        }

        return $this->repository->insertNewStockItem($warehouseItemId, $cogsValuationCalcData);
    }

    private function insertFinishedStock($cogs, $warehouseItemId, $qty, $productionId): StockItem
    {
        $totalCost = $this->calculateTotalCost($productionId);
        $stockItem = $this->repository->getStockItemByWarehouseItemId($warehouseItemId);

        if (is_null($stockItem)) {
            $avgCost = $totalCost / $qty;
            $cogsValuationCalcData = $cogs->initialAvg($qty, $avgCost, 0);

            if (empty($cogsValuationCalcData)) {
                throw new Exception('Failed to calculate initial avg cost for production');
            }

            return $this->repository->insertNewStockItem($warehouseItemId, $cogsValuationCalcData);
        } else {
            $avgCost = $totalCost / $qty;
            $cogsValuationCalcData = $cogs->calculateAvgPrice(
                $stockItem->inventory_value,
                $stockItem->qty_on_hand,
                $stockItem->avg_cost,
                $qty,
                $avgCost,
                false
            );

            if (empty($cogsValuationCalcData)) {
                throw new Exception('Failed to calculate initial avg cost for production');
            }

            return $this->repository->insertNewStockItem($warehouseItemId, $cogsValuationCalcData);
        }
    }

    private function calculateTotalCost($productionId)
    {
        $details = $this->repository->getProductionFinishDetailsByProductionId($productionId);
        $totalCost = 0;

        $details->each(function ($finishDetail) use (&$totalCost) {
            $totalCost += ($finishDetail->amount_used * $finishDetail->avg_cost);
        });

        if ($totalCost == 0) {
            throw new Exception('Failed to calculate cost of materials used');
        }

        return $totalCost;
    }


}
