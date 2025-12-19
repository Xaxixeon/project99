<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        $orders = Order::with(['items.product'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->paginate(20);

        return view('orders.index', [
            'orders' => $orders,
            'customers' => collect([$customer]),
            'designers' => collect(),
        ]);
    }

    public function show(Order $order)
    {
        $customer = Auth::guard('customer')->user();

        abort_if($order->customer_id !== $customer->id, 403);

        $order->load([
            'customer.instansi',
            'designer',
            'items.product',
            'logs.user',
            'files',
            'chats',
            'invoice',
        ]);

        $workflowSteps = [
            'pending'     => 'Order Masuk',
            'assigned'    => 'Assigned ke Designer',
            'designing'   => 'Desain Dikerjakan',
            'design_done' => 'Desain Selesai',
            'production'  => 'Produksi Dimulai',
            'printing'    => 'Printing',
            'finishing'   => 'Finishing',
            'ready'       => 'Siap Dibayar',
            'paid'        => 'Sudah Dibayar',
            'packing'     => 'Packing',
            'shipping'    => 'Pengiriman',
            'completed'   => 'Selesai',
        ];

        return view('orders.show', [
            'order' => $order,
            'workflowSteps' => $workflowSteps,
        ]);
    }
}
