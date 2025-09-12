<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Modules\Franchise\Events\TemplateUpdated;
use Modules\Franchise\Models\FranchiseTemplate;

uses(RefreshDatabase::class);

it('emits event when template updated', function () {
    Event::fake();

    $template = FranchiseTemplate::create([
        'tenant_id' => (string) Str::uuid(),
        'type' => 'recipe',
        'data' => ['price' => 10],
        'version' => 1,
        'status' => 'Local',
    ]);

    $this->patchJson('/v1/franchise/templates', [
        'template_id' => $template->id,
        'changes' => ['price' => 20],
    ])->assertOk()->assertJson(['success' => true]);

    Event::assertDispatched(TemplateUpdated::class);
});
