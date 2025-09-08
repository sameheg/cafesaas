<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationConfig extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'service', 'config_json'];

    protected $casts = [
        'config_json' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function activeKey()
    {
        return $this->hasOne(WebhookKey::class)->where('active', true);
    }
}
