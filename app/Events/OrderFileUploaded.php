<?php

namespace App\Events;

use App\Models\Order;
use App\Models\OrderFile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderFileUploaded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;
    public OrderFile $file;

    public function __construct(Order $order, OrderFile $file)
    {
        $this->order = $order;
        $this->file  = $file;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('order-channel');
        // or per order: return new Channel('order.' . $this->order->id);
    }

    public function broadcastAs(): string
    {
        return 'order.file_uploaded';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id'    => $this->order->id,
            'order_no'    => $this->order->order_code ?? $this->order->order_no ?? null,
            'file_id'     => $this->file->id,
            'file_name'   => $this->file->filename ?? $this->file->name ?? null,
            'file_url'    => $this->file->url ?? null,
            'uploaded_at' => $this->file->created_at?->timestamp,
        ];
    }
}
