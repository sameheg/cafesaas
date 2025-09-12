<?php

namespace Modules\Loyalty\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Event;
use Modules\Loyalty\Listeners\OrderPaidListener;
use Modules\ModuleServiceProvider;

class LoyaltyServiceProvider extends ModuleServiceProvider
{
    public function boot(): void
    {
        // Load routes and migrations
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // Register event listener for POS orders
        Event::listen('pos.order.paid@v1', OrderPaidListener::class);

        // Schedule expiry job
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->job(new \Modules\Loyalty\Jobs\ExpiryCheckJob())->daily();
        });
    }
}
