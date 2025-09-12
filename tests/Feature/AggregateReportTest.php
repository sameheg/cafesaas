<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Modules\Franchise\Models\FranchiseBranch;
use Modules\Franchise\Models\FranchiseTemplate;

uses(RefreshDatabase::class);

it('returns aggregate data', function () {
    $tenant = (string) Str::uuid();
    FranchiseBranch::create(['tenant_id' => $tenant, 'name' => 'A', 'overrides' => []]);
    FranchiseTemplate::create(['tenant_id' => $tenant, 'type' => 'recipe', 'data' => [], 'version' => 1, 'status' => 'Local']);

    $this->getJson('/v1/franchise/reports/aggregate')
        ->assertOk()
        ->assertJsonStructure(['data' => ['branches', 'templates']]);
});
