<?php

namespace Modules\Procurement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Procurement\Models\Bid;
use Modules\Procurement\Models\Po;
use Modules\Procurement\Models\Rfq;

class PoController extends Controller
{
    public function store(Request $request, string $rfqId)
    {
        $rfq = Rfq::findOrFail($rfqId);
        if (Po::where('bid_id', $request->bid_id)->exists()) {
            return response()->json([], 409);
        }

        $data = $request->validate([
            'bid_id' => 'required|exists:procurement_bids,id',
        ]);

        $bid = Bid::findOrFail($data['bid_id']);

        $po = Po::create([
            'tenant_id' => $rfq->tenant_id,
            'bid_id' => $bid->id,
            'supplier_id' => $bid->supplier_id,
            'amount' => $bid->price,
            'status' => 'draft',
        ]);

        return response()->json(['po_id' => $po->id]);
    }
}
