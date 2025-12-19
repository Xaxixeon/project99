<?php

namespace App\Automation;

use App\Models\Order;

class NotifyCustomerReady
{
    public function handle(Order $order): void
    {
        if ($order->status === 'ready' && $order->customer) {
            // Notification::send($order->customer, new OrderReadyNotification($order));
        }
    }
}
