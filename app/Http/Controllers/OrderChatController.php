<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderChat;
use Illuminate\Http\Request;

class OrderChatController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'message'    => 'nullable|string',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $staff    = auth('staff')->user();
        $customer = auth('customer')->user();

        if (!$staff && !$customer) {
            abort(403);
        }

        $senderType = $staff ? 'staff' : 'customer';

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('order_chats');
        }

        OrderChat::create([
            'order_id'    => $order->id,
            'staff_id'    => $staff?->id,
            'customer_id' => $customer?->id,
            'message'     => $request->message,
            'attachment'  => $path,
            'sender_type' => $senderType,
        ]);

        return back();
    }
}
