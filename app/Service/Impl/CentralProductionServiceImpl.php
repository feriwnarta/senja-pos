<?php

namespace App\Service\Impl;

use App\Service\CentralProductionService;
use Exception;
use Illuminate\Support\Facades\Log;

class CentralProductionServiceImpl implements CentralProductionService
{

    public function generateCode(string $centralId)
    {
        try {


        } catch (Exception $exception) {
            Log::error('gagal generate code central production');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

    }
}
