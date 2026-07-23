<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if legacy web guard has admin role
        if (auth()->guard('web')->check() && auth()->guard('web')->user()->role === 'admin') {
            return $next($request);
        }

        // 2. Check if staff guard has a role named "Super Admin"
        if (auth()->guard('staff')->check()) {
            $staff = auth()->guard('staff')->user();
            if ($staff->status === 'Active' && $staff->role && strtolower($staff->role->name) === 'super admin') {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized access.');
    }
}
