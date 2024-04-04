<?php

namespace App\Service\Impl;

use App\Models\CentralKitchenReceipts;
use App\Models\CentralProduction;
use App\Models\CentralProductionShipping;
use App\Models\RequestStock;
use App\Models\Warehouse;
use App\Models\WarehouseItemReceipt;
use App\Models\WarehouseItemReceiptRef;
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

    public function createProduction(string $requestStockId, string $centralKitchenId, array $dataForProductionFinishes): ?CentralProduction
    {
        try {
            return Cache::lock('createProduction', 10)->block(5, function () use ($requestStockId, $centralKitchenId, $dataForProductionFinishes) {
                DB::beginTransaction();

                try {

                    // cek terlebih dahulu apakah produksi sudah terbuat dengan request stock id yang ada
                    $production = $this->checkProduction($requestStockId);

                    // jika true maka tidak perlu melakukan pembuatan ulang, melainkan melakukan proses penerimaan ulang
                    if (!is_null($production) && $production->exists()) {
//                        RequestStockHistory::create([
//                            'request_stocks_id' => $requestStockId,
//                            'desc' => 'Produksi diterima kembali oleh central kitchen (otomatis)',
//                            'status' => 'Produksi diterima',
//                        ]);

                        // lakukan pembuatan data production finishes
                        Log::info('buat data production finishes');
                        $production->finishes()->delete();
                        $production->finishes()->createMany($dataForProductionFinishes);

                        $production->history()->create([
                            'desc' => 'Melakukan penerimaan ulang saat permintaan stok dibatalkan',
                            'status' => 'Dibuat'
                        ]);

                    } else {
                        $result = $this->generateCode($requestStockId, $centralKitchenId);

                        if ($result && isset($result['code'], $result['increment'])) {
                            $production = CentralProduction::create([
                                'request_stocks_id' => $requestStockId,
                                'central_kitchens_id' => $centralKitchenId,
                                'code' => $result['code'],
                                'increment' => $result['increment'],
                            ]);

                            Log::info('buat data production finishes');
                            $production->finishes()->createMany($dataForProductionFinishes);

                            $production->history()->create([
                                'desc' => 'Membuat produksi bedasarkan request stok warehouse',
                                'status' => 'Dibuat'
                            ]);


//                            RequestStockHistory::create([
//                                'request_stocks_id' => $requestStockId,
//                                'desc' => 'Produksi diterima oleh central kitchen',
//                                'status' => 'Produksi diterima',
//                            ]);
                        }
                    }
                    DB::commit();
                    return $production; // Return the model instance on success
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

    public function checkProduction(string $requestStockId)
    {
        return CentralProduction::where('request_stocks_id', $requestStockId)->first();
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
                        'qty_target' => str_replace('.', '', $recipe['item_component_usage']),
                    ];
                }
                Log::debug($element);
            }

            // simpan production result
            $result = $production->result()->createMany($resultArray);


            Log::debug('TOTAL');
            Log::debug($result);

            // update status produksi
            $production->history()->create([
                'desc' => 'Permintaan bahan berdasarkan resep disimpan',
                'status' => 'Disimpan'
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
     *
     * @param array $materials
     * @return void
     */
    public function requestMaterialToWarehouse(CentralProduction $production, array $materials, string $warehouseId, string $productionId, string $requestId)
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
            return Cache::lock('createItemOut', 10)->block(5, function () use ($production, $materials, $warehouseId, $productionId, $resultMaterial, $requestId) {

                // proses simpan item keluar untuk gudang
                DB::beginTransaction();
                try {

                    $outbound = WarehouseOutbound::create([
                        'warehouses_id' => $warehouseId,
                        'central_productions_id' => $productionId,
                    ]);

                    $outbound->outboundItem()->createMany($resultMaterial);

                    CentralProduction::where('request_stocks_id', $requestId)->first()->history()->create([
                        'desc' => 'Permintaan bahan untuk keperluan produksi dibuat ke gudang',
                        'status' => 'Permintaan Bahan'
                    ]);

//                    RequestStockHistory::
//                    create([
//                        'request_stocks_id' => $requestId,
//                        'desc' => 'Membuat permintaan bahan keluar dari gudang ke central kitchen otomatis dari produksi',
//                        'status' => 'Menunggu pengiriman bahan'
//                    ]);

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
     *
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

            $latestIncrement = WarehouseOutbound::where('warehouses_id', $warehouseId)->max('increment');


            $currentYearMonth = Carbon::now()->format('Ym');

            $nextCode = 1;
            if ($latestOutbound) {
                $latestProductionDate = Carbon::parse($latestOutbound->created_at)->format('Ym');
                if ($latestProductionDate === $currentYearMonth) {
                    $nextCode = $latestIncrement + 1;
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
     *
     * @param array $items
     * @param string $outboundId
     * @return void
     */
    public function processItemReceiptProduction(array $items, string $outboundId, CentralProduction $production)
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
                    'qty_accept' => str_replace('.', '', $item['qty_accept']),
                ];
            }, $items);

            $results = [];

            foreach ($production->outbound->first()->reference->first()->shipping->shippingItem as $shippingItem) {
                foreach ($extractItem as $item) {
                    $itemId = $item['items_id'];
                    $stockItem = $shippingItem->stockItem;
                    $warehouseItem = $stockItem->warehouseItem->items->id;

                    if (!is_null($warehouseItem) && $warehouseItem == $itemId) {


                        // Jika item belum ada, tambahkan ke hasil
                        $results[] = [
                            'items_id' => $itemId,
                            'qty_on_hand' => str_replace('.', '', $item['qty_accept']),
                            'avg_cost' => $stockItem->avg_cost,
                            'last_cost' => $stockItem->last_cost,
                        ];
                    }
                }
            }

            $production->components()->createMany($results);
            $outbound = CentralKitchenReceipts::create([
                'warehouse_outbounds_id' => $outboundId,
            ]);

            $output = $outbound->detail()->createMany($extractItem);

            // update history request stock
            $outbound->outbound->history()->create([
                'desc' => 'Bahan diterima dan divalidasi oleh central kitchen',
                'status' => 'Bahan diterima'
            ]);

            $outbound->outbound->production->history()->create([
                'desc' => 'Bahan diterima dan divalidasi oleh central kitchen (otomatis)',
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


        DB::beginTransaction();

        $production = CentralProduction::findOrFail($productionId);
        // lakukan pengecekan apakah ada bahan tambahan yang sedang diproses

        Log::debug('cek production adiitional request');
        $productionAdditionRequest = $production->additionRequest;
        if ($productionAdditionRequest->isNotEmpty()) {
            $productionAdditionRequest->each(function ($addition) {
                if ($addition->amount_received == 0) {
                    throw new Exception('User melakukan produksi selesai akan tetapi bahan tambahan belum diterima', 100);
                }
            });
        }


        Log::debug('proses finish production');
        
        $production->update([
            'note' => $note
        ]);

        // update history
        $production->history()->create([
            'desc' => 'Central kitchen menyelesaikan proses produksi dan dalam tahap finishing (otomatis)',
            'status' => 'Penyelesaian',
        ]);


        DB::commit();
        return true;


    }

    public function createProductionShipping(string $productionId, string $centralKitchenId, string $centralKitchenCode)
    {

        try {
            return Cache::lock('createProductionShipping', 10)->block(5, function () use ($productionId, $centralKitchenId, $centralKitchenCode) {

                try {
                    $result = $this->generateCodeProductionShipping($productionId, $centralKitchenId, $centralKitchenCode);

                    if ($result && isset($result['code'], $result['increment'])) {
                        // buat shipping
                        CentralProductionShipping::create([
                            'central_productions_id' => $productionId,
                            'central_kitchens_id' => $centralKitchenId,
                            'code' => $result['code'],
                            'increment' => $result['increment'],
                            'description' => 'Membuat pengiriman hasil produksi',
                        ]);
                    } else {
                        throw new Exception('gagal membuat production shipping');
                    }
                } catch (Exception $exception) {

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

    public function createItemReceipt(string $warehouseId, array $items, CentralProduction $centralProduction)
    {

        if (empty($items)) {
            throw new Exception('parameter items array kosong');
        }

        if (!isset($centralProduction) || $centralProduction == null) {
            throw new Exception('parameter central production model kosong atau null');
        }

        $itemReceiptDetail = [];

        foreach ($centralProduction->ending as $ending) {
            $itemReceiptDetail[] = [
                'items_id' => $ending->target_items_id,
                'qty_send' => $ending->qty
            ];
        }


        // Simpan referensi terlebih dahulu ke dalam WarehouseItemReceiptRef
        $itemReceiptRef = $centralProduction->reference()->save(new WarehouseItemReceiptRef());

        // buat item receipt
        $warehouseReceipt = WarehouseItemReceipt::create([
            'warehouses_id' => $warehouseId,
            'warehouse_item_receipt_refs_id' => $itemReceiptRef->id,
        ]);

        // buat history item receipt
        $warehouseReceipt->history()->create([
            'desc' => 'Membuat draft penerimaan barang dari produksi',
            'status' => 'Draft',
        ]);

        // Simpan detail-item penerimaan barang
        $warehouseReceipt->details()->createMany($itemReceiptDetail);
    }


    public function getSaveComponent(CentralProduction $production)
    {
        if (is_null($production)) {
            throw new Exception('Central production null saat mengambil component produksi yang disimpan');
        }

        $resultComponentSave = [];

        foreach ($production->result as $productionResult) {
            $targetItemId = $productionResult->targetItem->id;

            if (!isset($resultComponentSave[$targetItemId])) {
                $resultComponentSave[$targetItemId] = [
                    'target_item_id' => $productionResult->targetItem->id,
                    'target_item_name' => $productionResult->targetItem->name,
                    'ingredients' => [],
                ];
            }

            $resultComponentSave[$targetItemId]['ingredients'][] = [
                'id' => $productionResult->id,
                'item_id' => $productionResult->items_id,
                'item_name' => $productionResult->item->name,
                'qty' => number_format($productionResult->qty_target, 0, '', ''),
                'unit' => $productionResult->item->unit->name,
            ];
        }


        $resultComponentSave = array_values($resultComponentSave);


        return $resultComponentSave;
    }

    public function saveEditComponent(CentralProduction $centralProduction, array $components)
    {
        DB::transaction(function () use ($components, $centralProduction) {

            foreach ($components as $component) {
                foreach ($component['ingredients'] as $ingredient) {
                    // Perbarui informasi komponen produksi
                    $model = $centralProduction->result->where('id', $ingredient['id'])->first(); // Gunakan first() karena mungkin model tidak ditemukan
                    if ($model) {
                        $model->qty_target = str_replace('.', '', $ingredient['qty']);
                        $model->save();
                    }
                }
            }

        });


    }

    public function cancelCreateProduction(CentralProduction $centralProduction)
    {
        DB::transaction(function () use ($centralProduction) {
            $centralProduction->requestStock->requestStockHistory()->create([
                'desc' => 'Penerimaan produksi dibatalkan oleh central kitchen',
                'status' => 'Penerimaan dibatalkan'
            ]);

            $centralProduction->history()->create([
                'desc' => 'Penerimaan produksi dibatalkan',
                'status' => 'Penerimaan dibatalkan'
            ]);

            // TODO: perbaiki penghapusan central production

//            $centralProduction->delete();
        });

    }
}
