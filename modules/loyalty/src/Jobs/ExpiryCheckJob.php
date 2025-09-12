<?php

namespace Modules\Loyalty\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Loyalty\Models\LoyaltyPoint;

class ExpiryCheckJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        LoyaltyPoint::whereNotNull('expiry')
            ->where('expiry', '<', now())
            ->where('balance', '>', 0)
            ->chunkById(100, function ($points) {
                foreach ($points as $record) {
                    $record->balance = 0;
                    $record->save();
                }
            });
    }
}
