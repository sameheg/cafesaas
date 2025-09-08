<?php

namespace Modules\Inventory\Models;

use Modules\Inventory\Events\InventoryStockLow;
use Modules\Inventory\Models\StockAlert;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $table = 'inventory_item';

    protected $fillable = ['tenant_id', 'branch_id', 'name', 'sku', 'quantity', 'low_stock_threshold'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    protected static function booted(): void
    {
        static::saved(function (self $item) {
            if ($item->quantity < $item->low_stock_threshold) {
                StockAlert::create([
                    'tenant_id' => $item->tenant_id,
                    'inventory_item_id' => $item->id,
                    'quantity' => $item->quantity,
                    'threshold' => $item->low_stock_threshold,
                ]);
                event(new InventoryStockLow($item));
            }
        });
    }
}
