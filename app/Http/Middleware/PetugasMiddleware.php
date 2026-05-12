<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PetugasMiddleware
{
    /**
     * Handle an incoming request.
     * Middleware untuk Petugas (User)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user (petugas) yang login menggunakan guard 'web'
        if (Auth::guard('web')->check()) {
            return $next($request);
        }

        // Jika bukan petugas atau tidak login, redirect
        return redirect('/login')->with('error', 'Anda harus login terlebih dahulu untuk mengakses halaman ini.');
    }
}
