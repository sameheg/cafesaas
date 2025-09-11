<?php

namespace Modules\Procurement\Services;

use Modules\Procurement\Models\Grn;
use Modules\Procurement\Models\Po;

class MatchService
{
    public function threeWayMatch(Po $po, Grn $grn, float $invoiceAmount): bool
    {
        return bccomp((string) $po->amount, (string) $invoiceAmount, 2) === 0
            && bccomp((string) $po->amount, (string) $grn->received_qty, 2) === 0;
    }
}
