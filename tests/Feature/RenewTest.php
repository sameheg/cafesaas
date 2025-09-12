<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Modules\Membership\Models\Membership;
use Tests\TestCase;

class RenewTest extends TestCase
{
    use RefreshDatabase;

    public function test_patch_updates_tier(): void
    {
        $membership = Membership::create([
            'id' => (string) Str::ulid(),
            'tenant_id' => 't1',
            'customer_id' => 'c1',
            'tier' => 'silver',
            'expiry' => now()->addDay(),
            'status' => 'active',
        ]);

        $response = $this->patchJson('/v1/membership/'.$membership->id, ['tier' => 'gold']);

        $response->assertStatus(200)->assertJson(['updated' => true]);
        $this->assertEquals('gold', $membership->fresh()->tier);
    }
}
