<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer','items'])
            ->latest()
            ->get()
            ->map(function ($order) {
                $order->kanban_status = $order->order_status ?? $order->status ?? 'waiting';
                $order->product_name = $order->product_type ?? ($order->items->first()->name ?? 'Produk');
                $order->customer_name = $order->customer->name ?? '-';
                $order->deadline_date = $order->deadline ?? $order->due_at;
                return $order;
            });

        return view('staff.dashboard', compact('orders'));
    }
}
