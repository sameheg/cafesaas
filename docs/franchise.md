# Franchise Module

Manages franchise templates, overrides and aggregated reporting.

## State Machine

```mermaid
stateDiagram-v2
    [*] --> Local
    Local --> Synced: push()
    Synced --> Overridden: override()
    Overridden --> Audited: audit()
```

## Usage

```php
// Push template changes
Http::patch('/v1/franchise/templates', [
    'template_id' => $id,
    'changes' => ['price' => 20],
]);
```
