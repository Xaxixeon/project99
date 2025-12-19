@extends('cuba.layouts.admin')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold">Orders</h4>

                <a href="{{ route('orders.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus me-2"></i> New Order
                </a>
            </div>

            {{-- SEARCH + FILTER --}}
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search orders..."
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Filter by Status</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected(request('status') == $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-secondary w-100">
                        <i class="fa fa-filter me-2"></i> Apply Filter
                    </button>
                </div>
            </form>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>
                                    <strong>{{ $order->customer->name }}</strong><br>
                                    <small class="text-muted">{{ $order->customer->phone }}</small>
                                </td>

                                <td>{{ $order->items_count }} items</td>

                                <td>
                                    <strong>Rp {{ number_format($order->total) }}</strong>
                                </td>

                                <td>
                                    @php
                                        $badge =
                                            [
                                                'pending' => 'warning',
                                                'processing' => 'primary',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                            ][$order->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>

                                <td>{{ $order->created_at->format('d M Y') }}</td>

                                <td class="text-end">

                                    {{-- View --}}
                                    <a href="{{ route('orders.show', $order->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('orders.edit', $order->id) }}"
                                        class="btn btn-sm btn-outline-warning">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this order?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
@endsection
