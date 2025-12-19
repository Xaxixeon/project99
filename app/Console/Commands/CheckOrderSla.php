<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class CheckOrderSla extends Command
{
    protected $signature = 'orders:check-sla';
    protected $description = 'Update SLA status for orders';

    public function handle(): int
    {
        $slaHours  = config('order_sla.overall_hours', 72);
        $threshold = config('order_sla.warning_threshold', 0.8);

        $now = now();

        $orders = Order::whereIn('status', [
                Order::STATUS_NEW,
                Order::STATUS_ASSIGNED,
                Order::STATUS_DESIGN,
                Order::STATUS_PRODUCTION,
                Order::STATUS_QC,
                Order::STATUS_READY,
                Order::STATUS_SHIPPED,
                Order::STATUS_DONE,
            ])
            ->get();

        foreach ($orders as $order) {
            $created = $order->created_at;
            if (!$created) {
                continue;
            }

            $end = $order->status === Order::STATUS_DONE ? $order->updated_at : $now;
            if (!$end) {
                $end = $now;
            }

            $elapsedHours = $created->diffInMinutes($end) / 60;
            $order->lead_time_hours = $elapsedHours;

            if ($order->status === Order::STATUS_DONE) {
                $order->sla_status = $elapsedHours <= $slaHours ? 'met' : 'breached';
            } else {
                if ($elapsedHours >= $slaHours) {
                    $order->sla_status = 'breached';
                } elseif ($elapsedHours >= $slaHours * $threshold) {
                    $order->sla_status = 'at_risk';
                } else {
                    $order->sla_status = null;
                }
            }

            $order->save();
        }

        $this->info("SLA checked for {$orders->count()} orders.");
        return self::SUCCESS;
    }
}
