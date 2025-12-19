<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        return view('panel.warehouse.dashboard', [
            'stock' => Inventory::with('product')->get(),
            'shippingQueue' => Order::where('status', 'shipping')->latest()->get(),
            'packingQueue'  => Order::where('status', 'packing')->latest()->get()
        ]);
    }

    public function adjust(Request $request, Product $product)
    {
        $data = $request->validate([
            'quantity' => 'required|integer',
            'note'     => 'nullable|string|max:255',
        ]);

        $inventory = Inventory::firstOrCreate(['product_id' => $product->id]);
        $inventory->quantity = $data['quantity'];
        $inventory->save();

        return back()->with('success', 'Stok diperbarui untuk ' . $product->name);
    }
}
