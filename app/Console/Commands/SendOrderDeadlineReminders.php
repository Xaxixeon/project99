<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class SendOrderDeadlineReminders extends Command
{
    protected $signature = 'orders:deadline-reminders {--hours=24}';
    protected $description = 'Send WA reminders to customers before order deadline';

    public function handle(WhatsAppService $wa): int
    {
        $hours = (int) $this->option('hours');
        $now   = now();
        $from  = $now->copy();
        $to    = $now->copy()->addHours($hours);

        $orders = Order::whereNotNull('deadline')
            ->whereBetween('deadline', [$from, $to])
            ->whereNull('deadline_reminder_sent_at')
            ->whereNotIn('status', [Order::STATUS_DONE, Order::STATUS_SHIPPED, Order::STATUS_OVERDUE])
            ->with('customer')
            ->get();

        foreach ($orders as $order) {
            $customer = $order->customer ?? null;
            if (!$customer || !$customer->phone) {
                continue;
            }

            $orderNo = $order->order_code ?? $order->order_no ?? $order->id;
            $deadlineText = $order->deadline ? $order->deadline->format('d M Y H:i') : '-';
            $msg = "Halo {$customer->name}, pengingat: pesanan #{$orderNo} "
                ."dengan deadline {$deadlineText} masih dalam proses. "
                ."Mohon pastikan semua konfirmasi dan file sudah lengkap.";

            if ($wa->sendMessage($customer->phone_e164 ?? $customer->phone, $msg)) {
                $order->deadline_reminder_sent_at = $now;
                $order->save();

                \App\Services\OrderActivityLogger::logFieldChanges(
                    $order,
                    ['deadline_reminder_sent_at' => null],
                    ['deadline_reminder_sent_at' => $now],
                    null,
                    $now->timestamp
                );
            }
        }

        $this->info("Reminders sent for {$orders->count()} orders.");
        return self::SUCCESS;
    }
}
