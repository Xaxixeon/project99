<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;

class ProductionController extends Controller
{
    public function index()
    {
        return view('panel.production.dashboard', [
            'productionQueue' => Order::whereIn('status', ['production', 'printing'])->latest()->get(),
            'finishingQueue'  => Order::where('status', 'finishing')->latest()->get()
        ]);
    }

    public function show(Order $order)
    {
        return view('panel.production.show', compact('order'));
    }
}
