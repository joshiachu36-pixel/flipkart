<?php

use App\Services\PermissionService;

if (! function_exists('can_do')) {
    /**
     * Check if the currently authenticated admin/staff user has the given permission slug.
     *
     * Usage:
     *   PHP:   can_do('products.approve')
     *   Blade: @if(can_do('sellers.view'))
     *
     * Super Admin (web guard with role='admin') and staff with Super Admin role always return true.
     *
     * @param  string  $slug   The permission slug, e.g. 'products.approve'
     * @return bool
     */
    function can_do(string $slug): bool
    {
        return app(PermissionService::class)->hasPermission($slug);
    }
}

if (! function_exists('is_super_admin')) {
    /**
     * Check if the currently authenticated user is a Super Admin.
     *
     * @return bool
     */
    function is_super_admin(): bool
    {
        return app(PermissionService::class)->isSuperAdmin();
    }
}
