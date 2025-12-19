<?php

namespace App\Automation;

use App\Models\Order;

class AutoNotifyDesigner
{
    public function handle(Order $order): void
    {
        // Kirim notifikasi ke designer yang ditugaskan (implementasi sesuai kebutuhan)
        // Notification::send($order->designer, new DesignerAssignedNotification($order));
    }
}
