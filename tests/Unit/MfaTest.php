<?php

namespace Tests\Unit;

use App\Events\DomainEvent;
use App\Support\EventBus;
use App\Support\Security\Mfa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MfaTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_and_verify_totp(): void
    {
        $mfa = $this->app->make(Mfa::class);
        $secret = $mfa->generateSecret();
        $code = $mfa->generateCode($secret);
        $this->assertTrue($mfa->verifyCode($secret, $code));
    }

    public function test_sms_challenge_dispatches_event(): void
    {
        $mfa = $this->app->make(Mfa::class);
        $bus = $this->app->make(EventBus::class);
        $captured = null;
        $bus->subscribe(DomainEvent::AUTH_MFA_CHALLENGE->value, function (array $payload) use (&$captured) {
            $captured = $payload;
        });

        $secret = $mfa->generateSecret();
        $mfa->challengeSms(5, '+100000000', $secret);

        $this->assertSame([
            'user_id' => 5,
            'method' => 'sms',
            'phone' => '+100000000',
        ], $captured);
    }
}
