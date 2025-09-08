<?php

namespace App\Support;

use App\Events\DomainEvent;
use App\Events\ModuleToggled;
use App\Models\AuditLog;
use App\Models\FeatureFlag;
use App\Models\Tenant;
use App\Models\TenantModule;
use Illuminate\Validation\ValidationException;

class ModuleManager
{
    public function __construct(protected ?ModuleRegistry $registry = null)
    {
        $this->registry = $registry ?? app(ModuleRegistry::class);
    }

    /**
     * Map of module dependencies.
     *
     * @var array<string,array<int,string>>
     */
    protected array $dependencies = [
        'analytics' => ['billing'],
        'billing' => [],
    ];

    public function toggle(Tenant $tenant, string $module, bool $enabled): TenantModule
    {
        if ($enabled) {
            foreach ($this->dependencies[$module] ?? [] as $dependency) {
                $active = $tenant->modules()->where('module', $dependency)->where('enabled', true)->exists();
                if (! $active) {
                    throw ValidationException::withMessages([
                        'module' => "Missing dependency: {$dependency}",
                    ]);
                }
            }
        }

        $tenantModule = $tenant->modules()->updateOrCreate(
            ['module' => $module],
            ['enabled' => $enabled]
        );

        $this->flag($tenant, $module, $enabled);

        AuditLog::create([
            'tenant_id' => $tenant->id,
            'action' => DomainEvent::MODULE_TOGGLED->value,
            'meta' => [
                'module' => $module,
                'enabled' => $enabled,
            ],
        ]);

        event(new ModuleToggled($tenant, $module, $enabled));
        $this->registry?->dispatchHook(
            $enabled ? DomainEvent::MODULE_ENABLED->value : DomainEvent::MODULE_DISABLED->value,
            $tenant,
            $module
        );

        return $tenantModule;
    }

    public function flag(Tenant $tenant, string $key, bool $enabled): FeatureFlag
    {
        return FeatureFlag::updateOrCreate(
            ['tenant_id' => $tenant->id, 'key' => $key],
            ['enabled' => $enabled]
        );
    }

    public function flagEnabled(Tenant $tenant, string $key): bool
    {
        return $tenant->featureFlags()
            ->where('key', $key)
            ->where('enabled', true)
            ->exists();
    }
}
