<?php

namespace App\Support;

use App\Models\IdempotencyKey;
use Closure;
use Illuminate\Support\Facades\DB;

trait ManagesIdempotency
{
    public function once(string $key, Closure $callback): void
    {
        if (IdempotencyKey::where('key', $key)->exists()) {
            return;
        }

        DB::transaction(function () use ($key, $callback) {
            IdempotencyKey::create(['key' => $key]);
            $callback();
        });
    }
}
