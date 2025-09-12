<?php

namespace Modules\Franchise\Services;

use Modules\Franchise\Services\MarginViolationException;

class MarginGuard
{
    public function validate(array $changes): void
    {
        if (config('franchise.margin_guards') && isset($changes['price']) && $changes['price'] < 0) {
            throw new MarginViolationException('Price below allowed margin');
        }
    }
}
