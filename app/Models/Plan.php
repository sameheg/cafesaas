<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'price_cents',
        'currency',
        'interval',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
    ];
}
