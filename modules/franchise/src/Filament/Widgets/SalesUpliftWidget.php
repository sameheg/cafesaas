<?php

namespace Modules\Franchise\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class SalesUpliftWidget extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Sales Uplift', '5%')->description('Last sync'),
        ];
    }
}
