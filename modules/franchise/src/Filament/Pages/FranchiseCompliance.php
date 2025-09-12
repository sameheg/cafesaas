<?php

namespace Modules\Franchise\Filament\Pages;

use Filament\Pages\Dashboard;
use Modules\Franchise\Filament\Widgets\ComplianceChart;
use Modules\Franchise\Filament\Widgets\SalesUpliftWidget;

class FranchiseCompliance extends Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected function getWidgets(): array
    {
        return [
            ComplianceChart::class,
            SalesUpliftWidget::class,
        ];
    }
}
