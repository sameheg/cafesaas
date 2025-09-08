<?php

namespace App\Http\Controllers;

use App\Services\Pricing\Promotion;
use App\Services\Pricing\PromotionRepository;
use App\Services\Pricing\PromotionType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function __construct(private PromotionRepository $promotions) {}

    public function index(Request $request)
    {
        $tenantId = (int) $request->get('tenant_id');

        return response()->json($this->promotions->all($tenantId));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $promotion = new Promotion(
            tenantId: $data['tenant_id'],
            type: PromotionType::from($data['type']),
            value: $data['value'],
            start: isset($data['start']) ? Carbon::parse($data['start']) : null,
            end: isset($data['end']) ? Carbon::parse($data['end']) : null,
            minQty: $data['min_qty'] ?? null,
            event: $data['event'] ?? null,
        );

        $promotion = $this->promotions->create($promotion);

        return response()->json($promotion, 201);
    }

    public function update(Request $request, int $promotionId)
    {
        $data = $this->validateData($request, true);

        $existing = $this->promotions->find($promotionId);
        if (! $existing) {
            return response()->json([], 404);
        }

        $promotion = new Promotion(
            tenantId: $data['tenant_id'] ?? $existing->tenantId,
            type: isset($data['type']) ? PromotionType::from($data['type']) : $existing->type,
            value: $data['value'] ?? $existing->value,
            start: isset($data['start']) ? Carbon::parse($data['start']) : $existing->start,
            end: isset($data['end']) ? Carbon::parse($data['end']) : $existing->end,
            minQty: $data['min_qty'] ?? $existing->minQty,
            event: $data['event'] ?? $existing->event,
            id: $existing->id,
        );

        $promotion = $this->promotions->update($promotionId, $promotion);

        return response()->json($promotion);
    }

    public function destroy(int $promotionId)
    {
        $this->promotions->delete($promotionId);

        return response()->noContent();
    }

    protected function validateData(Request $request, bool $partial = false): array
    {
        $rules = [
            'tenant_id' => [$partial ? 'sometimes' : 'required', 'integer'],
            'type' => [$partial ? 'sometimes' : 'required', 'in:time,quantity,percentage'],
            'value' => [$partial ? 'sometimes' : 'required', 'numeric'],
            'start' => ['nullable', 'date'],
            'end' => ['nullable', 'date', 'after_or_equal:start'],
            'min_qty' => ['nullable', 'integer', 'min:0'],
            'event' => ['nullable', 'string'],
        ];

        return $request->validate($rules);
    }
}
