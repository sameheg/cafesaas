<?php

namespace App\Http\Controllers;

use App\Models\IntegrationConfig;
use App\Models\WebhookLog;
use Illuminate\Http\Request;

class IntegrationManagerController extends Controller
{
    public function index()
    {
        $configs = IntegrationConfig::with('activeKey')->get();
        $logs = WebhookLog::latest()->limit(50)->get();

        return view('admin.integrations.index', compact('configs', 'logs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'service' => 'required|string',
            'config_json' => 'nullable|array',
        ]);

        IntegrationConfig::updateOrCreate(
            ['tenant_id' => $data['tenant_id'], 'service' => $data['service']],
            ['config_json' => $data['config_json'] ?? null]
        );

        return redirect()->route('admin.integrations.index');
    }
}
