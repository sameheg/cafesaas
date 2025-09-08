<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Modules\Pricing\Services\PricingService;

class CartService
{
    public function __construct(private PricingService $pricing) {}

    public function addItem(int $userId, int $tenantId, int $productId, int $quantity): void
    {
        $item = DB::table('cart_items')
            ->where([
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'product_id' => $productId,
            ])->first();

        if ($item) {
            DB::table('cart_items')
                ->where('id', $item->id)
                ->update([
                    'quantity' => $item->quantity + $quantity,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('cart_items')->insert([
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function removeItem(int $userId, int $tenantId, int $productId): void
    {
        DB::table('cart_items')
            ->where([
                'user_id' => $userId,
                'tenant_id' => $tenantId,
                'product_id' => $productId,
            ])->delete();
    }

    public function total(int $userId, int $tenantId, ?int $branchId = null): float
    {
        $items = DB::table('cart_items')
            ->where([
                'user_id' => $userId,
                'tenant_id' => $tenantId,
            ])->get();

        $total = 0.0;

        foreach ($items as $item) {
            $price = (float) DB::table('products')
                ->where('id', $item->product_id)
                ->value('price');

            $price = $this->pricing->apply($price, $tenantId, $branchId, (int) $item->quantity);

            $total += $price * $item->quantity;
        }

        return $total;
    }
}
