<?php

namespace App\Support\Security;

use App\Support\AuditLogger;
use App\Support\EventBus;

class SessionManager
{
    public function __construct(private AuditLogger $logger, private EventBus $bus) {}

    public function sign(string $sessionId): string
    {
        return hash_hmac('sha256', $sessionId, config('app.key'));
    }

    public function verify(string $sessionId, string $signature): bool
    {
        $valid = hash_equals($this->sign($sessionId), $signature);
        $this->logger->log($valid ? 'session.verified' : 'session.invalid', ['session_id' => $sessionId]);

        return $valid;
    }

    public function mfaChallenge(int $userId, string $method): void
    {
        $this->bus->dispatchNow('auth.mfa_challenge', [
            'user_id' => $userId,
            'method' => $method,
        ]);
    }
}
