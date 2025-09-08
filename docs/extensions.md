# Building & Registering Extensions

The platform exposes a **Module Registry** that allows packages to register
and toggle modules at runtime.

## Register a Module

```php
use App\Support\ModuleRegistry;

$registry = app(ModuleRegistry::class);
$registry->register('crm', ['name' => 'CRM']);
```

## Enable for a Tenant

```php
$tenant = Tenant::find(1);
$registry->enable($tenant, 'crm');
```

## Hooks & Events

* `App\\Events\\ModuleRegistered` – fired when a module is registered.
* `App\\Events\\ModuleToggled` – fired when a module is enabled/disabled for a tenant.

Custom callbacks can be attached via hooks:

```php
$registry->hook('module.enabled', function (Tenant $tenant, string $module) {
    // perform additional boot logic
});
```

Extensions can listen for these events or hooks to perform setup,
seed data, or react to module activation.
