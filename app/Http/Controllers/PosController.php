<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PosController extends Controller
{
    public function store(Request $request): Response
    {
        $data = $request->validate([
            'tenant_id' => ['required', 'integer'],
            'restaurant_table_id' => ['required', 'integer'],
            'total_cents' => ['required', 'integer'],
        ]);

        $order = Order::create($data);

        OrderCreated::dispatch($order);

        return response($order, 201);
    }
}
