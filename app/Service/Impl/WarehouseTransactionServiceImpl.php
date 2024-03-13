<?php

namespace App\Service\Impl;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\PurchaseRequestHistory;
use App\Models\PurchaseRequestRef;
use App\Models\RequestStock;
use App\Models\RequestStockDetail;
use App\Models\RequestStockHistory;
use App\Models\StockItem;
use App\Models\Warehouse;
use App\Models\WarehouseItem;
use App\Models\WarehouseOutbound;
use App\Models\WarehouseOutboundHistory;
use App\Models\WarehouseOutboundItem;
use App\Models\WarehouseShipping;
use App\Models\WarehouseShippingItem;
use App\Models\WarehouseShippingRef;
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
                    // buat purchase request untuk item yang bertipe po

                    $itemRouteBuy = [];

                    foreach ($itemReq as $item) {
                        $resultItem = WarehouseItem::findOrFail($item['id'])->items;

                        // buat request stock detail
                        RequestStockDetail::create([
                            'request_stocks_id' => $reqId,
                            'items_id' => $resultItem->id,
                            'qty' => $item['itemReq'],
                            'type' => ($resultItem->route == 'BUY') ? 'PO' : (($resultItem->route == 'PRODUCECENTRALKITCHEN') ? 'PRODUCE' : 'ERROR'),
                        ]);

                        if ($resultItem->route == 'BUY') {
                            $itemRouteBuy[] = [
                                'items_id' => $resultItem->id,
                                'qty_buy' => $item['itemReq'],
                            ];
                        }

                        Log::info('Permintaan stok dibuat dengan nomor request ' . $reqId);
                    }

                    Log::info('buat request stock history');
                    RequestStockHistory::create([
                        'request_stocks_id' => $reqId,
                        'desc' => 'Permintaan stok dibuat',
                        'status' => 'Baru',
                    ]);

                    Log::info('Buat purchase order jika ada item yang ber-route buy');
                    // buat PO
                    if (!empty($itemRouteBuy)) {

                        // buat request po referensi dari request stock gudang
                        $requestStock = RequestStock::findOrFail($reqId);
                        $purchaseReqRef = $requestStock->reference()->save(new PurchaseRequestRef());

                        // buat purchase request
                        $purchaseReq = PurchaseRequest::create([
                            'purchase_request_refs_id' => $purchaseReqRef->id
                        ]);

                        // buat purchase request history
                        PurchaseRequestHistory::create([
                            'purchase_requests_id' => $purchaseReq->id,
                            'desc' => 'Membuat permintaan pembelian dari request stock',
                            'status' => 'Permintaan baru',
                        ]);

                        // buat purchase detail item
                        foreach ($itemRouteBuy as $item) {
                            PurchaseRequestDetail::create([
                                'purchase_requests_id' => $purchaseReq->id,
                                'items_id' => $item['items_id'],
                                'qty_buy' => $item['qty_buy']
                            ]);
                        }


                    }

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


    /**
     * lakukan proses pengurangan stock berdasarkan item yang ditentukan
     * @param string $itemId
     * @return array
     */
    public function reduceStockItemShipping(array $items, string $outboundId): ?StockItem
    {
        try {
            return Cache::lock('reduceStock', 10)->block(5, function () use ($items, $outboundId) {
                DB::beginTransaction();

                try {

                    if (empty($items)) {
                        throw new Exception('Items kosong');
                    }

                    $cogsCalc = app()->make(CogsValuationCalc::class);
                    Log::info('proses potong stock warehouse shipping');
                    Log::debug($items);

                    $stockIds = [];
                    $addedItemIds = [];
                    $warehouseId = null;
                    $warehouseCode = null;

                    // dapatkan warehouseId
                    $warehouseId = WarehouseOutbound::findOrFail($outboundId)->warehouses_id;

                    // lakukan iterasi untuk mengurangi stock item valuation
                    foreach ($items as $item) {

                        Log::debug('transaction service item');
                        Log::debug($item);

                        $id = $item['item_id'];

                        Log::debug($item);

                        if (!in_array($id, $addedItemIds)) {

                            $addedItemIds[] = $id;

                            // cari warehouse item
                            $warehouseItem = WarehouseItem::with('stockItem')
                                ->whereHas('warehouse', function ($query) use ($warehouseId) {
                                    $query->where('id', $warehouseId);
                                })
                                ->where('items_id', $id)
                                ->firstOrFail();

                            $req = $warehouseItem->stockItem->last();


                            // dapatkan data terakhir inventory valuation stock item
                            $inventoryValue = $req['inventory_value'];
                            $oldQty = $req['qty_on_hand'];
                            $oldAvg = $req['avg_cost'];
                            $incomingQty = str_replace('.', '', $item['qty_send']);
                            $purchasePrice = $req['avg_cost'];


                            $result = $cogsCalc->calculateAvgPrice($inventoryValue, $oldQty, $oldAvg, $incomingQty, $purchasePrice, true);


                            if (empty($result)) {
                                throw new Exception('Gagal menghitung nilai inventory valuation');
                            }


                            // update inventory valuation
                            $stock = StockItem::create([
                                'warehouse_items_id' => $warehouseItem->id,
                                'incoming_qty' => $result['incoming_qty'],
                                'incoming_value' => $result['incoming_value'],
                                'price_diff' => $result['price_diff'],
                                'inventory_value' => $result['inventory_value'],
                                'qty_on_hand' => $result['qty_on_hand'],
                                'avg_cost' => $result['avg_cost'],
                                'last_cost' => $result['last_cost'],
                            ]);

                            $stockIds[] = $stock->id;


                            // update warehouse outbound items send
                            WarehouseOutboundItem::where('warehouse_outbounds_id', $item['outboundId'])
                                ->where('items_id', $id)
                                ->update([
                                    'qty_send' => str_replace('.', '', ($item['qty_send'] < 0) ? $item['qty_send'] * -1 : $item['qty_send']),
                                ]);


                            // update history outbound
                            WarehouseOutboundHistory::create([
                                'warehouse_outbounds_id' => $item['outboundId'],
                                'desc' => 'Permintaan stock selesai, dan dipindahkan keproses pengiriman',
                                'status' => 'Bahan dikirim'
                            ]);

                            $outbound = WarehouseOutbound::findOrFail($item['outboundId']);

                            if ($outbound == null) {
                                throw new Exception('ada sesuatu yang salah saat berusaha mendapatkan data outbound');
                            }

                            $warehouseId = $outbound->warehouse->id;
                            $warehouseCode = $outbound->warehouse->warehouse_code;
                        }

                    }


                    $this->createShipping($outboundId, $stockIds, $warehouseId, $warehouseCode);

                    DB::commit();
                } catch (Exception $exception) {
                    DB::rollBack();
                    Log::error('Gagal mengurangi stock gudang:', [
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
            Log::error('Error saat mengurangi stock gudang:', [
                'trace' => $exception->getTraceAsString(),
            ]);
            throw $exception; // Re-throw for further handling
        }
    }

    /**
     * buat pengiriman barang dari gudang
     * generate kode pengiriman yang unique
     * @return void
     */
    private function createShipping(string $outboundId, array $stockIds, string $warehouseId, string $warehouseCode)
    {

        // panggil fungsi generate code
        $result = $this->generateCodeShipping($warehouseId, $warehouseCode);


        if ($result == null || empty($result)) {
            throw new Exception('gagal meng-generate code warehouse shipping');
        }

        // cari outbound id

        $shipRef = WarehouseOutbound::findOrFail($outboundId)->reference()->save(new WarehouseShippingRef());


        $warehouseShipping = WarehouseShipping::create([
            'warehouses_id' => $warehouseId,
            'warehouse_shipping_refs_id' => $shipRef->id,
            'description' => 'Proses pemotongan stock',
            'increment' => $result['increment'],
            'code' => $result['code'],
            'description' => 'Membuat pengiriman dari produksi',
        ]);

        foreach ($stockIds as $stockId) {
            Log::debug($stockId);
            WarehouseShippingItem::create([
                'warehouse_shippings_id' => $warehouseShipping->id,
                'stock_items_id' => $stockId,
            ]);
        }


    }

    /**
     * generate kode warehouse shipping
     * pastikan fungsi ini dipanggil didalam atomic lock
     * @return void
     */
    public function generateCodeShipping(string $warehouseId, string $warehouseCode): array
    {

        $warehouseShipping = WarehouseShipping::where('warehouses_id', $warehouseId)
            ->latest()->first();


        $currentYearMonth = Carbon::now()->format('Ym');
        $nextCode = 1;

        if ($warehouseShipping) {
            $latestDate = Carbon::parse($warehouseShipping->created_at)->format('Ym');
            if ($latestDate === $currentYearMonth) {
                $nextCode = $warehouseShipping->increment + 1;
            }
        }

        $code = "WAREHOUSESHIPPING{$warehouseCode}{$currentYearMonth}{$nextCode}";

        return [
            'code' => $code,
            'increment' => $nextCode,
        ];
    }


}
