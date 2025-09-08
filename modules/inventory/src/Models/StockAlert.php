<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Models\InventoryItem;

class StockAlert extends Model
{
    protected $table = 'stock_alert';

    protected $fillable = ['tenant_id', 'inventory_item_id', 'quantity', 'threshold'];

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
