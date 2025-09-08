<?php

namespace Modules\Pricing\Services\Pricing;

enum PromotionType: string
{
    case TIME = 'time';
    case QUANTITY = 'quantity';
    case PERCENTAGE = 'percentage';
}
