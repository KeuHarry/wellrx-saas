<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        @page {
            margin-top: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin-top: 50px;
            margin-bottom: 0;
            margin-left: 0;
            margin-right: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header, .document-type {
            text-align: center;
            margin-bottom: 20px;
        }

        .document-type h2 {
            font-size: 24px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        .details {
            margin-bottom: 20px;
        }

        .details p {
            margin: 5px 0;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #888;
        }

        @media print {
            body {
                background-color: #fff;
                color: #000;
            }

            .container {
                box-shadow: none;
                border-radius: 0;
                margin: 0;
                padding: 10px;
            }

            table th {
                background-color: #2c3e50;
                color: white;
                font-weight: bold;
            }

            table th, table td {
                font-size: 12px;
                padding: 8px;
            }

            .header h1, .header p, .details p, .footer p {
                font-size: 14px;
                margin: 5px 0;
            }

            .document-type h2 {
                font-size: 14px;
            }

            .footer {
                page-break-after: always;
            }

            /* Hide unnecessary elements */
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1>{{ $pharmacy['pharmacy_name'] }}</h1>
            <p>{{ $pharmacy['pharmacy_address'] }}, {{ $pharmacy['pharmacy_city'] }}</p>
            <p>Phone: {{ $pharmacy['pharmacy_phone'] }} | Email: {{ $pharmacy['pharmacy_email'] }}</p>
        </div>

        <div class="document-type">
            <h2>CUSTOMER INVOICE</h2>
            <p>{{ $sale->code }}</p>
        </div>

        <!-- Sale Details -->
        <div class="details">
            <p><strong>Invoice Date:</strong> {{ $sale->created_at->format('jS F Y') }}</p>
            <p><strong>Customer Name:</strong> {{ $sale->customer ? $sale->customer->name : 'Client' }}</p>
        </div>

        <!-- Sale Items Table -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price ({{ $pharmacy['currency'] ?? '$' }})</th>
                    <th>Total ({{ $pharmacy['currency'] ?? '$' }})</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->saleItems as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product->label }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        <td>{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" style="text-align: right;">Total:</th>
                    <th>{{ $pharmacy['currency'] ?? '$' }}{{ number_format($sale->total, 2) }}</th>
                </tr>
                <tr>
                    <th colspan="4" style="text-align: right;">Tax:</th>
                    <th>{{ $pharmacy['currency'] ?? '$' }}{{ number_format($sale->taxes, 2) }}</th>
                </tr>
                <tr>
                    <th colspan="4" style="text-align: right;">Discount:</th>
                    <th>{{ $sale->discount > 0 ? '-' : '' }}{{ $pharmacy['currency'] ?? '$' }}{{ number_format($sale->discount, 2) }}</th>
                </tr>
                <tr>
                    <th colspan="4" style="text-align: right;">Net Due:</th>
                    <th>{{ $pharmacy['currency'] ?? '$' }}{{ number_format($sale->net_due, 2) }}</th>
                </tr>
            </tfoot>
        </table>

        <!-- Footer Section -->
        <div class="footer">
            <p>{{ $pharmacy['footer_message'] }}</p>
        </div>
        <!-- Print Button -->
        <div class="text-center no-print">
            <button onclick="window.print()">Print Invoice</button>
        </div>

    </div>
</body>
</html>
