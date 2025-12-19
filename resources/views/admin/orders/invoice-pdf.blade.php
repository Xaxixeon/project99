<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .title {
            font-size: 26px;
            font-weight: bold;
            color: #3745e5;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            padding-bottom: 3px;
            border-bottom: 1px solid #ccc;
            margin-top: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 7px;
        }

        th {
            background: #f1f1f1;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .total-box {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .notes {
            margin-top: 20px;
            font-size: 13px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div>
            <div class="title">INVOICE</div>
            <div>Order #{{ $order->id }}</div>
        </div>

        <div style="text-align: right">
            <strong>{{ config('app.name') }}</strong><br>
            {{ config('app.company_address', 'Jl. Raya Sample No. 123') }}<br>
            Phone: {{ config('app.company_phone', '08123456789') }}
        </div>
    </div>

    {{-- CUSTOMER INFO --}}
    <div class="section-title">Bill To</div>
    <p>
        <strong>{{ $order->customer->name }}</strong><br>
        {{ $order->customer->phone }}<br>
        {{ $order->customer->address ?? '-' }}
    </p>

    {{-- ORDER INFO --}}
    <div class="section-title">Order Details</div>
    <p>
        <strong>Status:</strong> {{ ucfirst($order->status) }}<br>
        <strong>Date:</strong> {{ $order->created_at->format('d M Y') }}<br>
        <strong>Deadline:</strong> {{ $order->deadline->format('d M Y H:i') }}
    </p>

    {{-- ITEMS TABLE --}}
    <div class="section-title">Items</div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th>Product</th>
                <th>Variant</th>
                <th class="text-right" style="width: 10%">Qty</th>
                <th class="text-right" style="width: 15%">Price</th>
                <th class="text-right" style="width: 15%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->variant->name ?? '-' }}</td>
                    <td class="text-right">{{ $item->qty }}</td>
                    <td class="text-right">Rp {{ number_format($item->price) }}</td>
                    <td class="text-right">
                        Rp {{ number_format($item->qty * $item->price) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTAL --}}
    <div class="total-box">
        Grand Total: Rp {{ number_format($order->total) }}
    </div>

    {{-- NOTES --}}
    @if ($order->notes)
        <div class="notes">
            <strong>Notes:</strong><br>
            {{ $order->notes }}
        </div>
    @endif

</body>

</html>
