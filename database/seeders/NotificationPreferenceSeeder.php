<?php

namespace Database\Seeders;

use App\Models\NotificationPreference;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class NotificationPreferenceSeeder extends Seeder
{
    public function run(): void
    {
        $templates = ['order.shipped', 'order.delivered'];
        $channels = ['mail', 'sms'];

        Tenant::all()->each(function ($tenant) use ($templates, $channels) {
            foreach ($templates as $template) {
                foreach ($channels as $channel) {
                    NotificationPreference::firstOrCreate(
                        [
                            'tenant_id' => $tenant->id,
                            'template_key' => $template,
                            'channel' => $channel,
                        ],
                        ['enabled' => true]
                    );
                }
            }
        });
    }
}
