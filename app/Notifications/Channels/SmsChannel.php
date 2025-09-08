<?php

namespace App\Notifications\Channels;

use App\Models\IntegrationConfig;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class SmsChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toSms')) {
            return;
        }

        $message = $notification->toSms($notifiable);
        if (! $message) {
            return;
        }

        $tenantId = $notifiable->tenant_id ?? null;
        $provider = 'log';

        if ($tenantId) {
            $config = IntegrationConfig::where('tenant_id', $tenantId)
                ->where('service', 'twilio')
                ->value('config_json');
            if (is_array($config) && isset($config['provider'])) {
                $provider = $config['provider'];
            }
        }

        Log::info('SMS via '.$provider.' to '.($notifiable->phone ?? 'unknown').': '.$message);
    }
}
