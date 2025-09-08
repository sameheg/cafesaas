<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            if ($tenant = app(TenantManager::class)->tenant()) {
                $builder->where($builder->qualifyColumn('tenant_id'), $tenant->id);
            }
        });

        static::creating(function (Model $model) {
            if ($tenant = app(TenantManager::class)->tenant()) {
                $model->tenant_id = $tenant->id;
            }
        });
    }
}
