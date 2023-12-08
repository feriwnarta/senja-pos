<?php

namespace App\Providers;

use App\Service\Impl\CogsValuationCalc;
use App\Service\InventoryValuationCalc;
use Illuminate\Support\ServiceProvider;

class AccuntancyProvider extends ServiceProvider
{
    public array $singletons = [
        InventoryValuationCalc::class => CogsValuationCalc::class
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
