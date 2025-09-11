<?php

namespace Modules\Procurement\Filament\Pages;

use Filament\Pages\Dashboard as FilamentDashboard;
use Modules\Procurement\Filament\Widgets\CycleTimeChart;
use Modules\Procurement\Filament\Widgets\MatchRateWidget;

class ProcurementDashboard extends FilamentDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected function getWidgets(): array
    {
        return [
            CycleTimeChart::class,
            MatchRateWidget::class,
        ];
    }
}
