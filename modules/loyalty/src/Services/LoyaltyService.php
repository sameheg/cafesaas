<?php

namespace Modules\Loyalty\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Modules\Loyalty\Events\PointsEarned;
use Modules\Loyalty\Models\LoyaltyPoint;

class LoyaltyService
{
    public function earn(string $tenantId, string $customerId, int $points, ?\DateTimeInterface $expiry = null): LoyaltyPoint
    {
        $record = LoyaltyPoint::firstOrCreate(
            ['tenant_id' => $tenantId, 'customer_id' => $customerId],
            ['balance' => 0, 'expiry' => $expiry]
        );

        $record->increment('balance', $points);
        if ($expiry) {
            $record->expiry = $expiry;
        }
        $record->save();

        Event::dispatch(new PointsEarned($tenantId, $customerId, $points));

        Cache::forget($this->cacheKey($tenantId, $customerId));

        return $record;
    }

    public function burn(string $tenantId, string $customerId, int $points): bool
    {
        $record = LoyaltyPoint::where('tenant_id', $tenantId)
            ->where('customer_id', $customerId)
            ->lockForUpdate()
            ->first();

        if (! $record || $record->balance < $points) {
            return false;
        }

        $record->decrement('balance', $points);
        Cache::forget($this->cacheKey($tenantId, $customerId));

        return true;
    }

    public function balance(string $tenantId, string $customerId): int
    {
        return Cache::remember($this->cacheKey($tenantId, $customerId), 60, function () use ($tenantId, $customerId) {
            return (int) LoyaltyPoint::where('tenant_id', $tenantId)
                ->where('customer_id', $customerId)
                ->value('balance') ?? 0;
        });
    }

    protected function cacheKey(string $tenantId, string $customerId): string
    {
        return "loyalty:balance:{$tenantId}:{$customerId}";
    }
}
