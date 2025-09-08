<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $table = 'recipe';

    protected $fillable = ['tenant_id', 'branch_id', 'name', 'description'];

    public function items()
    {
        return $this->hasMany(RecipeItem::class);
    }
}
