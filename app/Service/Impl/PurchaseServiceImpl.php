<?php

namespace App\Service\Impl;

use App\Models\Purchase;
use App\Models\PurchaseRef;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use App\Service\PurchaseService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseServiceImpl implements PurchaseService
{

    /**
     * proses pembuatan purchase request dari request stock gudang
     * @return void
     */
    public function processPurchaseRequestFromReqStock(string $purchaseReqId)
    {
        try {
            return Cache::lock('createPurchaseRequestFromReqStock', 10)->block(5, function () use ($purchaseReqId) {
                DB::beginTransaction();

                try {
                    // buat


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


    /**
     * buat purchase dari request stock gudang tanpa multi supplier
     * ini hanya akan membuat 1 po untuk 1 supplier saja
     * @param string $purchaseReqId
     * @return mixed
     * @throws Exception
     */
    public function createPurchaseNetFromRequestStock(string $purchaseReqId, string $supplierId, string $paymentScheme, string $dueDate, array $dataPurchase)
    {
        try {
            return Cache::lock('createPurchaseFromRequestStock', 10)->block(5, function () use ($purchaseReqId, $supplierId, $paymentScheme, $dueDate, $dataPurchase) {
                DB::beginTransaction();

                try {
                    Log::info('fungsi create purchase from request stock dijalankan');

                    // buat purchase request refference
                    $purchaseRequest = PurchaseRequest::findOrFail($purchaseReqId);
                    $purchaseRef = $purchaseRequest->purchaseReference()->save(new PurchaseRef());

                    // dapatkan code supplier
                    $supplier = Supplier::findOrFail($supplierId);

                    if ($supplier == null) {
                        Log::error('supplier null saat membuat purchase dari request stock');
                        throw new Exception('Supplier Null');
                    }

                    Log::debug($supplier);

                    // generate code
                    $resultGenerate = $this->generateCodePurchase($supplier->code);

                    // buat purchase
                    $purchase = Purchase::create([
                        'suppliers_id' => $supplierId,
                        'purchase_refs_id' => $purchaseRef->id,
                        'code' => $resultGenerate['code'],
                        'increment' => $resultGenerate['increment'],
                        'payment_scheme' => $paymentScheme,
                        'due_date' => Carbon::now()->copy()->addDays($dueDate),
                    ]);


                    // buat purchase detail
                    foreach ($dataPurchase as $data) {
                        $unitPrice = str_replace(',', '', $data['unitPrice']);
                        $purchase->detail()->create([
                            'items_id' => $data['itemId'],
                            'qty_buy' => $data['purchaseAmount'],
                            'single_price' => $unitPrice,
                            'total_price' => $data['totalAmount'],
                        ]);
                    }

                    // buat purchase history
                    $purchase->history()->create([
                        'desc' => 'Membuat purchase dari permintaan stok',
                        'status' => 'Dibuat',
                    ]);

                    // update puchase request history
                    $purchaseRequest->history()->create([
                        'desc' => 'Purchase dibuat dari request stock gudang',
                        'status' => 'Pembelian dibuat',
                    ]);

                    // commit
                    DB::commit();

                    return true;

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


    public function generateCodePurchase(string $supplierCode)
    {
        $prefix = 'PO';
        $year = date('Ymd');
        $nextCode = 1;

        // dapatkan data increment code selanjutnya
        $lastPurchase = Purchase::latest()->first();


        // lanjutkan increment
        if ($lastPurchase != null) {
            $latestDate = Carbon::parse($lastPurchase->created_at);

            // Cek apakah bulan saat ini berbeda dengan bulan dari waktu terakhir diambil
            if ($latestDate->format('Y-m') !== Carbon::now()->format('Y-m')) {
                // Bulan berbeda, atur $nextCode kembali ke nol
            } else {
                // Bulan sama, increment $nextCode
                $nextCode = ++$lastPurchase->increment;
            }
        }

        return [
            'code' => "$prefix$supplierCode$year$nextCode",
            'increment' => $nextCode
        ];

    }
}
