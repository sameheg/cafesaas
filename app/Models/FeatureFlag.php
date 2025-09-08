<?php

namespace App\Models;

use App\Support\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureFlag extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = ['tenant_id', 'key', 'enabled'];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
