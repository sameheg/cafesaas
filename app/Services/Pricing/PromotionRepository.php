<?php

namespace App\Services\Pricing;

class PromotionRepository
{
    /** @var array<int, Promotion> */
    protected array $promotions = [];

    protected int $nextId = 1;

    /**
     * @return Promotion[]
     */
    public function all(int $tenantId): array
    {
        return array_values(array_filter(
            $this->promotions,
            fn (Promotion $p) => $p->tenantId === $tenantId
        ));
    }

    public function create(Promotion $promotion): Promotion
    {
        $promotion->id = $this->nextId++;
        $this->promotions[$promotion->id] = $promotion;

        return $promotion;
    }

    public function find(int $id): ?Promotion
    {
        return $this->promotions[$id] ?? null;
    }

    public function update(int $id, Promotion $promotion): ?Promotion
    {
        if (! isset($this->promotions[$id])) {
            return null;
        }

        $promotion->id = $id;
        $this->promotions[$id] = $promotion;

        return $promotion;
    }

    public function delete(int $id): void
    {
        unset($this->promotions[$id]);
    }
}
