<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class QueueMonitorController extends Controller
{
    public function index()
    {
        return view('admin.queue-monitor');
    }

    public function poll()
    {
        $jobs = DB::table('jobs')->limit(20)->get();
        $failed = DB::table('failed_jobs')->limit(20)->get();

        $status = 'not running';
        if (function_exists('shell_exec')) {
            $output = @shell_exec('pgrep -f "queue:work"');
            if (!empty(trim((string) $output))) {
                $status = 'running';
            }
        }

        $worker = [
            'status'    => $status,
            'memory'    => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
            'timestamp' => now()->format('H:i:s'),
        ];

        return response()->json([
            'jobs'   => $jobs,
            'failed' => $failed,
            'worker' => $worker,
        ]);
    }
}
