<?php

namespace Tests\Feature;

use App\Contracts\ModuleContract;
use App\Support\ModuleRegistry;
use Tests\TestCase;

class ModuleContractTest extends TestCase
{
    public function test_all_registered_modules_have_contract_providers(): void
    {
        $registry = app(ModuleRegistry::class);

        foreach (array_keys($registry->all()) as $key) {
            $providers = $registry->providers();
            $this->assertArrayHasKey($key, $providers, "Provider missing for {$key}");
            $this->assertInstanceOf(ModuleContract::class, $providers[$key]);
        }
    }
}
