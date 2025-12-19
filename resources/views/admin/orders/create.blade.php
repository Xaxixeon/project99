@extends('cuba.layouts.admin')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold">Create New Order</h4>

                <a href="{{ route('orders.index') }}" class="btn btn-light">
                    <i class="fa fa-arrow-left me-2"></i> Back to Orders
                </a>
            </div>

            {{-- FORM --}}
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf

                <div class="row g-4">

                    {{-- Customer --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Customer</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">Select customer...</option>

                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->name }} — {{ $customer->phone }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Deadline --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Deadline</label>
                        <input type="datetime-local" name="deadline" class="form-control" required>
                    </div>

                    {{-- Order Notes --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Order Notes</label>
                        <textarea name="notes" class="form-control" placeholder="Write important order notes (optional)..." rows="3"></textarea>
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
                                <tr>
                                    <td>
                                        <select name="items[0][product_id]" class="form-select product-select" required>
                                            <option value="">Choose product...</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <select name="items[0][variant_id]" class="form-select variant-select" required>
                                            <option value="">Choose variant...</option>
                                            @foreach ($variants as $variant)
                                                <option value="{{ $variant->id }}">
                                                    {{ $variant->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <input type="number" name="items[0][qty]" class="form-control" min="1"
                                            value="1" required>
                                    </td>

                                    <td>
                                        <input type="number" name="items[0][price]" class="form-control" min="0"
                                            value="0" required>
                                    </td>

                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger remove-row">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>

                        </table>

                        <button type="button" class="btn btn-outline-primary" id="addItemRow">
                            <i class="fa fa-plus me-2"></i> Add Item
                        </button>
                    </div>

                    {{-- Submit --}}
                    <div class="col-12 mt-4">
                        <button class="btn btn-primary px-4">
                            <i class="fa fa-check me-2"></i> Save Order
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- JS FOR DYNAMIC ROWS --}}
    <script>
        let rowIndex = 1;

        document.getElementById('addItemRow').addEventListener('click', () => {
            let table = document.querySelector('#itemsTable tbody');

            let newRow = `
        <tr>
            <td>
                <select name="items[${rowIndex}][product_id]"
                        class="form-select product-select" required>
                    <option value="">Choose product...</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </td>

            <td>
                <select name="items[${rowIndex}][variant_id]"
                        class="form-select variant-select" required>
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

<x-cuba.admin-layout>
    <h3>Buat Order</h3>

    <form method="POST" action="{{ route('admin.orders.store') }}">
        @csrf

        <select name="product_id" class="form-select mb-3">
            @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>

        <select name="variant_id" class="form-select mb-3">
            @foreach ($products as $product)
                @foreach ($product->variants as $variant)
                    <option value="{{ $variant->id }}">
                        {{ $product->name }} - {{ $variant->name }}
                    </option>
                @endforeach
            @endforeach
        </select>

        <select name="material_id" class="form-select mb-3">
            @foreach ($materials as $material)
                <option value="{{ $material->id }}">{{ $material->name }}</option>
            @endforeach
        </select>

        <select name="finishing_id" class="form-select mb-3">
            @foreach ($finishings as $finishing)
                <option value="{{ $finishing->id }}">{{ $finishing->name }}</option>
            @endforeach
        </select>

        <input type="number" name="discount" class="form-control mb-2" placeholder="Diskon (%)" value="0"
            oninput="updatePrice()">

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="use_tax" value="1" checked
                onchange="updatePrice()">
            <label class="form-check-label">
                Gunakan PPN 11%
            </label>
        </div>

        <div class="alert alert-dark">
            <strong>Total:</strong>
            <span id="pricePreview">Rp 0</span>
        </div>

        <button class="btn btn-primary">Simpan Order</button>
    </form>

</x-cuba.admin-layout>
