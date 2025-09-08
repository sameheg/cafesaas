<?php

namespace Modules\Pricing\Models;

use App\Support\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceRule extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'scope',
        'condition',
        'formula',
    ];

    protected $casts = [
        'condition' => 'array',
    ];
}
