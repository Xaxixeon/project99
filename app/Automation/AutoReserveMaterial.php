<?php

namespace App\Automation;

use App\Models\Order;

class AutoReserveMaterial
{
    public function handle(Order $order): void
    {
        // Implementasikan reservasi material jika diperlukan
        // InventoryService::reserveForOrder($order);
    }
}
