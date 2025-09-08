<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $table = 'purchase_order';

    protected $fillable = [
        'tenant_id',
        'purchase_id',
        'order_id',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
