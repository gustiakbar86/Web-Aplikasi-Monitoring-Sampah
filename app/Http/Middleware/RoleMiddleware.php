<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            // Not authenticated - redirect to login
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Support both numeric role codes (seeders use 1/2/3) and string role names
        $map = [
            1 => 'superadmin',
            2 => 'admin',
            3 => 'petugas',
        ];

        $userRole = null;

        if (isset($user->role)) {
            // If role is numeric, map to name
            if (is_numeric($user->role)) {
                $int = (int) $user->role;
                $userRole = $map[$int] ?? null;
            } else {
                // assume string name stored
                $userRole = (string) $user->role;
            }
        }

        // If user role doesn't match required role, deny access
        if ($userRole !== $role) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
