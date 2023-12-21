<?php

namespace App\Providers;

use App\Service\CentralProductionService;
use App\Service\Impl\CentralProductionServiceImpl;
use App\Service\Impl\WarehouseTransactionServiceImpl;
use App\Service\WarehouseTransactionService;
use Illuminate\Support\ServiceProvider;

class WarehouseTransaction extends ServiceProvider
{

    public array $singletons = [
        WarehouseTransactionService::class => WarehouseTransactionServiceImpl::class,
        CentralProductionService::class => CentralProductionServiceImpl::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
