<?php

namespace Modules\Marketplace\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Marketplace\Models\Bid;

class BidController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'rfq_id' => 'required|string',
            'price' => 'required|numeric|min:0.01',
        ]);

        $bid = Bid::create([
            'tenant_id' => (string) ($request->user()->tenant_id ?? 'tenant'),
            'rfq_id' => $data['rfq_id'],
            'store_id' => $request->user()?->id ?? 1,
            'price' => $data['price'],
            'status' => Bid::STATUS_OPEN,
        ]);

        return response()->json(['bid_id' => $bid->id]);
    }
}
