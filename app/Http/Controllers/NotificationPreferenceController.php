<?php

namespace App\Http\Controllers;

use App\Models\NotificationPreference;
use App\Models\Tenant;
use Illuminate\Http\Request;

class NotificationPreferenceController extends Controller
{
    public function index(Tenant $tenant)
    {
        return response()->json(
            $tenant->notificationPreferences()->get(['template_key', 'channel', 'enabled'])
        );
    }

    public function update(Request $request, Tenant $tenant, string $templateKey, string $channel)
    {
        $data = $request->validate([
            'enabled' => ['required', 'boolean'],
        ]);

        $preference = NotificationPreference::updateOrCreate(
            [
                'tenant_id' => $tenant->id,
                'template_key' => $templateKey,
                'channel' => $channel,
            ],
            ['enabled' => $data['enabled']]
        );

        return response()->json($preference);
    }

    public function dashboard(Tenant $tenant)
    {
        return view('notifications.preferences', [
            'preferences' => $tenant->notificationPreferences()->get(),
        ]);
    }
}
