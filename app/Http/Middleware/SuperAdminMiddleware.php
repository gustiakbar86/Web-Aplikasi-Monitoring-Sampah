<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     * Middleware untuk Super Admin
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah administrator dengan role super_admin yang login
        if (Auth::guard('administrator')->check()) {
            $admin = Auth::guard('administrator')->user();
            if ($admin->role_admin === 'super_admin') {
                return $next($request);
            }
        }

        // Jika bukan super admin, redirect
        return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
