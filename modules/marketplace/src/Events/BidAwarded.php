<?php

namespace Modules\Marketplace\Events;

use Modules\Marketplace\Models\Bid;

class BidAwarded
{
    public function __construct(public Bid $bid) {}

    public function toPayload(): array
    {
        return [
            'bid_id' => $this->bid->id,
            'supplier' => (string) $this->bid->store_id,
        ];
    }
}
