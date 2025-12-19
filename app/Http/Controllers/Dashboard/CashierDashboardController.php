<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;

class CashierDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        return view('dashboard.cashier.index', [
            'pendingPayments' => Order::where('status', 'ready')->get(),
            'paidTodayCount'  => Order::where('status', 'paid')
                ->whereDate('updated_at', $today)
                ->count(),
            'revenueToday'    => Order::where('status', 'paid')
                ->whereDate('updated_at', $today)
                ->sum('total'),
            'unpaid'      => Order::where('status', 'ready')->count(),
            'paidToday'   => Order::whereDate('updated_at', today())
                ->where('status', 'paid')->count(),
            'incomeToday' => Order::whereDate('updated_at', today())
                ->where('status', 'paid')->sum('total'),
            'transactions' => Order::whereIn('status', ['paid', 'ready'])
                ->latest()->take(10)->get(),
        ]);
    }
}
