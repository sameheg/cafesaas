<?php

namespace Modules\Franchise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class FranchiseBranch extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'name',
        'overrides',
    ];

    protected $casts = [
        'overrides' => 'encrypted:array',
    ];
}
