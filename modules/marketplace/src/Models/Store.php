<?php

namespace Modules\Marketplace\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Modules\Marketplace\Models\Listing;

class Store extends Model
{
    use HasUlids;

    protected $table = 'marketplace_stores';

    protected $fillable = [
        'tenant_id',
        'supplier_id',
        'name',
        'tier',
    ];

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }
}
