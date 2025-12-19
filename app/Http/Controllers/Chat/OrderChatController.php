<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderChatController extends Controller
{
    public function send(Request $request, Order $order)
    {
        $request->validate([
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:20000',
        ]);

        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')
                ->store('order_chat/' . $order->id);
        }

        OrderChat::create([
            'order_id' => $order->id,
            'staff_id' => Auth::guard('staff')->id(),
            'customer_id' => Auth::guard('customer')->id(),
            'message' => $request->message,
            'attachment' => $attachmentPath,
            'sender_type' => Auth::guard('staff')->check() ? 'staff' : 'customer',
        ]);

        return back();
    }
}
