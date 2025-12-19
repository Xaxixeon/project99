<?php

use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

if (! function_exists('log_activity')) {
    function log_activity(string $action, ?string $description = null): void
    {
        $user = Auth::guard('staff')->user();

        ActivityLog::create([
            'staff_user_id' => $user?->id,
            'action'        => $action,
            'description'   => $description,
            'ip_address'    => request()->ip(),
        ]);
    }
}
