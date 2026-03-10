<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TenantResolverService;
class TenantServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TenantResolverService::class, function ($app) {
            return new TenantResolverService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
