<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function css(Tenant $tenant)
    {
        $theme = $tenant->theme;

        return response($theme?->toCss() ?? '', 200, [
            'Content-Type' => 'text/css',
        ]);
    }

    public function update(Request $request, Tenant $tenant)
    {
        $data = $request->validate([
            'vars' => ['required', 'array'],
        ]);

        $theme = Theme::updateOrCreate(
            ['tenant_id' => $tenant->id],
            ['vars_json' => $data['vars']]
        );

        return response()->json($theme);
    }
}
