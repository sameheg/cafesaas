<?php

namespace App\Providers;

use App\Listeners\InitializeTenant;
use App\Listeners\LogTicketFeedbackToCrm;
use App\Support\EventBus;
use App\Support\TenantManager;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        $bus->subscribe('ticket.resolved', LogTicketFeedbackToCrm::class);

        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(config('security.throttle_per_minute'))
                ->by($request->ip());
        });
    }
}
