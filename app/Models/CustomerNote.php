<?php

namespace App\Models;

use App\Support\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerNote extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'customer_id',
        'note',
        'tenant_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
