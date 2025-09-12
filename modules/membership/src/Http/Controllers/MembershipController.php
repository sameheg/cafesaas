<?php

namespace Modules\Membership\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Membership\Models\Membership;
use Modules\Membership\Models\MembershipPerk;

class MembershipController extends Controller
{
    public function update(string $id): JsonResponse
    {
        $membership = Membership::findOrFail($id);
        $tier = request()->input('tier');
        $membership->upgrade($tier);

        return response()->json(['updated' => true]);
    }

    public function perks(string $tier): JsonResponse
    {
        $perks = MembershipPerk::where('tier', $tier)->pluck('description');
        if ($perks->isEmpty()) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return response()->json(['perks' => $perks->toArray()]);
    }
}
