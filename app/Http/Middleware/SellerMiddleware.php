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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->guard('seller')->check()) {
            return redirect()->route('seller.login');
        }

        $seller = auth()->guard('seller')->user();

        if ($seller->status !== 'Approved') {
            abort(403, 'Your seller account is pending approval, rejected, or suspended.');
        }

        return $next($request);
    }
}
