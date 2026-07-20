<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Modes:
     *   - 'auth-only'  → only checks that the seller is logged in (any status).
     *   - default       → checks logged in AND status === 'Approved'.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $mode
     */
    public function handle(Request $request, Closure $next, string $mode = 'full'): Response
    {
        if (!auth()->guard('seller')->check()) {
            return redirect()->route('seller.login');
        }

        // Auth-only mode: seller is logged in, no status check
        if ($mode === 'auth-only') {
            return $next($request);
        }

        // Full mode: seller must be Approved
        $seller = auth()->guard('seller')->user();

        if ($seller->status !== 'Approved') {
            return redirect()->route('seller.dashboard');
        }

        return $next($request);
    }
}
