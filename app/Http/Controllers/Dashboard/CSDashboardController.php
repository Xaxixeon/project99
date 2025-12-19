<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;

class CSDashboardController extends Controller
{
    public function index()
    {
        $newStatuses       = ['pending', 'new'];
        $approvalStatuses  = ['waiting_approval'];
        $urgentStatuses    = ['urgent'];

        $newOrders     = Order::whereIn('status', $newStatuses)->count();
        $approvalCount = Order::whereIn('status', $approvalStatuses)->count();

        $urgentOrders = Order::whereIn('status', $newStatuses)
            ->where('created_at', '>=', now()->subDay())
            ->count();

        $newOrderList = Order::with('customer')
            ->whereIn('status', $newStatuses)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('dashboard.customer-service', compact(
            'newOrders',
            'approvalCount',
            'urgentOrders',
            'newOrderList'
        ));
    }
}
