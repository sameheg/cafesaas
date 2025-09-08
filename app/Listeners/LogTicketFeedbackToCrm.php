<?php

namespace App\Listeners;

use App\Models\CustomerInteraction;
use App\Support\ManagesIdempotency;

class LogTicketFeedbackToCrm
{
    use ManagesIdempotency;

    public function handle(array $payload): void
    {
        if (empty($payload['customer_id']) || empty($payload['feedback'])) {
            return;
        }

        $key = 'ticket_feedback:'.hash('sha256', json_encode($payload));

        $this->once($key, function () use ($payload) {
            CustomerInteraction::create([
                'customer_id' => $payload['customer_id'],
                'type' => 'ticket_feedback',
                'details' => $payload['feedback'],
                'interaction_at' => now(),
            ]);
        });
    }
}
