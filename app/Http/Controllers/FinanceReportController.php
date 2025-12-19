<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceReportController extends Controller
{
    public function summary(Request $request)
    {
        $from = $request->input('from') ? now()->parse($request->input('from')) : now()->startOfMonth();
        $to   = $request->input('to')   ? now()->parse($request->input('to'))   : now();

        $query = Order::whereBetween('created_at', [$from, $to]);

        $totalRevenue = $query->sum('total_price');
        $totalCost    = $query->sum('total_cost');
        $totalProfit  = $query->sum('profit');

        $byProduct = OrderItem::selectRaw('order_items.product_id, SUM(order_items.subtotal) as revenue, SUM(CASE WHEN orders.subtotal > 0 THEN (orders.profit / orders.subtotal) * order_items.subtotal ELSE 0 END) as profit')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->groupBy('order_items.product_id')
            ->with('product')
            ->get();

        $byCustomer = (clone $query)
            ->selectRaw('customer_id, SUM(total_price) as revenue, SUM(profit) as profit')
            ->groupBy('customer_id')
            ->with('customer')
            ->get();

        return view('reports.finance-summary', compact(
            'from',
            'to',
            'totalRevenue',
            'totalCost',
            'totalProfit',
            'byProduct',
            'byCustomer',
        ));
    }
}
