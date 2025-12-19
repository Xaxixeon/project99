<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function show(Order $order)
    {
        $order->load(['customer', 'items.product']);

        // If file_manager used for designer uploads
        $files = \DB::table('file_manager')->where('order_id', $order->id)->get();

        return view('orders.detail', [
            'order' => $order,
            'files' => $files
        ]);
    }
}
