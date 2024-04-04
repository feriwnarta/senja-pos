<?php

namespace App\Providers;

use App\Repository\Impl\PurchaseRepositoryImpl;
use App\Repository\Impl\PurchaseRequestRepositoryImpl;
use App\Repository\PurchaseRepository;
use App\Repository\PurchaseRequestRepository;
use App\Service\Impl\PurchaseServiceImpl;
use App\Service\PurchaseService;
use Illuminate\Support\ServiceProvider;

class PurchaseServiceProvider extends ServiceProvider
{

    public array $singletons = [
        PurchaseRepository::class => PurchaseRepositoryImpl::class,
        PurchaseService::class => PurchaseServiceImpl::class,
        PurchaseRequestRepository::class => PurchaseRequestRepositoryImpl::class,
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
