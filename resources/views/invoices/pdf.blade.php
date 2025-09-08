<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->number }}</title>
</head>
<body>
<h1>Invoice #{{ $invoice->number }}</h1>
<p>Customer: {{ $invoice->customer_name }}</p>
<table width="100%" border="1" cellpadding="5" cellspacing="0">
    <thead>
    <tr>
        <th>Description</th>
        <th>Qty</th>
        <th>Unit Price</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoice->items as $item)
        <tr>
            <td>{{ $item->description }}</td>
            <td align="center">{{ $item->quantity }}</td>
            <td align="right">{{ number_format($item->unit_price, 2) }}</td>
            <td align="right">{{ number_format($item->total, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<p>Subtotal: {{ number_format($invoice->subtotal, 2) }}</p>
<p>Tax: {{ number_format($invoice->tax_total, 2) }}</p>
<p>Total: {{ number_format($invoice->total, 2) }}</p>
<img src="{{ storage_path('app/' . $invoice->qr_path) }}" alt="QR">
</body>
</html>
