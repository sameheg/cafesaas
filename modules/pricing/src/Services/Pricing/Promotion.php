<?php

namespace Modules\Pricing\Services\Pricing;

use Carbon\Carbon;

class Promotion
{
    public function __construct(
        public int $tenantId,
        public PromotionType $type,
        public float $value,
        public ?Carbon $start = null,
        public ?Carbon $end = null,
        public ?int $minQty = null,
        public ?string $event = null,
        public ?int $id = null,
    ) {}

    public function isActive(int $quantity, Carbon $now, array $events = []): bool
    {
        if ($this->event && ! in_array($this->event, $events, true)) {
            return false;
        }

        return match ($this->type) {
            PromotionType::TIME => $this->start && $this->end && $now->between($this->start, $this->end),
            PromotionType::QUANTITY => $this->minQty !== null && $quantity >= $this->minQty,
            PromotionType::PERCENTAGE => true,
        };
    }

    public function apply(float $price): float
    {
        return $price * (1 - $this->value / 100);
    }
}
