<?php

namespace App\Automation;

use App\Models\Order;

class ClearOverdueIfReturned
{
    public function handle(Order $order): void
    {
        if ($order->getOriginal('status') === Order::STATUS_OVERDUE) {
            $order->lifecycle = 'in_progress';
            $order->save();

            \App\Services\OrderActivityLogger::logTransition(
                order: $order,
                from: 'overdue',
                to: $order->status,
                staffId: auth('staff')->id() ?? null,
                note: 'overdue_cleared',
                clientTs: now()->timestamp
            );
        }
    }
}
