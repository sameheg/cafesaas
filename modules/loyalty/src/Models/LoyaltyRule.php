<?php

namespace Modules\Loyalty\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyRule extends Model
{
    use HasFactory;
    use HasUlids;

    protected $table = 'loyalty_rules';

    protected $fillable = [
        'tenant_id',
        'name',
        'earn_rate',
        'stackable',
    ];

    protected $casts = [
        'earn_rate' => 'decimal:2',
        'stackable' => 'boolean',
    ];
}
