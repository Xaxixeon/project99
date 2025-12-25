<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Task;
use Carbon\Carbon;

class ManagerController extends Controller
{
    public function index()
    {
        return view('panel.manager.dashboard', [
            'allOrders' => Order::latest()->take(30)->get(),
            'rolesSummary' => User::with('roles')->get()
        ]);
    }
}

class DashboardController extends Controller
{
    public function overview()
    {
        return view('dashboard.overview', [
            'kpi'        => $this->kpi(),
            'taskStatus' => $this->taskStatus(),
            'orderFlow'  => $this->orderFlow(),
            'byDivision' => $this->taskByDivision(),
            'deadline'   => $this->deadlineAlert(),
        ]);
    }

    protected function kpi(): array
    {
        return [
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'task_active'  => Task::whereNotIn('status', ['done'])->count(),
            'task_done'    => Task::where('status', 'done')->count(),
            'late_task'    => Task::where('deadline', '<', today())
                ->where('status', '!=', 'done')
                ->count(),
        ];
    }

    protected function taskStatus()
    {
        return Task::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
    }

    protected function orderFlow()
    {
        return Order::selectRaw('order_status, COUNT(*) as total')
            ->groupBy('order_status')
            ->pluck('total', 'order_status');
    }

    protected function taskByDivision()
    {
        return Task::selectRaw('role as division, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'division');
    }

    protected function deadlineAlert()
    {
        return Task::where('deadline', '<=', now()->addDays(2))
            ->where('status', '!=', 'done')
            ->orderBy('deadline')
            ->limit(5)
            ->get();
    }
}
