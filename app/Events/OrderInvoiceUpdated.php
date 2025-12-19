<?php

namespace App\Events;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderInvoiceUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;
    public Invoice $invoice;

    public function __construct(Order $order, Invoice $invoice)
    {
        $this->order   = $order;
        $this->invoice = $invoice;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('order-channel');
    }

    public function broadcastAs(): string
    {
        return 'order.invoice_updated';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id'   => $this->order->id,
            'order_no'   => $this->order->order_code ?? $this->order->order_no ?? null,
            'invoice_id' => $this->invoice->id,
            'status'     => $this->invoice->status ?? null,
            'amount'     => isset($this->invoice->amount) ? (float) $this->invoice->amount : null,
            'updated_at' => $this->invoice->updated_at?->timestamp,
        ];
    }
}
