<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DesignerDashboardController extends Controller
{
    public function index()
    {
        $orders = Order::whereIn('status', [
                'new',
                'designing',
                'waiting_approval',
            ])
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();

        $designQueue = $orders;
        $queueCount = $orders->count();
        $inProgressCount = $orders->where('status', 'designing')->count();
        $doneTodayCount = Order::where('status', 'design_done')
            ->whereDate('updated_at', today())
            ->count();

        return view('dashboard.designer.index', compact(
            'designQueue',
            'queueCount',
            'inProgressCount',
            'doneTodayCount'
        ));
    }
}
