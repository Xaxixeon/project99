<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderOperation;
use Illuminate\Http\Request;

class OrderOperationController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'type'  => 'required|in:printing,laminating,cutting,finishing_manual,qc,packaging,delivery',
            'notes' => 'nullable|string',
        ]);

        OrderOperation::create([
            'order_id' => $order->id,
            'staff_id' => auth('staff')->id(),
            'type'     => $data['type'],
            'status'   => 'pending',
            'notes'    => $data['notes'] ?? null,
        ]);

        return back()->with('success', 'Operasi produksi ditambahkan.');
    }

    public function start(OrderOperation $operation)
    {
        $operation->status     = 'in_progress';
        $operation->started_at = now();
        $operation->save();

        return back();
    }

    public function finish(OrderOperation $operation)
    {
        $operation->status      = 'done';
        $operation->finished_at = now();
        $operation->save();

        return back();
    }
}
