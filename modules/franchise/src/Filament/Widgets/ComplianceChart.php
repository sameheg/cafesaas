<?php

namespace Modules\Franchise\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\Franchise\Models\FranchiseBranch;

class ComplianceChart extends ChartWidget
{
    protected static ?string $heading = 'Compliance';

    protected function getData(): array
    {
        $compliance = FranchiseBranch::count() ? 95 : 0;

        return [
            'datasets' => [
                [
                    'label' => 'Compliance %',
                    'data' => [$compliance],
                ],
            ],
            'labels' => ['Compliance'],
        ];
    }
}
