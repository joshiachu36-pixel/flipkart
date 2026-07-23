<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    
    public function handle(Request $request, Closure $next)
    {
        if (auth()->guard('web')->check() && auth()->guard('web')->user()->role === 'admin') {
            auth()->shouldUse('web');
            return $next($request);
        }

        if (auth()->guard('staff')->check()) {
            $staff = auth()->guard('staff')->user();
            if ($staff->status === 'Active') {
                auth()->shouldUse('staff');
                return $next($request);
            } else {
                auth()->guard('staff')->logout();
                $statusMessage = $staff->status === 'Suspended' ? 'suspended' : 'inactive';
                return redirect()->route('admin.login')->with('error', "Your account is {$statusMessage}.");
            }
        }

        abort(403);
    }
}

