<div class="overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-100 dark:bg-slate-800">
            <tr>
                <th class="p-3 text-left">Order</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Deadline</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($preorders as $order)
                <tr class="border-t">
                    <td class="p-3 font-semibold">{{ $order->order_code }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->product_name }}</td>
                    <td>{{ optional($order->deadline)->format('d M Y') }}</td>
                    <td>
                        <button class="px-3 py-1 bg-green-600 text-white rounded text-xs">
                            Convert to Task
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
