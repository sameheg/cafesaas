<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'order_id',
        'tracking_number',
        'status',
        'provider',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
