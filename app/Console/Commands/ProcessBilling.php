<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Subscription;
use App\Notifications\PaymentOverdue;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class ProcessBilling extends Command
{
    protected $signature = 'billing:process';

    protected $description = 'Generate payments and notify overdue subscriptions';

    public function handle(): int
    {
        Subscription::query()
            ->where('status', 'active')
            ->whereNotNull('next_billing_at')
            ->where('next_billing_at', '<=', now())
            ->each(function (Subscription $subscription) {
                Payment::create([
                    'tenant_id' => $subscription->tenant_id,
                    'subscription_id' => $subscription->id,
                    'amount_cents' => $subscription->amount_cents,
                    'currency' => $subscription->currency,
                    'status' => 'due',
                    'due_at' => $subscription->next_billing_at,
                ]);

                $subscription->update(['status' => 'past_due']);
            });

        Subscription::query()
            ->where('status', 'past_due')
            ->each(function (Subscription $subscription) {
                Notification::route('mail', $subscription->user_id)
                    ->notify(new PaymentOverdue($subscription));
            });

        $this->info('Billing processed.');

        return self::SUCCESS;
    }
}
