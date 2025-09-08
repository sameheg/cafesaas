<?php

namespace Tests\Feature;

use App\Models\Resource;
use App\Models\Schedule;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\ScheduleReminder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ScheduleReminderTest extends TestCase
{
    use RefreshDatabase;

    public function test_schedule_reminder_sent(): void
    {
        Notification::fake();

        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);

        $resource = Resource::create([
            'tenant_id' => $tenant->id,
            'name' => 'Table 1',
        ]);

        Schedule::create([
            'tenant_id' => $tenant->id,
            'resource_id' => $resource->id,
            'user_id' => $user->id,
            'title' => 'Test',
            'starts_at' => now()->addMinutes(5),
            'ends_at' => now()->addMinutes(65),
            'reminder_minutes_before' => 5,
        ]);

        Artisan::call('schedules:send-reminders');

        Notification::assertSentTo($user, ScheduleReminder::class);
    }
}
