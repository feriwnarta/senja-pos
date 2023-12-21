<?php

namespace App\Service\Impl;

use App\Models\CentralProduction;
use App\Models\RequestStock;
use App\Models\RequestStockHistory;
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
}
