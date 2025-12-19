<?php

namespace App\Automation;

use App\Models\Order;
use App\Models\StaffUser;

class AutoAssignDesigner
{
    public function handle(Order $order): void
    {
        if ($order->designer_id) {
            return;
        }

        $designer = StaffUser::where('role', 'designer')
            ->orderBy('current_load', 'asc')
            ->first();

        if ($designer) {
            $order->designer_id = $designer->id;
            $order->save();
        }
    }
}
