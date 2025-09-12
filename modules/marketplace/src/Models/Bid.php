<?php

namespace Modules\Marketplace\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Modules\Marketplace\Events\BidAwarded;

class Bid extends Model
{
    use HasUlids;

    protected $table = 'marketplace_bids';

    protected $fillable = [
        'tenant_id',
        'rfq_id',
        'store_id',
        'price',
        'status',
    ];

    const STATUS_OPEN = 'open';
    const STATUS_AWARDED = 'awarded';

    public function award(): void
    {
        $this->status = self::STATUS_AWARDED;
        $this->save();

        Event::dispatch(new BidAwarded($this));
    }
}
