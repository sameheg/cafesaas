<?php

namespace Modules\Membership\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Modules\Membership\Events\TierUpgraded;

class Membership extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'tenant_id',
        'customer_id',
        'tier',
        'expiry',
        'status',
    ];

    protected $casts = [
        'expiry' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::ulid();
            }
        });
    }

    public function upgrade(string $tier): void
    {
        $this->tier = $tier;
        $this->save();

        TierUpgraded::dispatch($this->tenant_id, $this->id, $tier);
    }
}
