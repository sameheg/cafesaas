<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'service',
        'url',
        'payload',
        'headers',
        'attempts',
        'status',
        'last_error',
        'response_code',
        'response_body',
    ];

    protected $casts = [
        'payload' => 'array',
        'headers' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
