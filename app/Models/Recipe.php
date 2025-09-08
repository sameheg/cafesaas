<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Models\RecipeItem;

class Recipe extends Model
{
    protected $table = 'recipe';

    protected $fillable = ['tenant_id', 'branch_id', 'name', 'description'];

    public function items()
    {
        return $this->hasMany(RecipeItem::class);
    }
}
