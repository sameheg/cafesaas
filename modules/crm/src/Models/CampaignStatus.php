<?php

declare(strict_types=1);

namespace Modules\Crm\Models;

enum CampaignStatus: string
{
    case Draft = 'draft';
    case Sent = 'sent';
}
