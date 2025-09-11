<?php

namespace Modules\Procurement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Procurement\Models\Rfq;

class RfqController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array',
        ]);

        $rfq = Rfq::create([
            'tenant_id' => $request->user()->tenant_id ?? 'tenant',
            'items' => $data['items'],
            'status' => 'open',
        ]);

        return response()->json(['rfq_id' => $rfq->id]);
    }
}
