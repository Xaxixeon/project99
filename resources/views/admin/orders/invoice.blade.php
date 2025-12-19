@extends('cuba.layouts.admin')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body p-5">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between mb-4">
                <div>
                    <h3 class="fw-bold text-primary mb-1">INVOICE</h3>
                    <p class="text-muted mb-0">Order #{{ $order->id }}</p>
                </div>

                <div class="text-end">
                    <h5 class="fw-bold">{{ config('app.name') }}</h5>
                    <p class="text-muted mb-1">
                        {{ config('app.company_address', 'Jl. Raya Sample No. 123') }}<br>
                        Phone: {{ config('app.company_phone', '08123456789') }}
                    </p>
                </div>
            </div>

            <hr class="my-4">

            {{-- CUSTOMER & ORDER INFO --}}
            <div class="row mb-4">

                <div class="col-md-6">
                    <h6 class="fw-bold text-uppercase text-secondary">Bill To</h6>
                    <p class="mb-1"><strong>{{ $order->customer->name }}</strong></p>
                    <p class="mb-1">{{ $order->customer->phone }}</p>
                    <p class="mb-0">{{ $order->customer->address ?? '-' }}</p>
                </div>

                <div class="col-md-6 text-end">
                    <h6 class="fw-bold text-uppercase text-secondary">Order Details</h6>

                    <p class="mb-1">
                        <strong>Status:</strong>
                        @php
                            $badge =
                                [
                                    'pending' => 'warning',
                                    'processing' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                ][$order->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($order->status) }}</span>
                    </p>

                    <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
                    <p class="mb-0"><strong>Deadline:</strong> {{ $order->deadline->format('d M Y H:i') }}</p>
                </div>

            </div>

            {{-- ITEMS TABLE --}}
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Variant</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($order->items as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->variant->name ?? '-' }}</td>
                                <td class="text-end">{{ $item->qty }}</td>
                                <td class="text-end">Rp {{ number_format($item->price) }}</td>
                                <td class="text-end fw-bold">
                                    Rp {{ number_format($item->qty * $item->price) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- TOTAL --}}
            <div class="d-flex justify-content-end">
                <div class="text-end">
                    <h5 class="fw-bold mb-1">Grand Total:</h5>
                    <h3 class="fw-bold text-primary">
                        Rp {{ number_format($order->total) }}
                    </h3>
                </div>
            </div>

            {{-- NOTES --}}
            @if ($order->notes)
                <div class="mt-4">
                    <h6 class="fw-bold text-secondary">Notes</h6>
                    <p>{{ $order->notes }}</p>
                </div>
            @endif

            {{-- ACTION BUTTONS --}}
            <div class="mt-5 d-flex justify-content-between">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-2"></i>Back
                </a>

                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fa fa-print me-2"></i>Print Invoice
                </button>
            </div>

        </div>
    </div>

    {{-- PRINT CSS --}}
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .card,
            .card * {
                visibility: visible;
            }

            .card {
                box-shadow: none !important;
                border: none !important;
            }

            .btn {
                display: none !important;
            }
        }
    </style>
@endsection
