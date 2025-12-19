<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;

class ApplyAutomationRules
{
    public function handle(OrderStatusUpdated $event): void
    {
        $order = $event->order;
        $rules = config('order_automation.on_status.' . $order->status, []);

        foreach ($rules as $class) {
            app($class)->handle($order, $event);
        }
    }
}
