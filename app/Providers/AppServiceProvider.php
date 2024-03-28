<?php

namespace App\Providers;

use App\Contract\CentralKitchen\CentralKitchenRepository;
use App\Contract\CentralKitchen\CentralKitchenService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
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
        Log::shareContext([
            'user' => auth()->user()
        ]);
        Paginator::useBootstrapFive();
    }
}
