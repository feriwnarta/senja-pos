<?php

namespace App\Service\Impl;

use App\Models\CentralKitchen;
use App\Models\Outlet;
use App\Service\CompositionService;
use Exception;
use Illuminate\Support\Facades\Log;

class CompositionServiceImpl implements CompositionService
{

    public function findOutletById(string $id): ?Outlet
    {
        try {
            return Outlet::find($id);
        } catch (Exception $exception) {
            Log::error('gagal mendapatkan data outlet berdasarkan id di composition service impl');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            return null;
        }
    }

    public function findCentralKitchenById(string $id): ?CentralKitchen
    {
        try {
            return CentralKitchen::find($id);
        } catch (Exception $exception) {
            Log::error('gagal mendapatkan data central kitchen berdasarkan id di composition service impl');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            return null;
        }
    }
}
