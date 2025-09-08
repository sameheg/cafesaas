<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Renter extends Model
{
    protected $table = 'renter';

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
    ];

    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class);
    }
}
