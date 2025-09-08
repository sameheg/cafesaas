<?php

namespace App\Services\Integrations;

use Illuminate\Support\Facades\Log;

class LogSmsGateway implements SmsGateway
{
    public function __construct(private array $config = []) {}

    public function send(string $to, string $message): void
    {
        Log::info('SMS to '.$to.': '.$message);
    }
}
