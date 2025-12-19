<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerSpecialPrice;
use App\Models\Product;
use Illuminate\Http\Request;

class CustomerSpecialPriceController extends Controller
{
    public function edit(Customer $customer)
    {
        $products = Product::orderBy('name')->get();
        $specialPrices = $customer->specialPrices->keyBy('product_id');

        return view('admin.customers.special-prices', compact(
            'customer',
            'products',
            'specialPrices'
        ));
    }

    public function update(Request $request, Customer $customer)
    {
        if (!$request->has('prices')) {
            return back()->with('success', 'Special pricing updated.');
        }

        foreach ($request->prices as $productId => $data) {
            $exists = CustomerSpecialPrice::where('customer_id', $customer->id)
                ->where('product_id', $productId)
                ->first();

            $pricePerM2 = $data['price_per_m2'] ?? null;
            $flatPrice = $data['flat_price'] ?? null;

            if (($pricePerM2 === null || $pricePerM2 === '') && ($flatPrice === null || $flatPrice === '')) {
                if ($exists) {
                    $exists->delete();
                }
                continue;
            }

            CustomerSpecialPrice::updateOrCreate(
                [
                    'customer_id' => $customer->id,
                    'product_id' => $productId,
                ],
                [
                    'price_per_m2' => $pricePerM2 !== '' ? $pricePerM2 : null,
                    'flat_price'   => $flatPrice !== '' ? $flatPrice : null,
                ]
            );
        }

        return back()->with('success', 'Special pricing updated.');
    }
}
