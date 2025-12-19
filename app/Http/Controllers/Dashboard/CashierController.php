<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;

class CashierController extends Controller
{
    public function index()
    {
        return view('panel.cashier.dashboard', [
            'pendingPayments' => Order::where('status', 'ready')->get(),
            'paidOrders'      => Order::where('status', 'paid')->get()
        ]);
    }
}
