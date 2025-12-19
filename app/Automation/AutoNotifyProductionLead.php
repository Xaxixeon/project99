<?php

namespace App\Automation;

use App\Models\Order;

class AutoNotifyProductionLead
{
    public function handle(Order $order): void
    {
        // Kirim notifikasi ke kepala produksi (implementasi sesuai kebutuhan)
    }
}
