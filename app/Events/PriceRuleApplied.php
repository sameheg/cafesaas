<?php

namespace App\Events;

use App\Models\PriceRule;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PriceRuleApplied implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public PriceRule $rule) {}

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('tenants.'.$this->rule->tenant_id);
    }

    public function broadcastAs(): string
    {
        return DomainEvent::PRICE_RULE_APPLIED->value;
    }
}
