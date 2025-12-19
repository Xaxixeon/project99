<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderQcCheck;
use Illuminate\Http\Request;

class QcController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'item'  => 'required|string|max:255',
            'passed'=> 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        OrderQcCheck::create([
            'order_id' => $order->id,
            'staff_id' => auth('staff')->id(),
            'item'     => $data['item'],
            'passed'   => $request->boolean('passed'),
            'notes'    => $data['notes'] ?? null,
        ]);

        return back()->with('success', 'QC item ditambahkan.');
    }

    public function toggle(OrderQcCheck $qc)
    {
        $qc->passed = !$qc->passed;
        $qc->save();

        return back();
    }
}
