<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        $byStatus = Order::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $from = Carbon::today()->subDays(6);
        $perDay = Order::selectRaw('DATE(created_at) as d, COUNT(*) as total')
            ->whereDate('created_at', '>=', $from)
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        $fromMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $revenue = Order::selectRaw('DATE_FORMAT(created_at,"%Y-%m") as m, SUM(total) as amount')
            ->whereDate('created_at', '>=', $fromMonth)
            ->groupBy('m')
            ->orderBy('m')
            ->get();

        $avgLeadTime = Order::where('status', Order::STATUS_DONE)
            ->whereNotNull('created_at')
            ->whereNotNull('updated_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as hours')
            ->value('hours');

        return view('manager.dashboard', [
            'byStatus'    => $byStatus,
            'perDay'      => $perDay,
            'revenue'     => $revenue,
            'avgLeadTime' => $avgLeadTime,
        ]);
    }
}
