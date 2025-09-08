<?php

namespace App\Events;

class ModuleRegistered
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $module,
        public array $meta = []
    ) {}
}
