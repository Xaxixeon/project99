<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StaffRoleMiddleware
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles   // daftar role yang diizinkan
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        /** @var \App\Models\User|null $staff */
        $staff = Auth::guard('staff')->user();

        // kalau belum login sebagai staff -> lempar ke halaman login staff
        if (! $staff) {
            return redirect()->route('staff.login');
        }

        // ambil daftar nama role user
        $staffRoles = method_exists($staff, 'roleNames')
            ? $staff->roleNames()
            : array_filter([$staff->role ?? null]);

        // superadmin boleh semuanya
        if (in_array('superadmin', $staffRoles, true)) {
            return $next($request);
        }

        // kalau middleware tidak diberi parameter role -> cukup login staff saja
        if (empty($roles)) {
            return $next($request);
        }

        // cek irisan antara role yang diminta dan role yang dimiliki staff
        if (! empty(array_intersect($roles, $staffRoles))) {
            return $next($request);
        }

        abort(403, 'Access denied: Required role ' . implode(',', $roles));
    }
}
