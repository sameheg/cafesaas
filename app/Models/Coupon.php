<?php

namespace App\Models;

use App\Support\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'code',
        'discount_type',
        'value',
        'start_at',
        'end_at',
        'usage_limit',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function isValid(): bool
    {
        $now = now();

        if ($this->start_at && $now->lt($this->start_at)) {
            return false;
        }

        if ($this->end_at && $now->gt($this->end_at)) {
            return false;
        }

        if (! is_null($this->usage_limit) && $this->usage_limit <= 0) {
            return false;
        }

        return true;
    }

    public function discountAmount(int $amountCents): int
    {
        return match ($this->discount_type) {
            'percentage' => (int) round($amountCents * ($this->value / 100)),
            default => min($amountCents, $this->value),
        };
    }
}
