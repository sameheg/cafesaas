<?php

namespace Modules\Procurement\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasUlids;

    protected $table = 'procurement_bids';
    protected $guarded = [];

    public function rfq()
    {
        return $this->belongsTo(Rfq::class);
    }
}
