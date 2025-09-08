<?php

namespace App\Providers;

use App\Listeners\InitializeTenant;
use App\Support\EventBus;
use App\Support\TenantManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(EventBus::class);
        $this->app->singleton(TenantManager::class);
    }

    public function boot(EventBus $bus): void
    {
        $bus->subscribe('tenant.created', InitializeTenant::class);
    }
}
