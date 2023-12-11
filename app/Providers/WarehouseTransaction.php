<?php

namespace App\Providers;

use App\Service\Impl\WarehouseTransactionServiceImpl;
use App\Service\WarehouseTransactionService;
use Illuminate\Support\ServiceProvider;

class WarehouseTransaction extends ServiceProvider
{

    public array $singletons = [
        WarehouseTransactionService::class => WarehouseTransactionServiceImpl::class,
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
