<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Get the required roles from the route
        $as = $this->getRequiredRoleAsForRoute($request->route());
        // Check if a role is required for the route, and
        // if so, ensure that the user has that role.
        
        if ($request->user()->hasPermissions($as)) {
            return $next($request);
        }

        return response([
            'error' => [
                'code' => 'INSUFFICIENT_ROLE',
                'description' => '您无权访问本页面',
            ],
        ], 401);
    }

    private function getRequiredRoleAsForRoute($route)
    {
        $actions = $route->getAction();

        return isset($actions['as']) ? $actions['as'] : null;
    }
}
