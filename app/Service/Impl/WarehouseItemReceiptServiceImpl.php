<?php

namespace App\Service\Impl;

use App\Models\WarehouseItemReceipt;
use App\Models\WarehouseItemReceiptDetail;
use App\Service\WarehouseItemReceiptService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseItemReceiptServiceImpl implements WarehouseItemReceiptService
{

    /**
     * Lakukan proses penerimaan barang dan panggil fungsi generate kode terima barang
     * Validasi parameter terlebih dahulu.
     * @param string $itemReceiptId
     * @param array $items
     * @return void
     * @throws Exception
     */
    public function accept(string $itemReceiptId, string $warehouseId, string $warehouseCode, array $items): bool
    {
        if (empty($items) || $itemReceiptId == '' || $itemReceiptId == null) {
            throw new Exception('parameter accept receipt tidak valid atau ada yang kosong, seperti dompet anda yang kosong');
        }

        // panggil fungsi generate code
        try {
            return Cache::lock('acceptItemReceipt', 10)->block(5, function () use ($itemReceiptId, $warehouseId, $warehouseCode, $items) {
                DB::beginTransaction();

                try {
                    $code = $this->generateCodeReceipt($itemReceiptId, $warehouseId, $warehouseCode);

                    // update warehouse receipt
                    $warehouseReceipt = WarehouseItemReceipt::find($itemReceiptId);

                    $warehouseReceipt->update([
                        'code' => $code['code'],
                        'increment' => $code['increment'],
                    ]);

                    // Update details
                    foreach ($items as $item) {
                        WarehouseItemReceiptDetail::where('id', $item['id'])->update([
                            'qty_accept' => $item['qty_accept']
                        ]);
                    }

                    // update history warehouse receipt
                    $warehouseReceipt->history()->create([
                        'desc' => 'Menerima penerimaan barang',
                        'status' => 'Diterima',
                    ]);

                    DB::commit();

                    return true;

                } catch (Exception $exception) {
                    DB::rollBack();
                    Log::error('Gagal menerima item receipt', [
                        'message' => $exception->getMessage(),
                        'trace' => $exception->getTraceAsString(),
                    ]);
                    throw $exception; // Re-throw for further handling
                }
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


    public function generateCodeReceipt(string $itemReceiptId, string $warehouseId, string $warehouseCode)
    {
        $warehouseItemReceipt = WarehouseItemReceipt::findOrFail($itemReceiptId);

        // jika sudah tersedia maka lemparkan saja error kode sudah terisi
        if ($warehouseItemReceipt->code !== null) {
            return [
                'code' => $warehouseItemReceipt->code,
                'increment' => $warehouseItemReceipt->increment,
            ];
        }

        // dapatkan kode terakhir
        $latestCode = WarehouseItemReceipt::where('warehouses_id', $warehouseId)
            ->whereNotNull('code')
            ->latest('created_at')
            ->first();


        Log::debug($latestCode);

        $currentYearMonth = Carbon::now()->format('Ym');
        $nextCode = 1;

        if ($latestCode) {
            $latestCodeDate = Carbon::parse($latestCode->created_at)->format('Ym');
            if ($latestCodeDate === $currentYearMonth) {
                $nextCode = $latestCode->increment + 1;
            }
        }

        $code = "RECEIPT{$warehouseCode}{$currentYearMonth}{$nextCode}";

        return [
            'code' => $code,
            'increment' => $nextCode,
        ];
    }
}
