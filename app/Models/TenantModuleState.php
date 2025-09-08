<?php

namespace App\Models;

use App\Support\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantModuleState extends Model
{
    use BelongsToTenant, HasFactory;

    protected $table = 'tenant_module_states';

    protected $fillable = ['tenant_id', 'module', 'enabled'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
