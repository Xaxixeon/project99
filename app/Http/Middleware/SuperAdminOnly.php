<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('staff')->user();

        // Jika belum login staff
        if (!$user) {
            return redirect()->route('staff.login');
        }

        // Cek apakah punya role super_admin
        if (!$user->roles()->where('name', 'superadmin')->exists()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
