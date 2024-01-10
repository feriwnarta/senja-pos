<?php

namespace App\Providers;

use App\Service\CentralProductionService;
use App\Service\Impl\CentralProductionServiceImpl;
use App\Service\Impl\ShippingServiceImpl;
use App\Service\Impl\WarehouseItemReceiptServiceImpl;
use App\Service\Impl\WarehouseTransactionServiceImpl;
use App\Service\ShippingService;
use App\Service\WarehouseItemReceiptService;
use App\Service\WarehouseTransactionService;
use Illuminate\Support\ServiceProvider;

class WarehouseTransaction extends ServiceProvider
{

    public array $singletons = [
        WarehouseTransactionService::class => WarehouseTransactionServiceImpl::class,
        CentralProductionService::class => CentralProductionServiceImpl::class,
        ShippingService::class => ShippingServiceImpl::class,
        WarehouseItemReceiptService::class => WarehouseItemReceiptServiceImpl::class,
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
