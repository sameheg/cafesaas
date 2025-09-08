<?php

namespace Modules\Pricing\Services;

use Modules\Pricing\Events\PriceRuleApplied;
use Modules\Pricing\Models\PriceRule;
use Modules\Pricing\Services\Pricing\PromotionRepository;
use Carbon\Carbon;

class PricingService
{
    public function __construct(private ?PromotionRepository $promotions = null)
    {
        $this->promotions = $promotions ?: new PromotionRepository;
    }

    public function apply(float $price, int $tenantId, ?int $branchId, int $quantity, ?Carbon $now = null, array $events = []): float
    {
        $now = $now ?: now();

        $rules = PriceRule::query()
            ->where('tenant_id', $tenantId)
            ->where(function ($q) use ($branchId) {
                $q->whereNull('branch_id');
                if ($branchId !== null) {
                    $q->orWhere('branch_id', $branchId);
                }
            })
            ->get();

        foreach ($rules as $rule) {
            if ($this->matches($rule, $branchId, $quantity, $now)) {
                $price = $this->evaluateFormula($rule->formula, $price, $quantity);
                event(new PriceRuleApplied($rule));
                break;
            }
        }

        foreach ($this->promotions->all($tenantId) as $promotion) {
            if ($promotion->isActive($quantity, $now, $events)) {
                $price = $promotion->apply($price);
            }
        }

        return $price;
    }

    protected function matches(PriceRule $rule, ?int $branchId, int $quantity, Carbon $now): bool
    {
        if ($rule->branch_id && $rule->branch_id !== $branchId) {
            return false;
        }

        $condition = $rule->condition ?? [];

        if (isset($condition['time'])) {
            $start = Carbon::parse($condition['time']['start'] ?? '1900-01-01');
            $end = Carbon::parse($condition['time']['end'] ?? '2999-12-31');
            if ($now->lt($start) || $now->gt($end)) {
                return false;
            }
        }

        if (isset($condition['volume'])) {
            $min = $condition['volume']['min'] ?? 0;
            $max = $condition['volume']['max'] ?? PHP_INT_MAX;
            if ($quantity < $min || $quantity > $max) {
                return false;
            }
        }

        return true;
    }

    protected function evaluateFormula(string $formula, float $price, int $quantity): float
    {
        $expression = str_replace(['price', 'qty'], [$price, $quantity], $formula);

        return eval("return {$expression};");
    }
}
