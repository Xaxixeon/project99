<x-admin.layout>

<h2 class="text-3xl font-bold mb-6">Designer Dashboard</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-gray-800 p-6 rounded shadow">
        <h3 class="text-lg font-semibold text-gray-300">Active Designs</h3>
        <p class="text-3xl font-bold mt-2 text-white">{{ $designingCount ?? 0 }}</p>
    </div>

    <div class="bg-gray-800 p-6 rounded shadow">
        <h3 class="text-lg font-semibold text-gray-300">Waiting for Revision</h3>
        <p class="text-3xl font-bold mt-2 text-yellow-400">{{ $revisionCount ?? 0 }}</p>
    </div>

    <div class="bg-gray-800 p-6 rounded shadow">
        <h3 class="text-lg font-semibold text-gray-300">Waiting Approval</h3>
        <p class="text-3xl font-bold mt-2 text-blue-400">{{ $approvalCount ?? 0 }}</p>
    </div>
</div>

<h3 class="text-xl font-bold mt-10 mb-4">Design Queue</h3>

<table class="w-full text-left text-gray-300 bg-gray-800 rounded">
    <thead class="bg-gray-700">
        <tr>
            <th class="px-4 py-2">Order</th>
            <th class="px-4 py-2">Customer</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($designQueue as $order)
        <tr class="border-b border-gray-700">
            <td class="px-4 py-2">{{ $order->id }}</td>
            <td class="px-4 py-2">{{ $order->customer->name }}</td>
            <td class="px-4 py-2">{{ $order->status }}</td>
            <td class="px-4 py-2">
                <a href="{{ route('orders.show', $order->id) }}"
                    class="px-3 py-1 bg-indigo-600 text-white rounded">
                    Open
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</x-admin.layout>
