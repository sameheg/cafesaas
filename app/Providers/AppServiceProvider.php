<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Events\OrderDelivered;
use App\Events\OrderShipped;
use App\Listeners\AddEmployeeFromCv;
use App\Listeners\InitializeTenant;
use App\Listeners\LogTicketFeedbackToCrm;
use App\Listeners\SendOrderDeliveredNotification;
use App\Listeners\SendOrderShippedNotification;
use App\Listeners\SetOrderStatusCreated;
use App\Listeners\SetOrderStatusShipped;
use App\Support\EventBus;
use App\Support\ModuleRegistry;
use App\Support\TenantManager;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(EventBus::class);
        $this->app->singleton(TenantManager::class);
        $this->app->singleton(ModuleRegistry::class);
    }

    public function boot(EventBus $bus): void
    {
        $bus->subscribe('tenant.created', InitializeTenant::class);
        $bus->subscribe('ticket.resolved', LogTicketFeedbackToCrm::class);
        $bus->subscribe('candidate.accepted', AddEmployeeFromCv::class);

        Event::listen(OrderCreated::class, [SetOrderStatusCreated::class, 'handle']);
        Event::listen(OrderShipped::class, [SetOrderStatusShipped::class, 'handle']);

        Event::listen(OrderShipped::class, [SendOrderShippedNotification::class, 'handle']);
        Event::listen(OrderDelivered::class, [SendOrderDeliveredNotification::class, 'handle']);

        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(config('security.throttle_per_minute'))
                ->by($request->ip());
        });
    }
}
