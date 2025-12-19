@extends('cuba.layouts.admin')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold">Order Details #{{ $order->id }}</h4>

                <div>
                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning me-2">
                        <i class="fa fa-edit me-2"></i>Edit
                    </a>

                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" onclick="return confirm('Delete this order?')">
                            <i class="fa fa-trash me-2"></i>Delete
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- ORDER INFO --}}
    <div class="row">

        {{-- CUSTOMER INFO --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-semibold">
                    Customer Information
                </div>
                <div class="card-body">

                    <p class="mb-1">
                        <strong>Name:</strong><br>
                        {{ $order->customer->name }}
                    </p>

                    <p class="mb-1">
                        <strong>Phone:</strong><br>
                        {{ $order->customer->phone }}
                    </p>

                    @if ($order->customer->email)
                        <p class="mb-1">
                            <strong>Email:</strong><br>
                            {{ $order->customer->email }}
                        </p>
                    @endif

                    <p class="mb-1">
                        <strong>Address:</strong><br>
                        {{ $order->customer->address ?? '-' }}
                    </p>

                </div>
            </div>
        </div>

        {{-- ORDER SUMMARY --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-semibold">
                    Order Summary
                </div>
                <div class="card-body">

                    {{-- Status --}}
                    <p class="mb-2">
                        <strong>Status:</strong><br>
                        @php
                            $badge =
                                [
                                    'pending' => 'warning',
                                    'processing' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                ][$order->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $badge }} px-3 py-2">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>

                    <p class="mb-2">
                        <strong>Deadline:</strong><br>
                        {{ $order->deadline->format('d M Y H:i') }}
                    </p>

                    <p class="mb-2">
                        <strong>Created At:</strong><br>
                        {{ $order->created_at->format('d M Y H:i') }}
                    </p>

                    <p class="mb-2">
                        <strong>Total Amount:</strong><br>
                        <span class="fs-4 fw-bold">Rp {{ number_format($order->total) }}</span>
                    </p>

                </div>
            </div>
        </div>

    </div>

    {{-- ORDER ITEMS --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light fw-semibold">
            Order Items
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($order->items as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->variant->name ?? '-' }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>Rp {{ number_format($item->price) }}</td>
                                <td class="fw-bold">Rp {{ number_format($item->price * $item->qty) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    No items found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    {{-- NOTES --}}
    @if ($order->notes)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-semibold">
                Notes
            </div>
            <div class="card-body">
                <p>{{ $order->notes }}</p>
            </div>
        </div>
    @endif
@endsection

<a href="{{ route('orders.invoice.pdf', $order->id) }}" class="btn btn-primary me-2">
    <i class="fa fa-file-pdf me-2"></i>Download PDF
</a>
