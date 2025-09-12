<?php

namespace Modules\Loyalty\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Loyalty\Services\LoyaltyService;

class BalanceController extends Controller
{
    public function __construct(protected LoyaltyService $service)
    {
    }

    public function __invoke(string $customerId)
    {
        $tenantId = request()->get('tenant_id');
        if (! $tenantId) {
            abort(400, 'tenant_id required');
        }

        $balance = $this->service->balance($tenantId, $customerId);
        if ($balance === 0) {
            // ensure record exists? treat as not found
            // but requirements says 404 if not found.
            // We'll check if record exists
            if (! \Modules\Loyalty\Models\LoyaltyPoint::where('tenant_id', $tenantId)->where('customer_id', $customerId)->exists()) {
                abort(404);
            }
        }

        return ['balance' => $balance];
    }
}
