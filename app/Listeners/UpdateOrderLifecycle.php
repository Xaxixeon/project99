<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;

class UpdateOrderLifecycle
{
    public function handle(OrderStatusUpdated $event): void
    {
        $order = $event->order;
        $map = config('order_lifecycle.lifecycle_by_status', []);
        $lifecycle = $map[$order->status] ?? null;

        if ($lifecycle && $order->lifecycle !== $lifecycle) {
            $order->lifecycle = $lifecycle;
            $order->save();
        }
    }
}
