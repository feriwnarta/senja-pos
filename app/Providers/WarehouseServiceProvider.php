<?php

namespace App\Providers;

use App\Service\CategoryItemService;
use App\Service\Impl\CategoryItemServiceImpl;
use App\Service\Impl\WarehouseServiceImpl;
use App\Service\WarehouseService;
use Illuminate\Support\ServiceProvider;

class WarehouseServiceProvider extends ServiceProvider
{

    public array $singletons = [
        WarehouseService::class => WarehouseServiceImpl::class,
        CategoryItemService::class => CategoryItemServiceImpl::class,
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
