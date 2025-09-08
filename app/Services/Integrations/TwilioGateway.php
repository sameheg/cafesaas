<?php

namespace App\Services\Integrations;

use Illuminate\Support\Facades\Http;

class TwilioGateway implements SmsGateway
{
    /**
     * @param  array<string,mixed>  $config
     */
    public function __construct(private array $config) {}

    public function send(string $to, string $message): void
    {
        $base = rtrim($this->config['base_url'] ?? '', '/');
        $sid = $this->config['sid'] ?? '';
        $token = $this->config['token'] ?? '';
        $from = $this->config['from'] ?? '';

        Http::withBasicAuth($sid, $token)
            ->asForm()
            ->post($base."/Accounts/{$sid}/Messages.json", [
                'To' => $to,
                'From' => $from,
                'Body' => $message,
            ]);
    }
}
