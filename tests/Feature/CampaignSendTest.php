<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Support\EventBus;
use Mockery;
use Modules\Crm\Models\Segment;

uses(RefreshDatabase::class);

it('creates and sends campaign', function (): void {
    $segment = Segment::create([
        'tenant_id' => 'tenant',
        'name' => 'high_value',
        'criteria' => [],
    ]);

    $bus = Mockery::spy(EventBus::class);
    $this->app->instance(EventBus::class, $bus);

    $response = $this->postJson('/v1/crm/campaigns', [
        'segment' => $segment->name,
        'action' => 'voucher',
    ]);

    $response->assertStatus(200)->assertJsonStructure(['campaign_id']);
    $bus->shouldHaveReceived('publish')->once();
});
