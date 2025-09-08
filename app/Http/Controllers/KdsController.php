<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Response;

class KdsController extends Controller
{
    public function index(): Response
    {
        $orders = Order::where('status', 'pending')->get();

        return response($orders);
    }
}
