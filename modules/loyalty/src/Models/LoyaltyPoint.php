<?php

namespace Modules\Loyalty\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoyaltyPoint extends Model
{
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $table = 'loyalty_points';

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'balance',
        'expiry',
    ];

    protected $casts = [
        'expiry' => 'datetime',
    ];
}
