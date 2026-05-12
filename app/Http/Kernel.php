<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [],
        'api' => [],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // Use framework auth middleware directly
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        // Role middleware alias used in routes (role:superadmin, role:admin, role:petugas)
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ];
}
