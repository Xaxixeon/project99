<x-admin.layout>

<h2 class="text-3xl font-bold mb-4 text-white">
    Special Pricing for {{ $customer->name }}
</h2>

<form method="POST" action="{{ route('admin.customers.special-prices.update', $customer) }}">
    @csrf

    <table class="w-full mt-4 bg-gray-900 text-white rounded">
        <tr class="bg-gray-700 text-gray-300">
            <th class="p-3 text-left">Product</th>
            <th class="p-3">Price per m²</th>
            <th class="p-3">Flat Price</th>
        </tr>

        @foreach($products as $product)
        @php
            $sp = $specialPrices[$product->id] ?? null;
        @endphp
        <tr class="border-b border-gray-800">
            <td class="p-3">{{ $product->name }}</td>
            <td class="p-3">
                <input name="prices[{{ $product->id }}][price_per_m2]"
                       type="number"
                       class="w-full px-3 py-2 rounded bg-gray-800 border-gray-700"
                       value="{{ $sp->price_per_m2 ?? '' }}">
            </td>
            <td class="p-3">
                <input name="prices[{{ $product->id }}][flat_price]"
                       type="number"
                       class="w-full px-3 py-2 rounded bg-gray-800 border-gray-700"
                       value="{{ $sp->flat_price ?? '' }}">
            </td>
        </tr>
        @endforeach
    </table>

    <button class="mt-4 bg-indigo-600 px-4 py-2 rounded text-white">
        Save Special Pricing
    </button>

</form>

</x-admin.layout>
