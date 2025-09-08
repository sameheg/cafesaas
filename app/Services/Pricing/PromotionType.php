<?php

namespace App\Services\Pricing;

enum PromotionType: string
{
    case TIME = 'time';
    case QUANTITY = 'quantity';
    case PERCENTAGE = 'percentage';
}
