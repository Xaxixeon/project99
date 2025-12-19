<?php

namespace App\Services;

use App\Events\OrderActivityLogged;
use App\Models\Order;
use App\Models\OrderActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class OrderActivityLogger
{
    public static function logTransition(
        Order $order,
        ?string $from,
        string $to,
        ?int $staffId,
        ?string $note = null,
        ?int $clientTs = null
    ): void {
        $log = OrderActivityLog::create([
            'order_id'         => $order->id,
            'staff_id'         => $staffId,
            'from_status'      => $from,
            'to_status'        => $to,
            'note'             => $note,
            'client_timestamp' => $clientTs ? Carbon::createFromTimestamp($clientTs) : null,
            'before_payload'   => ['status' => $from],
            'after_payload'    => ['status' => $to],
        ]);
        Cache::tags(['order_timeline'])->forget("timeline:order:{$order->id}");
        event(new OrderActivityLogged($log));
    }

    /**
     * Log perubahan field non-status (title, deadline, total, dll.).
     *
     * @param  Order $order
     * @param  array $original  Data sebelum perubahan (biasanya $order->getOriginal())
     * @param  array $changes   Perubahan (biasanya $order->getChanges())
     */
    public static function logFieldChanges(
        Order $order,
        array $original,
        array $changes,
        ?int $staffId,
        ?int $clientTs = null
    ): void {
        $loggableFields = [
            'title',
            'product',
            'deadline',
            'total',
            'lifecycle',
            'customer_id',
        ];

        $beforePayload = [];
        $afterPayload  = [];

        foreach ($loggableFields as $field) {
            if (!array_key_exists($field, $changes)) {
                continue;
            }
            $beforePayload[$field] = $original[$field] ?? null;
            $afterPayload[$field]  = $changes[$field];
        }

        if (empty($beforePayload) && empty($afterPayload)) {
            return;
        }

        $log = OrderActivityLog::create([
            'order_id'         => $order->id,
            'staff_id'         => $staffId,
            'from_status'      => null,
            'to_status'        => null,
            'note'             => null,
            'client_timestamp' => $clientTs ? Carbon::createFromTimestamp($clientTs) : null,
            'before_payload'   => $beforePayload,
            'after_payload'    => $afterPayload,
        ]);
        Cache::tags(['order_timeline'])->forget("timeline:order:{$order->id}");
        event(new OrderActivityLogged($log));
    }

    /**
     * Log perubahan prioritas (reorder) dalam status yang sama.
     */
    public static function logReorder(
        Order $order,
        int $oldPosition,
        int $newPosition,
        ?int $staffId,
        ?int $clientTs = null
    ): void {
        $log = OrderActivityLog::create([
            'order_id'         => $order->id,
            'staff_id'         => $staffId,
            'from_status'      => null,
            'to_status'        => null,
            'note'             => 'priority_changed',
            'client_timestamp' => $clientTs ? Carbon::createFromTimestamp($clientTs) : null,
            'before_payload'   => ['sort_order' => $oldPosition],
            'after_payload'    => ['sort_order' => $newPosition],
        ]);
        Cache::tags(['order_timeline'])->forget("timeline:order:{$order->id}");
        event(new OrderActivityLogged($log));
    }
}
