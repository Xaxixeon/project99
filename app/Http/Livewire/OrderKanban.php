<?php

namespace App\Http\Livewire;

use App\Events\OrderPriorityChanged;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class OrderKanban extends Component
{
    public array $statuses = [];
    public array $columns = [];
    public string $mode = 'default';
    public ?string $search = null;
    public ?string $statusFilter = null;
    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public int $perStatusLimit = 200;

    protected $queryString = ['search', 'statusFilter', 'dateFrom', 'dateTo'];

    protected $listeners = [
        'echo:order-channel,order.updated' => 'refreshBoard',
        'echo:order-channel,order.priority_changed' => 'refreshBoard',
    ];

    public function mount(): void
    {
        $this->statuses = Order::STATUSES;
        $this->loadBoard();
    }

    public function refreshBoard(): void
    {
        $this->loadBoard();
    }

    public function loadBoard(): void
    {
        $cacheKey = $this->makeCacheKey();

        $this->columns = Cache::tags(['order_board'])->remember($cacheKey, now()->addSeconds(15), function () {
            $columns = [];

            foreach ($this->statuses as $status) {
                if ($this->statusFilter && $status !== $this->statusFilter) {
                    $columns[$status] = [];
                    continue;
                }

                $query = Order::with('customer')->where('status', $status);

                if ($this->search) {
                    $s = '%' . $this->search . '%';
                    $query->where(function ($q) use ($s) {
                        $q->where('order_no', 'like', $s)
                          ->orWhere('title', 'like', $s)
                          ->orWhere('product', 'like', $s);
                    });
                }

                if ($this->dateFrom) {
                    $query->whereDate('created_at', '>=', $this->dateFrom);
                }
                if ($this->dateTo) {
                    $query->whereDate('created_at', '<=', $this->dateTo);
                }

                $orders = $query
                    ->orderBy('sort_order')
                    ->orderBy('deadline')
                    ->limit($this->perStatusLimit)
                    ->get();

                $columns[$status] = $orders->map(fn($o) => [
                    'id'         => $o->id,
                    'order_no'   => $o->order_code ?? $o->order_no ?? $o->id,
                    'title'      => $o->title,
                    'product'    => $o->product,
                    'deadline'   => optional($o->deadline)->toDateString(),
                    'status'     => $o->status,
                    'sla_status' => $o->sla_status,
                    'customer'   => $o->customer?->name,
                ])->values()->toArray();
            }

            return $columns;
        });
    }

    protected function makeCacheKey(): string
    {
        return 'board:' . md5(json_encode([
            'statuses'     => $this->statuses,
            'search'       => $this->search,
            'statusFilter' => $this->statusFilter,
            'dateFrom'     => $this->dateFrom,
            'dateTo'       => $this->dateTo,
            'limit'        => $this->perStatusLimit,
            'mode'         => $this->mode,
        ]));
    }

    public function moveToStatus(int $orderId, string $toStatus): void
    {
        $order = Order::findOrFail($orderId);
        request()->merge([
            'status'     => $toStatus,
            'updated_at' => now()->timestamp,
        ]);
        app(\App\Http\Controllers\OrderController::class)->updateStatus(request(), $order);
        $this->loadBoard();
    }

    public function reorderColumn(string $status, array $orderedIds): void
    {
        foreach ($orderedIds as $sort => $id) {
            Order::where('id', $id)->where('status', $status)->update(['sort_order' => $sort]);
        }
        broadcast(new OrderPriorityChanged([
            'status'    => $status,
            'order_ids' => $orderedIds,
        ]))->toOthers();
        $this->loadBoard();
    }

    public function render()
    {
        return view('livewire.order-kanban');
    }
}
