<?php

namespace Modules\Procurement\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class CycleTimeChart extends ChartWidget
{
    protected static ?string $heading = 'PO Cycle Time';

    protected function getData(): array
    {
        return [
            'datasets' => [
                ['label' => 'Hours', 'data' => [12, 24, 36]],
            ],
            'labels' => ['PO1', 'PO2', 'PO3'],
        ];
    }
}
