<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\View\View;

class BillingDashboardController extends Controller
{
    public function index(): View
    {
        $subscriptions = Subscription::all();
        $payments = Payment::latest()->get();

        return view('billing.dashboard', compact('subscriptions', 'payments'));
    }
}
