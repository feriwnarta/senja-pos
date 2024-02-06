<?php

namespace App\Service\Impl;

use App\Models\CentralProduction;
use App\Models\Purchase;
use App\Models\WarehouseItemReceiptRef;
use App\Repository\Impl\WarehouseItemReceiptRepositoryImpl;
use App\Repository\WarehouseItemReceiptRepository;
use App\Service\WarehouseItemReceiptService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseItemReceiptServiceImpl implements WarehouseItemReceiptService
{

    private WarehouseItemReceiptRepository $receiptRepository;

    /**
     * @param WarehouseItemReceiptRepository $receiptRepository
     */
    public function __construct(WarehouseItemReceiptRepositoryImpl $receiptRepository)
    {
        $this->receiptRepository = $receiptRepository;
    }

    /**
     * Lakukan proses penerimaan barang dan panggil fungsi generate kode terima barang
     * Validasi parameter terlebih dahulu.
     * @param string $itemReceiptId
     * @param array $items
     * @return void
     * @throws Exception
     */
    public function accept(WarehouseItemReceiptRef $reference, string $itemReceiptId, string $warehouseId, string $warehouseCode, array $items): bool
    {
        if (empty($items) || $itemReceiptId == '' || $itemReceiptId == null) {
            throw new Exception('parameter accept receipt tidak valid atau ada yang kosong');
        }

        // panggil fungsi generate code
        try {
            Log::info($reference);

            return Cache::lock('acceptItemReceipt', 10)->block(5, function () use ($itemReceiptId, $warehouseId, $warehouseCode, $items, $reference) {

                // proses penerimaan item receipt melibatkan 2 flow proses
                // penerimana dari produksi atau penerimaan dari pembelian

                if ($reference->receivable instanceof CentralProduction) {
                    return $this->processItemReceiptFromProduction(itemReceiptId: $itemReceiptId, warehouseId: $warehouseId, warehouseCode: $warehouseCode, items: $items);
                }

                if ($reference->receivable instanceof Purchase) {
                    // format data items sebelum diproses oleh purchasing
                    // ini digunakna untuk mengambil harga pembelian
                    $items = $this->formatItemsDataFromPurchasing($reference->receivable->detail);

                    return $this->processItemReceiptFromPurchase(itemReceiptId: $itemReceiptId, warehouseId: $warehouseId, warehouseCode: $warehouseCode, items: $items);
                }

                return false;

            });
        } catch (LockTimeoutException $e) {
            Log::error('Gagal mendapatkan lock:', [
                'message' => $e->getMessage(),
            ]);
            throw new Exception('Lock tidak didapatkan selama 5 detik');
        } catch (Exception $exception) {
            Log::error('Error saat menerima item receipt:', [
                'trace' => $exception->getTraceAsString(),
            ]);
            throw $exception; // Re-throw for further handling
        }

    }

    /**
     * proses penerimaan barang dari produksi
     * @param string $itemReceiptId
     * @param string $warehouseId
     * @param string $warehouseCode
     * @param array $items
     * @return true
     * @throws Exception
     */
    private function processItemReceiptFromProduction(string $itemReceiptId, string $warehouseId, string $warehouseCode, array $items)
    {
        DB::transaction(function () use ($itemReceiptId, $warehouseId, $warehouseCode, $items) {
            $resultGenerateCodeData = $this->generateCodeReceipt($itemReceiptId, $warehouseId, $warehouseCode);

            // update warehouse receipt
            $warehouseReceipt = $this->receiptRepository->findWarehouseItemReceiptById($itemReceiptId);
            $this->receiptRepository->updateCodeDataExistingItemReceipt($itemReceiptId, $resultGenerateCodeData['code'], $resultGenerateCodeData['increment']);

            // Update details
            $this->receiptRepository->createWarehouseItemReceiptDetail($items);

            // update history warehouse receipt
            $this->receiptRepository->creteNewWarehouseItemReceiptHistory($itemReceiptId, 'Menerima penerimaan barang', 'Diterima');

            // TODO: naikan stock dan avg cost
        });

        return true;
    }

    public function generateCodeReceipt(string $itemReceiptId, string $warehouseId, string $warehouseCode)
    {

        $nextCode = 1;
        $warehouseItemReceipt = $this->receiptRepository->findWarehouseItemReceiptById($itemReceiptId);

        // jika sudah tersedia maka balikan kode yang sudah digenerate
        if ($warehouseItemReceipt->code !== null) {
            return [
                'code' => $warehouseItemReceipt->code,
                'increment' => $warehouseItemReceipt->increment,
            ];
        }

        // dapatkan kode terakhir
        $latestCodeData = $this->receiptRepository->getLastCodeDataByWarehouseId($warehouseId);

        // format date time bulan dan tahun ini
        $currentYearMonth = Carbon::now()->format('Ym');

        if (!empty($latestCodeData)) {
            $latestCodeDate = Carbon::parse($latestCodeData['created_at'])->format('Ym');
            if ($latestCodeDate === $currentYearMonth) {
                $nextCode = $latestCodeData['increment'] + 1;
            }
        }

        $code = "RECEIPT{$warehouseCode}{$currentYearMonth}{$nextCode}";

        return [
            'code' => $code,
            'increment' => $nextCode,
        ];
    }

    private function formatItemsDataFromPurchasing(Collection $purchaseDetail): array
    {
        $items = array();

        foreach ($purchaseDetail as $detail) {
            $items[] = [
                'id' => $detail->items_id,
                'qty_accept' => $detail->qty_buy,
                'price' => $detail->single_price,
            ];
        }

        return $items;
    }

    /**
     * proses penerimaan barang dari purchase
     * @param string $itemReceiptId
     * @param string $warehouseId
     * @param string $warehouseCode
     * @param array $items
     * @return true
     * @throws Exception
     */
    private function processItemReceiptFromPurchase(string $itemReceiptId, string $warehouseId, string $warehouseCode, array $items)
    {
        DB::transaction(function () use ($itemReceiptId, $warehouseId, $warehouseCode, $items) {

            $this->receiptRepository->setInventoryValuation($warehouseId, $items);

            $resultGenerateCodeData = $this->generateCodeReceipt($itemReceiptId, $warehouseId, $warehouseCode);

            // update warehouse receipt
            $warehouseReceipt = $this->receiptRepository->findWarehouseItemReceiptById($itemReceiptId);

            // berikan kode dan increment ke item receipt yang sudah ada
            $this->receiptRepository->updateCodeDataExistingItemReceipt($itemReceiptId, $resultGenerateCodeData['code'], $resultGenerateCodeData['increment']);

            // Update details
            $this->receiptRepository->createWarehouseItemReceiptDetail($items);

            // update history warehouse receipt
            $this->receiptRepository->creteNewWarehouseItemReceiptHistory($itemReceiptId, 'Menerima penerimaan barang', 'Diterima');
            
        });

        return true;
    }

    public function reject($itemReceiptRefId): bool
    {
        // lakukan proses reject
        try {
            DB::beginTransaction();

            $warehouseReceipt = $this->receiptRepository->findWarehouseItemReceiptById($itemReceiptRefId);

            $this->receiptRepository->creteNewWarehouseItemReceiptHistory($itemReceiptRefId, 'Permintaan barang ditolak', 'Ditolak');

            // Gunakan 'each' untuk mengiterasi koleksi dan update setiap model
            $warehouseReceipt->details->each(function ($detail) {
                $detail->update(['qty_accept' => 0]);
            });

            Log::info('reject dijalankan');

            DB::commit();

            return true;

        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            Log::error("Gagal menolak item receipt $itemReceiptRefId: Data tidak ditemukan");
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error("Gagal menolak item receipt $itemReceiptRefId");
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

    }

    /**
     * naikan stok item yang diterima, dan hitung inventoy valuasinya
     * @param $qtyAccept
     * @return void
     */
    private function increaseStockAndCalculateValuation($itemId, $qtyAccept)
    {


    }
}
