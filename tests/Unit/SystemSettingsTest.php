<?php

namespace Tests\Unit;

use App\Support\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_set_and_get_values(): void
    {
        $settings = new SystemSettings;
        $settings->set('currency', ['code' => 'USD']);

        $this->assertSame(['code' => 'USD'], $settings->get('currency'));
    }
}
