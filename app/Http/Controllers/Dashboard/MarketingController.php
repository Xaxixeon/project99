<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;

class MarketingController extends Controller
{
    public function index()
    {
        return view('panel.marketing.dashboard', [
            'completedOrders' => Order::where('status', 'completed')->take(20)->get(),
            'topCustomers'    => \DB::table('orders')
                                    ->select('customer_id', \DB::raw('COUNT(*) as total'))
                                    ->groupBy('customer_id')
                                    ->orderByDesc('total')
                                    ->take(5)
                                    ->get(),
        ]);
    }
}
