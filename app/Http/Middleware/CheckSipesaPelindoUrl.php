<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSipesaPelindoUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Jika response adalah 404 dan URL dimulai dengan 'sipesapelindo'
        if ($response->getStatusCode() === 404 && str_starts_with($request->path(), 'sipesapelindo')) {
            return response()->view('errors.403', [], 403);
        }
        
        return $response;
    }
}
