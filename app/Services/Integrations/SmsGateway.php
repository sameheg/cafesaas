<?php

namespace App\Services\Integrations;

interface SmsGateway
{
    public function send(string $to, string $message): void;
}
