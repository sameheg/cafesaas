<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'tenant_id',
        'number',
        'customer_name',
        'subtotal',
        'tax_total',
        'total',
        'status',
        'issued_at',
        'pdf_path',
        'qr_path',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
