<?php

namespace Modules\Membership\Listeners;

use Modules\Membership\Models\Membership;

class PaymentSuccessListener
{
    /**
     * Handle billing.payment.success@v1 events.
     *
     * @param  array  $event
     */
    public function handle(array $event): void
    {
        $membershipId = $event['data']['membership_id'] ?? null;
        if (! $membershipId) {
            return;
        }

        $membership = Membership::find($membershipId);
        if ($membership) {
            $membership->status = 'active';
            $membership->expiry = now()->addMonth();
            $membership->save();
        }
    }
}
