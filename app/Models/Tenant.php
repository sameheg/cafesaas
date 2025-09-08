<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'domain', 'config_json'];

    protected $casts = [
        'config_json' => 'array',
    ];

    public function modules()
    {
        return $this->hasMany(TenantModule::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function featureFlags()
    {
        return $this->hasMany(FeatureFlag::class);
    }

    public function theme()
    {
        return $this->hasOne(Theme::class);
    }
}
