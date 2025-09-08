<?php

namespace App\Support\Security;

use App\Events\DomainEvent;
use App\Support\EventBus;

class Mfa
{
    public function __construct(private EventBus $bus) {}

    public function generateSecret(int $length = 20): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    public function generateCode(string $secret, ?int $timeSlice = null): string
    {
        $timeSlice = $timeSlice ?? (int) floor(time() / 30);
        $binaryTime = pack('N*', 0).pack('N*', $timeSlice);
        $hash = hash_hmac('sha1', $binaryTime, $secret, true);
        $offset = ord(substr($hash, -1)) & 0x0F;
        $value = ((ord($hash[$offset]) & 0x7F) << 24)
            | ((ord($hash[$offset + 1]) & 0xFF) << 16)
            | ((ord($hash[$offset + 2]) & 0xFF) << 8)
            | (ord($hash[$offset + 3]) & 0xFF);
        $code = $value % 1000000;

        return str_pad((string) $code, 6, '0', STR_PAD_LEFT);
    }

    public function verifyCode(string $secret, string $code, int $window = 1): bool
    {
        $timeSlice = (int) floor(time() / 30);
        for ($i = -$window; $i <= $window; $i++) {
            if (hash_equals($this->generateCode($secret, $timeSlice + $i), $code)) {
                return true;
            }
        }

        return false;
    }

    public function challengeTotp(int $userId, string $secret): string
    {
        $code = $this->generateCode($secret);
        $this->bus->dispatchNow(DomainEvent::AUTH_MFA_CHALLENGE->value, [
            'user_id' => $userId,
            'method' => 'totp',
        ]);

        return $code;
    }

    public function challengeSms(int $userId, string $phone, string $secret): string
    {
        $code = $this->generateCode($secret);
        // SMS dispatch stub could be implemented here.
        $this->bus->dispatchNow(DomainEvent::AUTH_MFA_CHALLENGE->value, [
            'user_id' => $userId,
            'method' => 'sms',
            'phone' => $phone,
        ]);

        return $code;
    }
}
