<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;
    public ?string $previousStatus;

    public function __construct(Order $order, ?string $previousStatus = null)
    {
        $this->order = $order;
        $this->previousStatus = $previousStatus;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('order-channel');
    }

    public function broadcastAs(): string
    {
        return 'order.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'id'                => $this->order->id,
            'order_no'          => $this->order->order_code ?? $this->order->order_no ?? null,
            'status'            => $this->order->status,
            'previous_status'   => $this->previousStatus,
            'title'             => $this->order->title ?? null,
            'product'           => $this->order->product ?? null,
            'deadline'          => optional($this->order->deadline)->toDateString(),
            'total'             => isset($this->order->total) ? (float) $this->order->total : null,
            'updated_at'        => $this->order->updated_at?->timestamp,
            'updated_at_client' => $this->order->updated_at_client?->timestamp,
        ];
    }
}
