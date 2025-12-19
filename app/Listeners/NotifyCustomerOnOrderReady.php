<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Services\WhatsAppService;

class NotifyCustomerOnOrderReady
{
    public function handle(OrderStatusUpdated $event): void
    {
        $order = $event->order;

        if ($order->status === 'ready') {
            $order->loadMissing('customer');
            $customer = $order->customer ?? null;
            if ($customer && !empty($customer->phone)) {
                $orderNo = $order->order_code ?? $order->order_no ?? $order->id;
                $message = "Halo {$customer->name}, pesanan #{$orderNo} sudah siap.";
                app(WhatsAppService::class)->sendMessage($customer->phone_e164 ?? $customer->phone, $message);
            }
        }
    }
}
