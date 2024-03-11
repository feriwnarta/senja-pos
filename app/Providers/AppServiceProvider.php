<?php

namespace App\Providers;

use App\Contract\CentralKitchen\CentralKitchenRepository;
use App\Contract\CentralKitchen\CentralKitchenService;
use App\Service\Impl\WarehouseTransactionServiceImpl;
use App\Service\WarehouseTransactionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $singletons = [
        CentralKitchenService::class => CentralKitchenService::class,
        CentralKitchenRepository::class => CentralKitchenRepository::class
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
