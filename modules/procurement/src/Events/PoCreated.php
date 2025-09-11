<?php

namespace Modules\Procurement\Events;

class PoCreated
{
    public function __construct(
        public readonly string $poId,
        public readonly string $supplierId
    ) {
    }

    public function toArray(): array
    {
        return [
            'event' => 'procurement.po.created',
            'data' => [
                'po_id' => $this->poId,
                'supplier_id' => $this->supplierId,
            ],
        ];
    }
}
