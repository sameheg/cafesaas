<?php

namespace App\Support;

use App\Events\ModuleRegistered;
use App\Models\Tenant;

class ModuleRegistry
{
    /**
     * Registered modules keyed by identifier.
     *
     * @var array<string, array>
     */
    protected array $modules = [];

    /**
     * Registered hooks.
     *
     * @var array<string, array<int, callable>>
     */
    protected array $hooks = [];

    /**
     * Register a module with optional metadata.
     */
    public function register(string $key, array $meta = []): void
    {
        $this->modules[$key] = $meta;

        event(new ModuleRegistered($key, $meta));
    }

    /**
     * Get all registered modules.
     *
     * @return array<string, array>
     */
    public function all(): array
    {
        return $this->modules;
    }

    /**
     * Enable a module for the given tenant.
     */
    public function enable(Tenant $tenant, string $module)
    {
        return app(ModuleManager::class)->toggle($tenant, $module, true);
    }

    /**
     * Disable a module for the given tenant.
     */
    public function disable(Tenant $tenant, string $module)
    {
        return app(ModuleManager::class)->toggle($tenant, $module, false);
    }

    /**
     * Register a hook callback for a given event name.
     */
    public function hook(string $event, callable $callback): void
    {
        $this->hooks[$event][] = $callback;
    }

    /**
     * Dispatch hooks for the given event.
     */
    public function dispatchHook(string $event, ...$payload): void
    {
        foreach ($this->hooks[$event] ?? [] as $callback) {
            $callback(...$payload);
        }
    }
}
