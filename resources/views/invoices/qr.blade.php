<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice QR {{ $invoice->number }}</title>
</head>
<body>
<img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/' . $invoice->qr_path))) }}" alt="QR Code">
</body>
</html>
