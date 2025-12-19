<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;

class CSController extends Controller
{
public function index()
{
    return view('panel.cs.dashboard', [
        'newOrders'      => Order::where('status', 'new')->with('customer')->latest()->get(),
        'assignedOrders' => Order::where('status', 'assigned')->with('customer')->latest()->get(),
    ]);
}


    public function pending()
    {
        return view('panel.cs.pending', [
            'pendingOrders' => Order::whereIn('status', ['new', 'assigned'])->latest()->get()
        ]);
    }
}

class CSDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.cs.index', [
            'newOrders' => Order::where('status', 'pending')->latest()->get(),
        ]);
    }
}