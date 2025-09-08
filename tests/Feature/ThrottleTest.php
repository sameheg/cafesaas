<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ThrottleTest extends TestCase
{
    public function test_throttle_blocks_after_limit(): void
    {
        config(['security.throttle_per_minute' => 1]);
        Route::middleware('throttle:global')->get('/throttle', fn () => 'ok');
        $this->get('/throttle')->assertOk();
        $this->get('/throttle')->assertStatus(429);
    }
}
