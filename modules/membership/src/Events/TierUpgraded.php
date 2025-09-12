<?php

namespace Modules\Membership\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TierUpgraded
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public string $tenantId,
        public string $memberId,
        public string $tier
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('tenant.'.$this->tenantId);
    }

    public function payload(): array
    {
        return [
            'member_id' => $this->memberId,
            'tier' => $this->tier,
        ];
    }
}
