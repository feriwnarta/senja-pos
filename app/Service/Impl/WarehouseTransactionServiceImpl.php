<?php

namespace App\Service\Impl;

use App\Models\RequestStock;
use App\Models\Warehouse;
use App\Service\WarehouseTransactionService;
use Exception;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseTransactionServiceImpl implements WarehouseTransactionService
{

    public function createRequest(bool $isOutlet, string $id): RequestStock
    {
        // Jika central kitchen
        if (!$isOutlet) {
            try {
                return Cache::lock('createRequestWarehouse', 10)->block(5, function () use ($isOutlet, $id) {

                    Log::debug('Mulai lock');
                    
                    try {
                        DB::beginTransaction();
                        // Generate code
                        $code = $this->generateCodeRequest($isOutlet, $id);

                        if (empty($code)) {
                            throw new Exception('Gagal generate code');
                        }

                        Log::debug($code);

                        // Buatkan request stock
                        $result = RequestStock::create([
                            'warehouses_id' => $id,
                            'code' => $code['code'],
                            'increment' => $code['increment'],
                        ]);

                        DB::commit();

                        return $result;
                    } catch (Exception $innerException) {
                        DB::rollBack();
                        Log::error('Exception dalam callback: ' . $innerException->getMessage());
                        throw $innerException;
                    }
                });
            } catch (LockTimeoutException $e) {
                // Handle jika lock tidak dapat diperoleh dalam 5 detik
                // Misalnya, log pesan atau lakukan tindakan tertentu
                Log::error('Lock tidak didapatkan selama 5 detik: ' . $e->getMessage());
                // Atau throw kembali exception atau lakukan tindakan sesuai kebutuhan
                throw new Exception('Lock tidak didapatkan selama 5 detik');
            }
        }

        // TODO: PROSES UNTUK OUTLET
    }

    public function generateCodeRequest(bool $isOutlet, string $id): ?array
    {
        try {
            // generate code request untuk central kitchen central kitchen
            if (!$isOutlet) {
                $prefix = 'STCKREQ';
                $warehouse = Warehouse::findOrFail($id);
                $warehouseCode = $warehouse->warehouse_code;
                $year = date('Ymd');
                $nextCode = 1;

                // dapatkan data increment code selanjutnya
                $latestRequest = RequestStock::where('warehouses_id', $warehouse->id)->latest()->first();
                if ($latestRequest != null) {
                    $nextCode = ++$latestRequest->increment;
                }

                return [
                    'code' => "$prefix$warehouseCode$year$nextCode",
                    'increment' => $nextCode
                ];
            }
        } catch (Exception $exception) {
            Log::error('gagal generate code request');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            return [];
        }

    }
}
