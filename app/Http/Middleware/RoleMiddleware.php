<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\StaffUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        /** @var StaffUser|null $user */
        $user = Auth::guard('staff')->user();

        if (!$user) {
            return redirect()->route('staff.login');
        }

        $userRoles = $user->roles()->pluck('name')->toArray();

        if (in_array('superadmin', $userRoles, true)) {
            return $next($request);
        }

        if (empty($roles) || !empty(array_intersect($roles, $userRoles))) {
            return $next($request);
        }

        abort(403, "Access denied. Required role(s): " . implode(', ', $roles));
    }
}
