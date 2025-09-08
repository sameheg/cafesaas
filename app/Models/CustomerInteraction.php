<?php

namespace App\Models;

use App\Support\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInteraction extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'customer_id',
        'type',
        'details',
        'interaction_at',
        'tenant_id',
    ];

    protected function casts(): array
    {
        return [
            'interaction_at' => 'datetime',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
