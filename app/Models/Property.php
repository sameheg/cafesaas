<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    protected $table = 'property';

    protected $fillable = [
        'tenant_id',
        'name',
        'address',
        'units',
    ];

    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class);
    }
}
