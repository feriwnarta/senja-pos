<?php

namespace App\Service\Impl;

use App\Models\CentralKitchenReceipts;
use App\Models\CentralProduction;
use App\Models\CentralProductionResult;
use App\Models\CentralProductionShipping;
use App\Models\RequestStock;
use App\Models\RequestStockHistory;
use App\Models\Warehouse;
use App\Models\WarehouseOutbound;
use App\Models\WarehouseOutboundHistory;
use App\Service\CentralProductionService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CentralProductionServiceImpl implements CentralProductionService
{

    public function createProduction(string $requestStockId, string $centralKitchenId): ?CentralProduction
    {
        try {
            return Cache::lock('createProduction', 10)->block(5, function () use ($requestStockId, $centralKitchenId) {
                DB::beginTransaction();

                try {
                    $result = $this->generateCode($requestStockId, $centralKitchenId);

                    if ($result && isset($result['code'], $result['increment'])) {
                        $production = CentralProduction::create([
                            'request_stocks_id' => $requestStockId,
                            'central_kitchens_id' => $centralKitchenId,
                            'code' => $result['code'],
                            'increment' => $result['increment'],
                        ]);


                        RequestStockHistory::create([
                            'request_stocks_id' => $requestStockId,
                            'desc' => 'Produksi diterima',
                            'status' => 'Produksi diterima',
                        ]);

                        DB::commit();
                        return $production; // Return the model instance on success
                    } else {
                        return null;
                    }
                } catch (Exception $exception) {
                    DB::rollBack();
                    Log::error('Gagal menyimpan item detail:', [
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
            Log::error('Error saat membuat produksi:', [
                'trace' => $exception->getTraceAsString(),
            ]);
            throw $exception; // Re-throw for further handling
        }


    }


    public function generateCode(string $requestStockId, string $centralKitchenId): array
    {
        try {
            $latestProduction = CentralProduction::where('central_kitchens_id', $centralKitchenId)->latest()->first();

            $currentYearMonth = Carbon::now()->format('Ym');

            $nextCode = 1;
            if ($latestProduction) {
                $latestProductionDate = Carbon::parse($latestProduction->created_at)->format('Ym');
                if ($latestProductionDate === $currentYearMonth) {
                    $nextCode = $latestProduction->increment + 1;
                }
            }

            $infix = RequestStock::findOrFail($requestStockId)
                ->warehouse->first()
                ->centralKitchen->first()
                ->code;

            $currentYearMonth = Carbon::now()->format('Ymd');

            $code = "PRD{$infix}{$currentYearMonth}{$nextCode}";

            return [
                'code' => $code,
                'increment' => $nextCode,
            ];
        } catch (Exception $exception) {
            Log::error('Gagal generate code central production');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }

    public function saveComponent(string $productionId, array $component)
    {
        try {
            DB::beginTransaction();

            $production = CentralProduction::findOrFail($productionId);
            $resultArray = [];

            foreach ($component as $element) {
                foreach ($element['recipe'] as $recipe) {
                    $resultArray[] = [
                        'target_items_id' => $element['item']['id'],
                        'central_productions_id' => $productionId,
                        'items_id' => $recipe['item_component_id'],
                        'qty_target' => $recipe['item_component_usage'],
                    ];
                }

                Log::debug($element);
            }

            // simpan production result
            $production->result()->createMany($resultArray);

            // update status request stock

            $production->requestStock->requestStockHistory()->createMany([
                [
                    'desc' => 'Komponen untuk produksi disimpan',
                    'status' => 'Komponen produksi disimpan'
                ],

            ]);

            DB::commit();
            return true;


        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('gagal menyimpan komponen produksi');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }

    /**
     * fungsi ini digunakan untuk menyimpan permintaan bahan yang dibutuhkan untuk produksi
     * dari central kitchen ke gudang
     * @param array $materials
     * @return void
     */
    public function requestMaterialToWarehouse(array $materials, string $warehouseId, string $productionId, string $requestId)
    {

        try {

            if (empty($materials)) {
                throw new Exception('material kosong');
            }

            // lakukan proses pengambilan item yang dibutuhkan untuk request ke gudang
            // dan juga lakukan proses jika item id dan item name nya lebih dari 2
            // maka gabungkan nilai qty untuk dijadikan satu
            $resultMaterial = $this->joinSameItemRequestMaterial($materials);

            if (empty($resultMaterial)) {
                throw new Exception('extract item gagal');
            }


            // atomic lock
            return Cache::lock('createItemOut', 10)->block(5, function () use ($materials, $warehouseId, $productionId, $resultMaterial, $requestId) {

                // proses simpan item keluar untuk gudang
                DB::beginTransaction();
                try {

                    // generate code

//                    $code = $this->genereateCodeItemOut($warehouseId);
//
//                    if (empty($code)) {
//                        throw new Exception('gagal mengenarate code item keluar');
//                    }


                    $outbound = WarehouseOutbound::create([
                        'warehouses_id' => $warehouseId,
                        'central_productions_id' => $productionId,
                    ]);

                    $outbound->outboundItem()->createMany($resultMaterial);

                    RequestStockHistory::
                    create([
                        'request_stocks_id' => $requestId,
                        'desc' => 'Membuat permintaan bahan keluar dari gudang ke central kitchen',
                        'status' => 'Membuat permintaan bahan'
                    ]);

                    WarehouseOutboundHistory::create([
                        'warehouse_outbounds_id' => $outbound->id,
                        'desc' => 'Permintaan bahan dibuat dari central kitchen',
                        'status' => 'Baru'
                    ]);


                    DB::commit();
                    return $outbound;

                } catch (Exception $exception) {
                    DB::rollBack();
                    Log::error('gagal membuat item keluar');
                    Log::error($exception->getMessage());
                    Log::error($exception->getTraceAsString());
                    throw $exception; // Re-throw for further handling
                }
            });

        } catch (LockTimeoutException $timeoutException) {
            Log::error('lock tidak didapatkan');
            Log::error($timeoutException->getMessage());
            Log::error($timeoutException->getTraceAsString());
            throw  $timeoutException;

        } catch (Exception $exception) {
            Log::error('gagal menyimpan permintaan bahan dari central kitchen ke gudang');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw $exception;
        }


    }

    public function joinSameItemRequestMaterial(array $materials)
    {
        $mergedComponents = [];

        // Loop melalui data untuk menggabungkan nilai target_qty
        foreach ($materials as $item) {
            foreach ($item['components'] as $component) {
                $key = $component['id'];

                if (!isset($mergedComponents[$key])) {
                    // Jika belum ada data untuk id tersebut, tambahkan ke array
                    $mergedComponents[$key] = [
                        'items_id' => $component['id'],
                        'qty' => $component['target_qty'],
                    ];
                } else {
                    // Jika sudah ada, tambahkan nilai target_qty
                    $mergedComponents[$key]['qty'] += $component['target_qty'];
                }
            }
        }

        Log::info(array_values($mergedComponents));

        return array_values($mergedComponents);
    }

    public function saveCodeItemOut(string $outboundId, string $warehouseId)
    {
        try {
            // Atomic lock
            return Cache::lock('updateCodeItemOut', 10)->block(5, function () use ($outboundId, $warehouseId) {
                try {
                    // Memulai transaksi database
                    DB::beginTransaction();

                    // Menghasilkan kode item keluar
                    $code = $this->genereateCodeItemOut($warehouseId);

                    Log::debug($code);

                    if (empty($code)) {
                        throw new Exception('gagal generate code');
                    }

                    // Menemukan dan mengupdate data WarehouseOutbound berdasarkan central_productions_id
                    $outbound = WarehouseOutbound::findOrFail($outboundId)
                        ->update([
                            'code' => $code['code'],
                            'increment' => $code['increment']
                        ]);


                    // update history produksi
                    WarehouseOutboundHistory::create([
                        'warehouse_outbounds_id' => $outboundId,
                        'desc' => 'Permintaan produksi diterima',
                        'status' => 'Permintaan diterima',
                    ]);


                    DB::commit();

                    return $outbound;
                } catch (Exception $exception) {
                    // Rollback transaksi jika terjadi kesalahan
                    DB::rollBack();

                    Log::error('Gagal mengupdate item keluar');
                    Log::error($exception->getMessage());
                    Log::error($exception->getTraceAsString());

                    // Melempar kembali untuk penanganan lebih lanjut
                    throw $exception;
                }
            });
        } catch (LockTimeoutException $timeoutException) {
            Log::error('Lock tidak didapatkan');
            Log::error($timeoutException->getMessage());
            Log::error($timeoutException->getTraceAsString());

            throw $timeoutException;
        } catch (Exception $exception) {
            Log::error('Gagal mengupdate code warehouse item keluar');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());

            throw $exception;
        }
    }


    /**
     * generate kode item keluar dari gudang
     * @param string $warehouseId
     * @return void
     */
    public function genereateCodeItemOut(string $warehouseId)
    {
        try {

            $latestOutbound = WarehouseOutbound::where('warehouses_id', $warehouseId)
                ->whereNotNull('code')
                ->latest()
                ->first();


            $currentYearMonth = Carbon::now()->format('Ym');

            $nextCode = 1;
            if ($latestOutbound) {
                $latestProductionDate = Carbon::parse($latestOutbound->created_at)->format('Ym');
                if ($latestProductionDate === $currentYearMonth) {
                    $nextCode = $latestOutbound->increment + 1;
                }
            }

            $warehosue = Warehouse::findOrFail($warehouseId);
            $infix = $warehosue->warehouse_code;

            $currentYearMonth = Carbon::now()->format('Ymd');

            $code = "ITEMOUT{$infix}{$currentYearMonth}{$nextCode}";

            return [
                'code' => $code,
                'increment' => $nextCode,
            ];

        } catch (Exception $exception) {
            Log::error('gagal menggenarete kode item keluar dari gudang');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }

    /**
     * proses menyimpan penerimaan item yang dikirim dari gudang ke central kitchen
     * @param array $items
     * @param string $outboundId
     * @return void
     */
    public function processItemReceiptProduction(array $items, string $outboundId)
    {
        try {

            if (empty($items)) {
                throw new Exception('item kosong');
            }

            Log::debug($items);

            DB::beginTransaction();
            $extractItem = array_map(function ($item) {
                return [
                    'items_id' => $item['item_id'],
                    'qty_accept' => $item['qty_accept'],
                ];
            }, $items);

            $outbound = CentralKitchenReceipts::create([
                'warehouse_outbounds_id' => $outboundId,
            ]);

            $outbound->detail()->createMany($extractItem);

            // update history request stock
            $outbound->outbound->history()->create([
                'desc' => 'Bahan diterima dan divalidasi oleh central kitchen',
                'status' => 'Bahan diterima'
            ]);

            $outbound->outbound->production->requestStock->requestStockHistory()->create([
                'desc' => 'Bahan diterima dan divalidasi oleh central kitchen',
                'status' => 'Bahan diterima',
            ]);

            DB::commit();
            return $outbound;

        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('gagal menyimpan item receipt');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw $exception;

        }

    }

    public function finishProduction(array $items, string $productionId, string $note)
    {
        try {

            DB::beginTransaction();

            if (empty($items)) {
                throw new Exception('items kosong');
            }

            Log::debug('finish production');

            // Kumpulkan semua ID yang diperlukan
            $resultIds = array_column($items, 'result_id');

            // Ambil semua data sekaligus untuk menghindari N+1
            $results = CentralProductionResult::findMany($resultIds);

            // Loop melalui data dan update
            foreach ($items as $item) {
                $centralProductionResultId = $item['result_id'];

                // Temukan hasil yang sesuai dari $results
                $result = $results->where('id', $centralProductionResultId)->first();

                // update target quantity
                if ($result) {
                    $result->update([
                        'qty_result' => $item['result_qty'],
                    ]);
                }
            }

            $production = CentralProduction::findOrFail($productionId);
            $production->update([
                'note' => $note
            ]);

            // update history
            $production->requestStock->requestStockHistory()->create([
                'desc' => 'Central kitchen menyelesaikan proses produksi',
                'status' => 'Produksi selesai',
            ]);

            // buat pengiriman
            $this->createProductionShipping($productionId, $production->centralKitchen->id, $production->centralKitchen->code);

            DB::commit();
            return $results;

        } catch (Exception $exception) {
            DB::rollBack();
            Log::error("gagal menyelesai proses produksi dengan id $productionId");
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }
    }

    private function createProductionShipping(string $productionId, string $centralKitchenId, string $centralKitchenCode)
    {

        try {
            return Cache::lock('createProductionShipping', 10)->block(5, function () use ($productionId, $centralKitchenId, $centralKitchenCode) {
                DB::beginTransaction();

                try {
                    $result = $this->generateCodeProductionShipping($productionId, $centralKitchenId, $centralKitchenCode);

                    if ($result && isset($result['code'], $result['increment'])) {
                        // buat shipping

                        $result = CentralProductionShipping::create([
                            'central_productions_id' => $productionId,
                            'central_kitchens_id' => $centralKitchenId,
                            'code' => $result['code'],
                            'increment' => $result['increment'],
                            'description' => 'Membuat pengiriman hasil produksi',
                        ]);

                        DB::commit();

                        return $result;
                    } else {
                        throw new Exception('gagal membuat production shipping');
                    }
                } catch (Exception $exception) {
                    DB::rollBack();
                    Log::error('Gagal menyimpan item detail:', [
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
            Log::error('Error saat membuat shipping produksi:', [
                'trace' => $exception->getTraceAsString(),
            ]);
            throw $exception; // Re-throw for further handling
        }

    }

    public function generateCodeProductionShipping(string $productionId, string $centralKitchenId, string $centralKitchenCode)
    {
        $latest = CentralProductionShipping::where('central_kitchens_id', $centralKitchenId)->latest()->first();

        $currentYearMonth = Carbon::now()->format('Ym');

        $nextCode = 1;
        if ($latest) {
            $latestShippingDate = Carbon::parse($latest->created_at)->format('Ym');
            if ($latestShippingDate === $currentYearMonth) {
                $nextCode = $latest->increment + 1;
            }
        }


        $currentYearMonth = Carbon::now()->format('Ymd');

        $code = "CENTRALSHIPPING{$centralKitchenCode}{$currentYearMonth}{$nextCode}";

        return [
            'code' => $code,
            'increment' => $nextCode,
        ];
    }
}
