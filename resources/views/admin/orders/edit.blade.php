@extends('cuba.layouts.admin')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold">Edit Order #{{ $order->id }}</h4>

                <a href="{{ route('orders.index') }}" class="btn btn-light">
                    <i class="fa fa-arrow-left me-2"></i> Back to Orders
                </a>
            </div>

            {{-- FORM --}}
            <form action="{{ route('orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- Customer --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Customer</label>
                        <select name="customer_id" class="form-select" required>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" @selected($order->customer_id == $customer->id)>
                                    {{ $customer->name }} — {{ $customer->phone }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Deadline --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Deadline</label>
                        <input type="datetime-local" name="deadline" class="form-control"
                            value="{{ $order->deadline->format('Y-m-d\TH:i') }}" required>
                    </div>

                    {{-- Notes --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Order Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ $order->notes }}</textarea>
                    </div>

                    {{-- Items Section --}}
                    <div class="col-12 mt-4">
                        <h5 class="fw-bold mb-3">Order Items</h5>

                        <table class="table table-bordered" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 30%">Product</th>
                                    <th style="width: 20%">Variant</th>
                                    <th style="width: 15%">Qty</th>
                                    <th style="width: 20%">Price</th>
                                    <th style="width: 15%">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($order->items as $index => $item)
                                    <tr>
                                        <td>
                                            <select name="items[{{ $index }}][product_id]" class="form-select"
                                                required>
                                                <option value="">Choose product...</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" @selected($item->product_id == $product->id)>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <select name="items[{{ $index }}][variant_id]" class="form-select"
                                                required>
                                                <option value="">Choose variant...</option>
                                                @foreach ($variants as $variant)
                                                    <option value="{{ $variant->id }}" @selected($item->variant_id == $variant->id)>
                                                        {{ $variant->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <input type="number" name="items[{{ $index }}][qty]"
                                                class="form-control" min="1" value="{{ $item->qty }}" required>
                                        </td>

                                        <td>
                                            <input type="number" name="items[{{ $index }}][price]"
                                                class="form-control" min="0" value="{{ $item->price }}" required>
                                        </td>

                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                        <button type="button" class="btn btn-outline-primary" id="addItemRow">
                            <i class="fa fa-plus me-2"></i> Add Item
                        </button>
                    </div>

                    {{-- Submit --}}
                    <div class="col-12 mt-4">
                        <button class="btn btn-primary px-4">
                            <i class="fa fa-check me-2"></i> Save Changes
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- JS FOR DYNAMIC ROWS --}}
    <script>
        let rowIndex = {{ count($order->items) }};

        document.getElementById('addItemRow').addEventListener('click', () => {
            let table = document.querySelector('#itemsTable tbody');

            let newRow = `
        <tr>
            <td>
                <select name="items[${rowIndex}][product_id]"
                        class="form-select" required>
                    <option value="">Choose product...</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </td>

            <td>
                <select name="items[${rowIndex}][variant_id]"
                        class="form-select" required>
                    <option value="">Choose variant...</option>
                    @foreach ($variants as $variant)
                        <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                    @endforeach
                </select>
            </td>

            <td>
                <input type="number" name="items[${rowIndex}][qty]"
                       class="form-control" min="1" value="1" required>
            </td>

            <td>
                <input type="number" name="items[${rowIndex}][price]"
                       class="form-control" min="0" value="0" required>
            </td>

            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger remove-row">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

            table.insertAdjacentHTML('beforeend', newRow);

            rowIndex++;
        });

        // Remove row
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    </script>
@endsection
