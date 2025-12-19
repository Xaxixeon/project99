<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $ordersCount       = Order::count();
        $production        = Order::whereIn('status', ['production', 'printing', 'finishing'])->count();
        $pendingPayments   = Order::where('status', 'ready')->count();
        $totalUsers        = User::count();
        $recentOrders      = Order::latest()->take(10)->get();
        $invoicesTotalPaid = Invoice::where('status', Invoice::STATUS_PAID)->sum('amount');
        $invoicesUnpaid    = Invoice::where('status', Invoice::STATUS_UNPAID)->sum('amount');
        $monthlyRevenue    = Invoice::where('status', Invoice::STATUS_PAID)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        return view('dashboard.admin.index', [
            'totalOrders'      => $ordersCount,
            'production'       => $production,
            'pendingPayments'  => $pendingPayments,
            'totalUsers'       => $totalUsers,
            'recentOrders'     => $recentOrders,
            'invoicesTotalPaid'=> $invoicesTotalPaid,
            'invoicesUnpaid'   => $invoicesUnpaid,
            'monthlyRevenue'   => $monthlyRevenue,
        ]);
    }
}
