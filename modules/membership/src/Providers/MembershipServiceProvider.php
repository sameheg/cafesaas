<?php

namespace Modules\Membership\Providers;

use Illuminate\Support\Facades\Event;
use Modules\Membership\Listeners\PaymentSuccessListener;
use Modules\ModuleServiceProvider;

class MembershipServiceProvider extends ModuleServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        Event::listen('billing.payment.success@v1', PaymentSuccessListener::class);
    }
}
