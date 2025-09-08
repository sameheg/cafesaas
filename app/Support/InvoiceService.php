<?php

namespace App\Support;

use App\Events\InvoiceIssued;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoiceService
{
    public function __construct(private EventBus $bus) {}

    /**
     * @param  array<array{description:string,quantity:int,unit_price:float,tax_rule_id:int|null}>  $items
     */
    public function issue(int $tenantId, string $customer, array $items): Invoice
    {
        $invoice = new Invoice([
            'tenant_id' => $tenantId,
            'number' => Str::uuid()->toString(),
            'customer_name' => $customer,
            'subtotal' => 0,
            'tax_total' => 0,
            'total' => 0,
            'status' => 'issued',
            'issued_at' => now(),
        ]);
        $invoice->save();

        $subtotal = 0;
        $taxTotal = 0;
        foreach ($items as $item) {
            $lineSubtotal = $item['quantity'] * $item['unit_price'];
            $taxAmount = $lineSubtotal * ($this->resolveTaxRate($item['tax_rule_id']) / 100);
            $invoiceItem = new InvoiceItem([
                'tenant_id' => $tenantId,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'tax_rule_id' => $item['tax_rule_id'],
                'tax_amount' => $taxAmount,
                'total' => $lineSubtotal + $taxAmount,
            ]);
            $invoice->items()->save($invoiceItem);
            $subtotal += $lineSubtotal;
            $taxTotal += $taxAmount;
        }

        $invoice->update([
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'total' => $subtotal + $taxTotal,
            'pdf_path' => $this->generatePdf($invoice),
            'qr_path' => $this->generateQr($invoice),
        ]);

        $this->bus->publish('invoice.issued', ['invoice_id' => $invoice->id]);
        event(new InvoiceIssued($invoice));

        return $invoice;
    }

    private function resolveTaxRate(?int $ruleId): float
    {
        if ($ruleId === null) {
            return 0.0;
        }

        return (float) optional(\App\Models\TaxRule::find($ruleId))->rate ?? 0.0;
    }

    private function generatePdf(Invoice $invoice): string
    {
        $html = view('invoices.pdf', ['invoice' => $invoice])->render();
        $path = 'invoices/'.$invoice->id.'.pdf';
        Storage::disk('local')->put($path, $html);

        return $path;
    }

    private function generateQr(Invoice $invoice): string
    {
        $qr = base64_encode('INV-'.$invoice->number);
        $path = 'invoices/'.$invoice->id.'.qr';
        Storage::disk('local')->put($path, $qr);

        return $path;
    }
}
