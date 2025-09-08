<?php

namespace App\Models;

use App\Support\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantModule extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = ['tenant_id', 'module', 'enabled'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
