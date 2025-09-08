<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Supplier;
use App\Support\TenantManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplyChainController extends Controller
{
    public function inventory()
    {
        return InventoryItem::all();
    }

    public function suppliers()
    {
        return Supplier::all();
    }

    public function linkOrder(Request $request)
    {
        $data = $request->validate([
            'purchase_id' => 'required|integer',
            'order_id' => 'required|integer',
        ]);

        $tenantId = app(TenantManager::class)->id();

        DB::table('purchase_order')->insert([
            'tenant_id' => $tenantId,
            'purchase_id' => $data['purchase_id'],
            'order_id' => $data['order_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'linked']);
    }
}
