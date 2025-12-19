<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;

class ProductionDashboardController extends Controller
{
    public function index()
    {
        $orders = Order::whereIn('status', [
                'printing',
                'finishing',
                'ready',
            ])
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();

        $productionQueue = $orders;

        return view('dashboard.production.index', compact('productionQueue'));
    }
}
