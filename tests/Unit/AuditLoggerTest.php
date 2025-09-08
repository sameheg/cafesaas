<?php

namespace Tests\Unit;

use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLoggerTest extends TestCase
{
    use RefreshDatabase;

    public function test_logs_audit_record(): void
    {
        $tenant = \App\Models\Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        $logger = $this->app->make(AuditLogger::class);
        $logger->log('test.action', [], null, $user->id);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'test.action',
            'user_id' => $user->id,
        ]);
    }
}
