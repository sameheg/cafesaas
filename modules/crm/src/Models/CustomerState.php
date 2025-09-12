<?php

declare(strict_types=1);

namespace Modules\Crm\Models;

enum CustomerState: string
{
    case Active = 'active';
    case Lapsed = 'lapsed';
    case Reactivated = 'reactivated';
    case Churned = 'churned';
}
