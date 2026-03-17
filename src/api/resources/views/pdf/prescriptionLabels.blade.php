<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription Labels</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 8px;
            font-size: 11px;
            color: #222;
        }
        .labels-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            max-width: 210mm;
            margin: 0 auto;
        }
        .label {
            width: 51mm;
            min-height: 102mm;
            padding: 3mm 4mm;
            border: 1px solid #ccc;
            background: #fff;
            break-inside: avoid;
            page-break-inside: avoid;
        }
        .label-pharmacy {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 2px;
            line-height: 1.25;
        }
        .label-pharmacy-address {
            font-size: 9px;
            color: #444;
            margin-bottom: 2px;
            line-height: 1.2;
        }
        .label-pharmacy-phone {
            font-size: 9px;
            margin-bottom: 4px;
            padding-bottom: 3px;
            border-bottom: 1px solid #ddd;
        }
        .label-rx-patient {
            margin-bottom: 4px;
            padding-bottom: 3px;
            border-bottom: 1px solid #eee;
        }
        .label-rx-ref {
            font-size: 9px;
            font-weight: bold;
        }
        .label-patient {
            font-size: 10px;
            font-weight: bold;
        }
        .label-product {
            font-size: 11px;
            font-weight: bold;
            margin: 4px 0 3px 0;
            line-height: 1.3;
        }
        .label-instructions {
            font-size: 10px;
            line-height: 1.4;
            margin-top: 2px;
        }
        .label-instructions ul {
            margin: 0;
            padding-left: 14px;
        }
        .label-instructions li {
            margin-bottom: 1px;
        }
        .label-qty {
            font-size: 9px;
            color: #555;
            margin-top: 2px;
        }
        @media print {
            body { padding: 0; }
            .label {
                border: 1px solid #999;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="labels-grid">
        @foreach ($prescription->prescriptionItems as $item)
            @for ($i = 0; $i < max(1, $item->quantity); $i++)
                <div class="label">
                    <div class="label-pharmacy">{{ $pharmacy['pharmacy_name'] ?? 'Pharmacy' }}</div>
                    <div class="label-pharmacy-address">{{ $pharmacy['pharmacy_address'] ?? '' }}{{ isset($pharmacy['pharmacy_city']) && $pharmacy['pharmacy_city'] ? ', ' . $pharmacy['pharmacy_city'] : '' }}</div>
                    <div class="label-pharmacy-phone">Tel: {{ $pharmacy['pharmacy_phone'] ?? '' }}</div>
                    <div class="label-rx-patient">
                        <div class="label-rx-ref">Rx: {{ $prescription->prescription_code }}</div>
                        <div class="label-patient">{{ $prescription->patient->last_name ?? '' }} {{ $prescription->patient->first_name ?? '' }}</div>
                    </div>
                    <div class="label-product">{{ $item->product->label ?? 'Product' }}</div>
                    @if($item->quantity > 1)
                        <div class="label-qty">Qty: {{ $item->quantity }}</div>
                    @endif
                    <div class="label-instructions">
                        @if($item->labelCodes && $item->labelCodes->count() > 0)
                            <ul>
                                @foreach($item->labelCodes as $lc)
                                    <li>{{ $lc->label ?: $lc->label_code }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span>Use as directed.</span>
                        @endif
                    </div>
                </div>
            @endfor
        @endforeach
    </div>
    <script>
        window.onload = function() { window.print(); };
    </script>
</body>
</html>
