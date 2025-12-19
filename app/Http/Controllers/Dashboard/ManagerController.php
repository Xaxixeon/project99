<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;

class ManagerController extends Controller
{
    public function index()
    {
        return view('panel.manager.dashboard', [
            'allOrders' => Order::latest()->take(30)->get(),
            'rolesSummary' => User::with('roles')->get()
        ]);
    }
}
