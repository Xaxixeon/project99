<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;

class AdjustInventoryOnOrderStatus
{
    public function handle(OrderStatusUpdated $event): void
    {
        $order = $event->order;

        // Contoh: ketika masuk production, kurangi stok material (implementasikan sesuai service Anda)
        // if ($event->previousStatus !== 'production' && $order->status === 'production') {
        //     InventoryService::consumeForOrder($order);
        // }

        // Contoh: ketika revert/cancel, kembalikan stok
        // if ($event->previousStatus === 'production' && $order->status === 'cancelled') {
        //     InventoryService::restoreForOrder($order);
        // }
    }
}
