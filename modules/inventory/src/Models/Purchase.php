<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Models\InventoryItem;
use App\Models\Supplier;
use App\Models\Branch;

class Purchase extends Model
{
    protected $table = 'purchase';

    protected $fillable = ['tenant_id', 'supplier_id', 'branch_id', 'inventory_item_id', 'quantity', 'purchased_at'];

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    protected static function booted(): void
    {
        static::created(function (self $purchase) {
            $item = $purchase->inventoryItem;
            if ($item) {
                $item->increment('quantity', $purchase->quantity);
            }
        });
    }
}
