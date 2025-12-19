<x-admin.layout>

<h2 class="text-3xl font-bold mb-6">Customer Service Dashboard</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-gray-800 p-6 rounded shadow">
        <h3 class="text-lg font-semibold">New Orders</h3>
        <p class="text-3xl font-bold mt-2">{{ $newOrders ?? 0 }}</p>
    </div>

    <div class="bg-gray-800 p-6 rounded shadow">
        <h3 class="text-lg font-semibold">Waiting Approval</h3>
        <p class="text-3xl font-bold mt-2">{{ $approvalCount ?? 0 }}</p>
    </div>

    <div class="bg-gray-800 p-6 rounded shadow">
        <h3 class="text-lg font-semibold">Urgent Orders</h3>
        <p class="text-3xl font-bold mt-2 text-red-400">{{ $urgentOrders ?? 0 }}</p>
    </div>
</div>

<h3 class="text-xl font-bold mt-10 mb-4">New Orders</h3>

@include('partials.order-table', ['orders' => $newOrderList])

</x-admin.layout>
