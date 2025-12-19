<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;

class WarehouseDashboardController extends Controller
{
    public function index()
    {
        $packagingStatuses = ['pack', 'packaging'];
        $readyStatuses     = ['ready', 'ready_for_pickup'];

        $packagingCount = Order::whereIn('status', $packagingStatuses)->count();
        $readyCount     = Order::whereIn('status', $readyStatuses)->count();

        $packagingQueue = Order::with('customer')
            ->whereIn('status', $packagingStatuses)
            ->orderBy('created_at', 'asc')
            ->limit(30)
            ->get();

        return view('dashboard.warehouse', compact(
            'packagingCount',
            'readyCount',
            'packagingQueue'
        ));
    }
}
