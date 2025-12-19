<?php

namespace App\Events;

use App\Models\OrderActivityLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderActivityLogged implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public OrderActivityLog $log;

    public function __construct(OrderActivityLog $log)
    {
        $this->log = $log;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('order-channel');
    }

    public function broadcastAs(): string
    {
        return 'order.activity_logged';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id'    => $this->log->order_id,
            'from_status' => $this->log->from_status,
            'to_status'   => $this->log->to_status,
            'note'        => $this->log->note,
            'before'      => $this->log->before_payload,
            'after'       => $this->log->after_payload,
            'staff'       => optional($this->log->staff)->name,
            'created_at'  => $this->log->created_at?->timestamp,
        ];
    }
}
