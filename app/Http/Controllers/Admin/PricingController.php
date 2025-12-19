<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Customer;
use App\Models\CustomerPrice;
use App\Models\PriceLog;
use App\Models\MemberType;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PricingController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('variants')
            ->orderBy('name')
            ->get();

        $memberTypeQuery = MemberType::query();
        if (Schema::hasColumn('member_types', 'priority')) {
            $memberTypeQuery->orderBy('priority');
        }
        $memberTypes = $memberTypeQuery->orderBy('name')->get();

        $instansi = Instansi::with('prices')->orderBy('name')->get();

        $customers = Customer::with(['prices', 'memberType', 'instansi'])
            ->orderBy('name')
            ->limit(50)
            ->get();

        return view('admin.pricing.index', [
            'products'    => $products,
            'memberTypes' => $memberTypes,
            'instansi'    => $instansi,
            'customers'   => $customers,
        ]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'price'      => 'required|numeric|min:0',
        ]);

        $product = Product::find($request->product_id);

        // log
        PriceLog::create([
            'product_id' => $product->id,
            'old_price'  => $product->base_price,
            'new_price'  => $request->price,
            'user_id'    => Auth::id(),
        ]);

        $product->base_price = $request->price;
        $product->save();

        return back()->with('success', 'Harga berhasil diperbarui.');
    }


    public function logs()
    {
        return view('admin.pricing.logs', [
            'logs' => PriceLog::with(['product', 'editor'])->latest()->paginate(30)
        ]);
    }


    // Harga spesifik per customer
    public function customer(Customer $customer)
    {
        return view('admin.pricing.customer', [
            'customer' => $customer,
            'products' => Product::all(),
            'specials' => CustomerPrice::where('customer_id', $customer->id)->get()->keyBy('product_id')
        ]);
    }


    public function customerUpdate(Request $request, Customer $customer)
    {
        foreach ($request->prices as $productId => $value) {

            if ($value === null || $value === '') {
                CustomerPrice::where('customer_id', $customer->id)
                    ->where('product_id', $productId)
                    ->delete();
                continue;
            }

            CustomerPrice::updateOrCreate([
                'customer_id' => $customer->id,
                'product_id'  => $productId
            ], [
                'price' => $value
            ]);
        }

        return back()->with('success', 'Harga khusus customer diperbarui.');
    }
}
