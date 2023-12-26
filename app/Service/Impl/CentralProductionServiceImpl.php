<?php

namespace App\Service\Impl;

use App\Models\CentralProduction;
use App\Models\RequestStock;
use App\Models\RequestStockHistory;
use App\Models\Warehouse;
use App\Models\WarehouseOutbound;
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

            $currentYearMonth = Carbon::now()->format('Ymd');

            $nextCode = 1;
            if ($latestProduction) {
                $latestProductionDate = Carbon::parse($latestProduction->created_at)->format('Ymd');
                if ($latestProductionDate === $currentYearMonth) {
                    $nextCode = $latestProduction->increment + 1;
                }
            }

            $infix = RequestStock::findOrFail($requestStockId)
                ->warehouse->first()
                ->centralKitchen->first()
                ->code;

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

                    $code = $this->genereateCodeItemOut($warehouseId);

                    if (empty($code)) {
                        throw new Exception('gagal mengenarate code item keluar');
                    }


                    $outbound = WarehouseOutbound::create([
                        'warehouses_id' => $warehouseId,
                        'central_productions_id' => $productionId,
                        'code' => $code['code'],
                        'increment' => $code['increment'],
                    ]);

                    $outbound->outboundItem()->createMany($resultMaterial);

                    RequestStockHistory::
                    create([
                        'request_stocks_id' => $requestId,
                        'desc' => 'Membuat permintaan bahan keluar dari gudang ke central kitchen',
                        'status' => 'Membuat permintaan bahan'
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

    /**
     * generate kode item keluar dari gudang
     * @param string $warehouseId
     * @return void
     */
    public function genereateCodeItemOut(string $warehouseId)
    {
        try {

            $latestOutbound = WarehouseOutbound::where('warehouses_id', $warehouseId)->latest()->first();

            $currentYearMonth = Carbon::now()->format('Ymd');

            $nextCode = 1;
            if ($latestOutbound) {
                $latestProductionDate = Carbon::parse($latestOutbound->created_at)->format('Ymd');
                if ($latestProductionDate === $currentYearMonth) {
                    $nextCode = $latestOutbound->increment + 1;
                }
            }

            $warehosue = Warehouse::findOrFail($warehouseId);
            $infix = $warehosue->warehouse_code;

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
}
