<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class OrderPaid implements ShouldBroadcast
{
    use SerializesModels;

    public function __construct(public Order $order) {}

    public function broadcastOn(): Channel
    {
        return new Channel('dashboard');
    }

    public function broadcastAs(): string
    {
        return 'order.paid';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'totalOrders' => \App\Models\Order::count(),
            'paidOrders' => \App\Models\Order::where('status', 'paid')->count(),
            'pendingOrders' => \App\Models\Order::where('status', 'pending')->count(),
            'message' => "Order #{$this->order->id} berhasil dibayar",
            'type' => 'success',
        ];
    }
}
