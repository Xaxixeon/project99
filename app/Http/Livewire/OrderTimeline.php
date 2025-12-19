<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class OrderTimeline extends Component
{
    public Order $order;
    public $logs = [];

    protected $listeners = [
        'echo:order-channel,order.activity_logged' => 'refreshIfMatch',
    ];

    public function mount(Order $order): void
    {
        $this->order = $order;
        $this->loadLogs();
    }

    public function loadLogs(): void
    {
        $key = "timeline:order:{$this->order->id}";
        $this->logs = Cache::tags(['order_timeline'])->remember(
            $key,
            now()->addSeconds(15),
            fn () => $this->order->activityLogs()->with('staff')->latest()->get()
        );
    }

    public function refreshIfMatch($payload): void
    {
        if ((int) ($payload['order_id'] ?? 0) === $this->order->id) {
            $this->loadLogs();
        }
    }

    public function render()
    {
        return view('livewire.order-timeline');
    }
}
