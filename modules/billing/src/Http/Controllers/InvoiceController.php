<?php

namespace Modules\Billing\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Billing\Events\InvoiceIssued;
use Modules\Billing\Models\Invoice;
use Modules\Billing\Services\InvoiceCalculator;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceCalculator $calculator)
    {
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tenant_id' => ['required', 'uuid'],
            'modules' => ['required', 'array'],
        ]);

        $proration = (bool) config('billing.proration');
        $amount = $this->calculator->calculate($data['modules'], $proration);

        $invoice = Invoice::create([
            'tenant_id' => $data['tenant_id'],
            'amount' => $amount,
            'status' => 'due',
            'due_date' => now()->addDays(30),
        ]);

        event(new InvoiceIssued($invoice));

        return response()->json(['invoice_id' => $invoice->id]);
    }

    public function history(string $tenantId): JsonResponse
    {
        $invoices = Invoice::query()
            ->where('tenant_id', $tenantId)
            ->latest()
            ->get();

        if ($invoices->isEmpty()) {
            abort(404);
        }

        return response()->json(['invoices' => $invoices]);
    }
}
