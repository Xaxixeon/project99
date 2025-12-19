<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPriorityChanged implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('order-channel');
    }

    public function broadcastAs(): string
    {
        return 'order.priority_changed';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}
