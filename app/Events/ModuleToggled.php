<?php

namespace App\Events;

use App\Models\Tenant;

class ModuleToggled
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public Tenant $tenant,
        public string $module,
        public bool $enabled
    ) {}
}
