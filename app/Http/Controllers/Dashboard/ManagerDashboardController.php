<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Schema;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        $from = now()->startOfMonth();
        $to   = now();

        $ordersBase = Order::whereBetween('created_at', [$from, $to]);

        $totalOrders = (clone $ordersBase)->count();
        $doneOrders  = (clone $ordersBase)->where('status', 'completed')->count();
        $inProgress  = (clone $ordersBase)->whereNotIn('status', ['completed', 'cancelled'])->count();

        $totalRevenue = (clone $ordersBase)->sum('total_price');
        $totalCost    = (clone $ordersBase)->sum('total_cost');
        $totalProfit  = (clone $ordersBase)->sum('profit');

        $avgProfitPerOrder = $totalOrders > 0 ? round($totalProfit / $totalOrders) : 0;
        $marginPercent     = $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 1) : 0;

        $topProducts = collect();
        if (Schema::hasColumn('orders', 'product_id')) {
            $topProducts = (clone $ordersBase)
                ->selectRaw('product_id, SUM(profit) as total_profit, COUNT(*) as order_count')
                ->groupBy('product_id')
                ->with('product')
                ->orderByDesc('total_profit')
                ->limit(5)
                ->get();
        }

        $topCustomers = (clone $ordersBase)
            ->selectRaw('customer_id, SUM(total_price) as revenue, SUM(profit) as profit')
            ->whereNotNull('customer_id')
            ->groupBy('customer_id')
            ->with('customer')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        return view('dashboard.manager.index', compact(
            'from',
            'to',
            'totalOrders',
            'doneOrders',
            'inProgress',
            'totalRevenue',
            'totalCost',
            'totalProfit',
            'avgProfitPerOrder',
            'marginPercent',
            'topProducts',
            'topCustomers',
        ));
    }
}
