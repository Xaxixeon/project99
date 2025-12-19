<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // === ORDERS ===
        $totalOrders = Order::count();
        $paidOrders = Order::where('status', 'paid')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();

        // === CUSTOMERS ===
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('is_active', true)->count();

        // === PRODUCTS ===
        $productCount = Product::count();

        // === CHART: LAST 7 DAYS ===
        $chartLabels = [];
        $chartValues = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartValues[] = Order::whereDate('created_at', $date)->count();
        }

        // === LATEST ORDERS ===
        $latestOrders = Order::with('customer')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'paidOrders',
            'pendingOrders',
            'processingOrders',
            'totalCustomers',
            'activeCustomers',
            'productCount',
            'chartLabels',
            'chartValues',
            'latestOrders'
        ));
    }
}
