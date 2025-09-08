<?php

namespace Tests\Unit;

use App\Events\PriceRuleApplied;
use App\Models\PriceRule;
use App\Models\Tenant;
use App\Services\PricingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PricingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_applies_rule_and_fires_event(): void
    {
        Tenant::factory()->create(['id' => 1]);

        $rule = PriceRule::create([
            'tenant_id' => 1,
            'branch_id' => 5,
            'scope' => 'product',
            'condition' => [
                'time' => [
                    'start' => now()->subHour()->toDateTimeString(),
                    'end' => now()->addHour()->toDateTimeString(),
                ],
                'volume' => [
                    'min' => 5,
                    'max' => 20,
                ],
            ],
            'formula' => 'price * 0.8',
        ]);

        Event::fake();
        $service = new PricingService;
        $price = $service->apply(100, $rule->tenant_id, $rule->branch_id, 10);

        Event::assertDispatched(PriceRuleApplied::class);
        $this->assertSame(80.0, $price);
    }
}
