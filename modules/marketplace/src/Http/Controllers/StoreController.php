<?php

namespace Modules\Marketplace\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Marketplace\Models\Store;

class StoreController extends Controller
{
    public function show(string $supplierId)
    {
        $store = Store::where('supplier_id', $supplierId)->firstOrFail();
        $listings = $store->listings()->get(['item_id','price','stock']);

        return ['listings' => $listings->toArray()];
    }
}
