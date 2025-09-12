<?php

namespace Modules\Billing\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Billing\Database\Factories\PaymentFactory;

class Payment extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'billing_payments';

    protected $fillable = [
        'tenant_id',
        'invoice_id',
        'method',
        'status',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    protected static function newFactory(): Factory
    {
        return PaymentFactory::new();
    }
}
