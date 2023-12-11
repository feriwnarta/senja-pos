<?php

namespace App\Service\Impl;

use App\Models\CentralKitchen;
use App\Service\WarehouseTransactionService;
use Exception;
use Illuminate\Support\Facades\Log;

class WarehouseTransactionServiceImpl implements WarehouseTransactionService
{

    public function generateCodeRequest(bool $isOutlet, string $id)
    {
        try {
            // generate code request untuk central kitchen central kitchen
            if (!$isOutlet) {
                $centralKitchen = CentralKitchen::findOrFail($id);

                $centralKitchenCode = $centralKitchen->code;
                

                return;
            }


        } catch (Exception $exception) {
            Log::error('gagal generate code request');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

    }
}
