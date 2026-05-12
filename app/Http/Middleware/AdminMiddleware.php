<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Middleware untuk Admin
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah administrator dengan role admin yang login
        if (Auth::guard('administrator')->check()) {
            $admin = Auth::guard('administrator')->user();
            if ($admin->role_admin === 'admin') {
                return $next($request);
            }
        }

        // Jika bukan admin, redirect
        return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
