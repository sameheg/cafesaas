<?php

namespace Modules\Procurement\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Modules\Procurement\Events\PoCreated;
use Modules\Procurement\Models\Bid;

class Po extends Model
{
    use HasUlids;

    protected $table = 'procurement_pos';
    protected $guarded = [];

    public function bid()
    {
        return $this->belongsTo(Bid::class);
    }

    protected static function booted(): void
    {
        static::created(function (self $po) {
            Event::dispatch(new PoCreated($po->id, $po->supplier_id));
        });
    }

    public function send(): void
    {
        $this->update(['status' => 'sent']);
    }

    public function receive(): void
    {
        $this->update(['status' => 'received']);
    }

    public function match(): void
    {
        $this->update(['status' => 'matched']);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function varianceDetect(): void
    {
        $this->update(['status' => 'varied']);
    }
}
