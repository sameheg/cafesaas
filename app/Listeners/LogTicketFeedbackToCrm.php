<?php

namespace App\Listeners;

use App\Models\CustomerInteraction;

class LogTicketFeedbackToCrm
{
    public function handle(array $payload): void
    {
        if (empty($payload['customer_id']) || empty($payload['feedback'])) {
            return;
        }

        CustomerInteraction::create([
            'customer_id' => $payload['customer_id'],
            'type' => 'ticket_feedback',
            'details' => $payload['feedback'],
            'interaction_at' => now(),
        ]);
    }
}
