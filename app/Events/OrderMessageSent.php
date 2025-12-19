<?php

namespace App\Events;

use App\Models\Order;
use App\Models\OrderChat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;
    public OrderChat $message;

    public function __construct(Order $order, OrderChat $message)
    {
        $this->order   = $order;
        $this->message = $message;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('order-channel');
    }

    public function broadcastAs(): string
    {
        return 'order.message_sent';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id'    => $this->order->id,
            'order_no'    => $this->order->order_code ?? $this->order->order_no ?? null,
            'message_id'  => $this->message->id,
            'sender_name' => $this->message->sender_name ?? null,
            'body'        => $this->message->message ?? $this->message->body ?? null,
            'created_at'  => $this->message->created_at?->timestamp,
        ];
    }
}
