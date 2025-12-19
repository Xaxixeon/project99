<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Campaign;

class MarketingDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.marketing.index', [
            'leads'          => Lead::count(),
            'conversion'     => Lead::where('status', 'converted')->count(),
            'activeCampaign' => Campaign::where('is_active', true)->count(),
            'recentLeads'    => Lead::latest()->take(10)->get(),
            'newLeads'   => 0,
            'conversion' => 0,
            'campaign'   => 0,
            'campaigns'  => [],
        ]);
    }
}
