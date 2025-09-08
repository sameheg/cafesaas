<?php

namespace App\Support;

use App\Events\DomainEvent;
use App\Events\ModuleToggled;
use App\Models\AuditLog;
use App\Models\FeatureFlag;
use App\Models\Tenant;
use App\Models\TenantModuleState;
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
     * @var array<string,array<string,string>>
     */
    protected array $dependencies = [
        'analytics' => ['billing' => '^1.0'],
        'billing' => [],
    ];

    /**
     * Get the dependency map.
     *
     * @return array<string,array<string,string>>
     */
    public function dependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * Resolve the given modules with all dependent modules.
     *
     * @param  array<int,string>  $modules
     * @return array<int,string>
     */
    public function resolveDependencies(array $modules): array
    {
        $resolved = [];
        foreach ($modules as $module) {
            $this->resolveModule($module, $resolved);
        }

        return $resolved;
    }

    /**
     * Recursively resolve a module and its dependencies.
     *
     * @param  array<int,string>  $resolved
     */
    protected function resolveModule(string $module, array &$resolved): void
    {
        foreach ($this->dependencies[$module] ?? [] as $dependency => $constraint) {
            $this->resolveModule($dependency, $resolved);
        }

        if (! in_array($module, $resolved, true)) {
            $resolved[] = $module;
        }
    }

    public function toggle(Tenant $tenant, string $module, bool $enabled): TenantModuleState
    {
        if ($enabled) {
            foreach ($this->dependencies[$module] ?? [] as $dependency => $constraint) {
                $active = $tenant->modules()->where('module', $dependency)->where('enabled', true)->exists();
                if (! $active) {
                    throw ValidationException::withMessages([
                        'module' => "Missing dependency: {$dependency}",
                    ]);
                }

                $depMeta = $this->registry->all()[$dependency] ?? null;
                if ($depMeta && isset($depMeta['version']) && ! version_compare($depMeta['version'], ltrim($constraint, '^'), '>=')) {
                    throw ValidationException::withMessages([
                        'module' => "Invalid version for {$dependency}",
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
