<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            width: 80mm; /* Ideal for thermal printers */
        }
        .header, .footer {
            text-align: center;
        }
        .header {
            font-weight: bold;
        }
        .line {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            text-align: left;
            padding: 5px 0;
        }
        .totals {
            font-weight: bold;
        }
        .footer {
            margin-top: 15px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <!-- Pharmacy Information -->
    <div class="header">
        <strong>{{ $pharmacy['pharmacy_name'] }}</strong><br>
        {{ $pharmacy['pharmacy_address'] }}<br>
        {{ $pharmacy['pharmacy_city'] }}<br>
        Tel: {{ $pharmacy['pharmacy_phone'] }} | Email: {{ $pharmacy['pharmacy_email'] }}
    </div>
    <div class="line"></div>

    <!-- Receipt Details -->
    <table>
        <tr>
            <th>Receipt For:</th>
            <td>{{ $sale->code }}</td>
        </tr>
        <tr>
            <th>Date:</th>
            <td>{{ now()->format('Y-m-d h:i:s a') }}</td>
        </tr>
        <tr>
            <th>Client Name:</th>
            <td>{{ $sale->customer ? $sale->customer->name : 'Client' }}</td>
        </tr>
        <tr>
            <th>Payment Mode:</th>
            <td>{{ '-' }}</td>
        </tr>
    </table>
    <div class="line"></div>

    <!-- Items Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Item</th>
                <th style="width: 10%; text-align: right;">Qty</th>
                <th style="width: 25%; text-align: right;">Price ({{ $pharmacy['currency'] ?? '$' }})</th>
                <th style="width: 25%; text-align: right;">Cost ({{ $pharmacy['currency'] ?? '$' }})</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->saleItems as $item)
            <tr>
                <td>{{ $item->product->label }}</td>
                <td style="text-align: right;">{{ $item->quantity }}</td>
                <td style="text-align: right;">{{ number_format($item->price, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="line"></div>

    <!-- Totals -->
    <table>
        <tr>
            <th>SubTotal:</th>
            <td style="text-align: right;">{{ $pharmacy['currency'] ?? '$' }}{{ number_format($sale->total, 2) }}</td>
        </tr>
        <tr>
            <th>Tax:</th>
            <td style="text-align: right;">{{ $pharmacy['currency'] ?? '$' }}{{ number_format($sale->taxes, 2) }}</td>
        </tr>
        <tr>
            <th>Discount:</th>
            <td style="text-align: right;">{{ $sale->discount > 0 ? '-' : '' }}{{ $pharmacy['currency'] ?? '$' }}{{ number_format($sale->discount, 2) }}</td>
        </tr>
        <tr class="totals">
            <th>Total:</th>
            <td style="text-align: right;">{{ $pharmacy['currency'] ?? '$' }}{{ number_format($sale->net_due, 2) }}</td>
        </tr>
        <tr class="totals">
            <th>Total Paid:</th>
            <td style="text-align: right;">{{ $pharmacy['currency'] ?? '$' }}{{ number_format($sale->paid_amount, 2) }}</td>
        </tr>
        <tr class="totals">
            <th>Tendered:</th>
            <td style="text-align: right;">{{ $pharmacy['currency'] ?? '$' }}{{ number_format($salePayment->tendered, 2) }}</td>
        </tr>
        <tr class="totals">
            <th>Amount:</th>
            <td style="text-align: right;">{{ $pharmacy['currency'] ?? '$' }}{{ number_format($salePayment->amount, 2) }}</td>
        </tr>
        <tr class="totals">
            <th>Change:</th>
            <td style="text-align: right;">{{ $pharmacy['currency'] ?? '$' }}{{ number_format($salePayment->change, 2) }}</td>
        </tr>
    </table>
    <div class="line"></div>

    <!-- Footer -->
    <div class="footer">
        {{ $pharmacy['footer_message'] }}
    </div>
</body>
</html>
