<?php

namespace Modules\Marketplace\Filament\Resources;

use Filament\Resources\Resource;
use Modules\Marketplace\Models\Bid;

class MarketplaceBidResource extends Resource
{
    protected static ?string $model = Bid::class;
}
