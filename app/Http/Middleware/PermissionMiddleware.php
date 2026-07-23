<?php

namespace App\Http\Middleware;

use App\Services\PermissionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * PermissionMiddleware
 *
 * Usage: ->middleware('permission:products.view')
 *
 * - Legacy web admin (User with role='admin') → always passes.
 * - Staff with Super Admin role → always passes.
 * - All other staff → permission slug checked against role permissions.
 * - Not authenticated as admin/staff → 403.
 */
class PermissionMiddleware
{
    public function __construct(protected PermissionService $permissionService)
    {
    }

    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Must be authenticated as web admin or staff
        $isWebAdmin = auth()->guard('web')->check()
            && auth()->guard('web')->user()->role === 'admin';

        $isStaff = auth()->guard('staff')->check()
            && auth()->guard('staff')->user()->status === 'Active';

        if (!$isWebAdmin && !$isStaff) {
            abort(403, 'Unauthorized.');
        }

        // Check the permission
        if ($this->permissionService->hasPermission($permission)) {
            return $next($request);
        }

        abort(403, "You do not have permission to perform this action. Required: {$permission}");
    }
}
