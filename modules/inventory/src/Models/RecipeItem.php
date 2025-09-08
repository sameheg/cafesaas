<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Models\InventoryItem;
use App\Models\Recipe;

class RecipeItem extends Model
{
    protected $table = 'recipe_item';

    protected $fillable = ['tenant_id', 'recipe_id', 'inventory_item_id', 'quantity'];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
