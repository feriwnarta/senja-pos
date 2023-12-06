<?php

namespace App\Providers;

use App\Service\CompositionService;
use App\Service\Impl\CompositionServiceImpl;
use Illuminate\Support\ServiceProvider;

class CompositionServiceProvider extends ServiceProvider
{

    public array $singletons = [
        CompositionService::class => CompositionServiceImpl::class,
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
