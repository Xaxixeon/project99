<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DesignerController extends Controller
{
    public function index()
    {
        return view('panel.designer.dashboard', [
            'designQueue' => Order::where('status', 'designing')->latest()->get(),
            'designDone'  => Order::where('status', 'design_done')->latest()->get()
        ]);
    }

    public function revisions()
    {
        // Asumsi file_manager sudah ada
        $files = \DB::table('file_manager')
            ->where('user_id', auth('staff')->id())
            ->latest()
            ->get();

        return view('panel.designer.revisions', compact('files'));
    }
}
