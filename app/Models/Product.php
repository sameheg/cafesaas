<?php

namespace App\Models;

use App\Support\BelongsToTenant;
use App\Support\HasFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Product extends Model
{
    use BelongsToTenant, HasFactory, HasFiles;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'price_cents',
        'stock',
    ];

    /**
     * @return HasMany<ProductOption>
     */
    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }

    public function images(): MorphToMany
    {
        return $this->files();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
