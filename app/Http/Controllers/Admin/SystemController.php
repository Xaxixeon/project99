<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SystemController extends Controller
{
    public function index()
    {
        $server = [
            'php_version'       => phpversion(),
            'laravel_version'   => app()->version(),
            'server_software'   => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_usage'      => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
            'disk_total'        => round(@disk_total_space('/') / 1024 / 1024 / 1024, 2) . ' GB',
            'disk_free'         => round(@disk_free_space('/') / 1024 / 1024 / 1024, 2) . ' GB',
            'disk_used_percent' => function () {
                $total = @disk_total_space('/');
                $free  = @disk_free_space('/');
                if ($total <= 0) {
                    return 0;
                }
                return round((1 - $free / $total) * 100, 1);
            },
        ];

        $server['disk_used_percent'] = is_callable($server['disk_used_percent'])
            ? $server['disk_used_percent']()
            : $server['disk_used_percent'];

        return view('admin.system', compact('server'));
    }
}
