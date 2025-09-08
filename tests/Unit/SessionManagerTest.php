<?php

namespace Tests\Unit;

use App\Events\DomainEvent;
use App\Support\EventBus;
use App\Support\Security\SessionManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_sign_and_verify_session(): void
    {
        $manager = $this->app->make(SessionManager::class);
        $id = 'session-1';
        $sig = $manager->sign($id);
        $this->assertTrue($manager->verify($id, $sig));
        $this->assertFalse($manager->verify($id, 'bad'));
    }

    public function test_mfa_challenge_dispatches_event(): void
    {
        $bus = $this->app->make(EventBus::class);
        $captured = null;
        $bus->subscribe(DomainEvent::AUTH_MFA_CHALLENGE->value, function (array $payload) use (&$captured) {
            $captured = $payload;
        });

        $manager = $this->app->make(SessionManager::class);
        $manager->mfaChallenge(1, 'totp');

        $this->assertSame(['user_id' => 1, 'method' => 'totp'], $captured);
    }
}
