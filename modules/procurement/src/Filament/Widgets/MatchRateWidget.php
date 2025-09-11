<?php

namespace Modules\Procurement\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class MatchRateWidget extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Match Rate', '100%'),
        ];
    }
}
