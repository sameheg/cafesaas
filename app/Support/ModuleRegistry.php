<?php

namespace App\Support;

use App\Contracts\ModuleContract;
use App\Events\ModuleRegistered;
use App\Models\Tenant;
use Illuminate\Support\Str;

class ModuleRegistry
{
    /**
     * Registered modules keyed by identifier.
     *
     * @var array<string, array>
     */
    protected array $modules = [];

    /**
     * Modules loaded from the JSON registry.
     *
     * @var array<string, bool>
     */
    protected array $loadedFromFile = [];

    /**
     * Path to the module registry file.
     */
    protected string $path;

    /**
     * Loaded service providers keyed by module key.
     *
     * @var array<string, ModuleContract>
     */
    protected array $providers = [];

    public function __construct()
    {
        $this->path = base_path('modules.json');

        if (is_file($this->path)) {
            $data = json_decode(file_get_contents($this->path), true) ?? [];
            foreach ($data['modules'] ?? [] as $module) {
                $key = $module['key'];
                $this->modules[$key] = ['required' => $module['required'] ?? false];
                $this->loadedFromFile[$key] = true;
            }
        }

        $this->registerProviders();
    }

    /**
     * Resolve and register module service providers.
     */
    protected function registerProviders(): void
    {
        foreach (array_keys($this->modules) as $key) {
            $class = $this->providerClass($key);
            if (! class_exists($class)) {
                continue;
            }

            $provider = new $class(app());
            if ($provider instanceof ModuleContract) {
                $provider->register();
                $provider->boot();
                $this->providers[$key] = $provider;
            }
        }
    }

    /**
     * Build the service provider class name for a module key.
     */
    protected function providerClass(string $key): string
    {
        $name = Str::studly($key);

        return "Modules\\{$name}\\{$name}ServiceProvider";
    }

    /**
     * Get loaded service providers.
     *
     * @return array<string, ModuleContract>
     */
    public function providers(): array
    {
        return $this->providers;
    }

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
        $this->modules[$key] = array_merge($this->modules[$key] ?? [], $meta);

        if (! isset($this->loadedFromFile[$key])) {
            $this->sync();
        }

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

    /**
     * Sync the in-memory registry back to the JSON file.
     */
    protected function sync(): void
    {
        $data = ['modules' => []];

        if (is_file($this->path)) {
            $data = json_decode(file_get_contents($this->path), true) ?: ['modules' => []];
        }

        $existing = [];
        foreach ($data['modules'] as $module) {
            $existing[$module['key']] = $module;
        }

        foreach ($this->modules as $key => $meta) {
            $existing[$key] = [
                'key' => $key,
                'required' => $meta['required'] ?? false,
            ];
        }

        $data['modules'] = array_values($existing);

        file_put_contents(
            $this->path,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL
        );

        $this->loadedFromFile = array_fill_keys(array_keys($existing), true);
    }
}
