<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkOrdersOverdue extends Command
{
    protected $signature = 'orders:mark-overdue';
    protected $description = 'Automatically mark overdue orders based on deadline';

    public function handle(): int
    {
        $today = Carbon::today();

        $orders = Order::whereNotNull('deadline')
            ->whereDate('deadline', '<', $today)
            ->whereNotIn('status', [Order::STATUS_DONE, Order::STATUS_SHIPPED, Order::STATUS_OVERDUE])
            ->get();

        foreach ($orders as $order) {
            $from = $order->status;
            $order->status = Order::STATUS_OVERDUE;
            $order->lifecycle = 'overdue';
            $order->updated_at_client = now();
            $order->save();

            // Log & event
            \App\Services\OrderActivityLogger::logTransition(
                order: $order,
                from: $from,
                to: Order::STATUS_OVERDUE,
                staffId: null,
                note: 'auto_overdue',
                clientTs: now()->timestamp
            );
        }

        Cache::tags(['order_board'])->flush();

        $this->info("Processed {$orders->count()} overdue orders.");

        return self::SUCCESS;
    }
}
