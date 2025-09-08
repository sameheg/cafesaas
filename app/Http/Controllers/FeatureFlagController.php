<?php

namespace App\Http\Controllers;

use App\Models\FeatureFlag;
use App\Models\Tenant;
use Illuminate\Http\Request;

class FeatureFlagController extends Controller
{
    public function index(Tenant $tenant)
    {
        return response()->json(
            $tenant->featureFlags()->get(['key', 'enabled'])
        );
    }

    public function update(Request $request, Tenant $tenant, string $key)
    {
        $data = $request->validate([
            'enabled' => ['required', 'boolean'],
        ]);

        $flag = FeatureFlag::updateOrCreate(
            ['tenant_id' => $tenant->id, 'key' => $key],
            ['enabled' => $data['enabled']]
        );

        return response()->json($flag);
    }
}
