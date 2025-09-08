<?php

namespace Tests\Feature;

use App\Http\Middleware\IpAllowlist;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class IpAllowlistTest extends TestCase
{
    public function test_blocks_disallowed_ip(): void
    {
        config(['security.ip_allowlist' => ['1.2.3.4']]);
        Route::middleware(IpAllowlist::class)->get('/ip-test', fn () => 'ok');
        $this->get('/ip-test')->assertStatus(403);
    }

    public function test_allows_allowed_ip(): void
    {
        config(['security.ip_allowlist' => ['127.0.0.1']]);
        Route::middleware(IpAllowlist::class)->get('/ip-test-allowed', fn () => 'ok');
        $this->get('/ip-test-allowed')->assertOk();
    }
}
