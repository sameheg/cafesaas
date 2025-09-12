<?php

namespace Modules\Loyalty\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PointsEarned
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public string $tenantId,
        public string $customerId,
        public int $points
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('tenant.'.$this->tenantId);
    }

    public function payload(): array
    {
        return [
            'customer_id' => $this->customerId,
            'points' => $this->points,
        ];
    }
}
