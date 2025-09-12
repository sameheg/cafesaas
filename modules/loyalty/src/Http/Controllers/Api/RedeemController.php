<?php

namespace Modules\Loyalty\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\RateLimiter;
use Modules\Loyalty\Services\LoyaltyService;
use Symfony\Component\HttpFoundation\Response;

class RedeemController extends Controller
{
    public function __construct(protected LoyaltyService $service)
    {
    }

    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'tenant_id' => ['required', 'string'],
            'customer_id' => ['required', 'string'],
            'points' => ['required', 'integer', 'min:1'],
        ]);

        $key = sprintf('redeem:%s:%s', $data['tenant_id'], $data['customer_id']);
        if (RateLimiter::tooManyAttempts($key, 100)) {
            return response()->json(['message' => 'Rate limit exceeded'], Response::HTTP_TOO_MANY_REQUESTS);
        }
        RateLimiter::hit($key, 3600);

        if (! $this->service->burn($data['tenant_id'], $data['customer_id'], $data['points'])) {
            return response()->json(['success' => false], Response::HTTP_PAYMENT_REQUIRED);
        }

        return ['success' => true];
    }
}
