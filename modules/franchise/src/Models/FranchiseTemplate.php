<?php

namespace Modules\Franchise\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranchiseTemplate extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'type',
        'data',
        'version',
        'status',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
