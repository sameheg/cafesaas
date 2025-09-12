<?php

namespace Modules\Marketplace\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasUlids;

    protected $table = 'marketplace_listings';

    protected $fillable = [
        'tenant_id',
        'store_id',
        'item_id',
        'price',
        'stock',
    ];
}
