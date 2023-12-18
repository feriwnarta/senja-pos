<?php

namespace App\Service\Impl;

use App\Models\Item;
use App\Models\RequestStock;
use App\Models\RequestStockDetail;
use App\Models\RequestStockHistory;
use App\Models\Warehouse;
use App\Service\WarehouseTransactionService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseTransactionServiceImpl implements WarehouseTransactionService
{

    public function createRequest(bool $isOutlet, string $id, string $note = null): RequestStock
    {
        // Jika central kitchen
        if (!$isOutlet) {
            try {
                return Cache::lock('createRequestWarehouse', 10)->block(5, function () use ($isOutlet, $id, $note) {

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
                            'note' => $note,
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
                if ($latestRequest !== null) {
                    $latestDate = Carbon::parse($latestRequest->created_at);

                    // Cek apakah bulan saat ini berbeda dengan bulan dari waktu terakhir diambil
                    if ($latestDate->format('Y-m') !== Carbon::now()->format('Y-m')) {
                        // Bulan berbeda, atur $nextCode kembali ke nol
                    } else {
                        // Bulan sama, increment $nextCode
                        $nextCode = ++$latestRequest->increment;
                    }
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

    /**
     * simpan item req yang dipilih ke items detail
     * pilah mana item yang dibeli mana item yang diproduksi
     * TODO: Proses untuk gudang pusat atau permintaan dari outlet
     * @param string $reqId
     * @param array $itemReq
     * @return void
     */
    public function finishRequest(string $reqId, array $itemReq): string
    {
        if (empty($reqId) || empty($itemReq)) {
            throw new Exception('Parameter kosong');
        }

        try {
            return Cache::lock('createRequestWarehouseFinish', 10)->block(5, function () use ($reqId, $itemReq) {
                DB::beginTransaction();

                try {
                    foreach ($itemReq as $item) {
                        $resultItem = Item::findOrFail($item['id']);

                        RequestStockDetail::create([
                            'request_stocks_id' => $reqId,
                            'items_id' => $item['id'],
                            'qty' => $item['itemReq'],
                            'type' => ($resultItem->route == 'BUY') ? 'PO' : (($resultItem->route == 'PRODUCECENTRALKITCHEN') ? 'PRODUCE' : 'ERROR'),
                        ]);


                        Log::info('Permintaan stok dibuat dengan nomor request ' . $reqId);
                    }

                    RequestStockHistory::create([
                        'request_stocks_id' => $reqId,
                        'desc' => 'Permintaan stok dibuat',
                        'status' => 'Baru',
                    ]);

                    DB::commit();
                    return 'success';
                } catch (Exception $exception) {
                    DB::rollBack();
                    Log::error($exception->getMessage());
                    Log::error($exception->getTraceAsString());
                    throw new Exception('Gagal menyimpan item detail');
                }
            });
        } catch (LockTimeoutException $e) {
            // Handle jika lock tidak dapat diperoleh dalam 5 detik
            Log::error('Lock tidak didapatkan selama 5 detik: ' . $e->getMessage());
            // Atau throw kembali exception atau lakukan tindakan sesuai kebutuhan
            throw new Exception('Lock tidak didapatkan selama 5 detik');
        }
    }
}
