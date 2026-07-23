<?php

namespace App\View\Composers;

use App\Services\PermissionService;
use Illuminate\View\View;

/**
 * SidebarComposer
 *
 * Injects permission data into the admin layout view.
 * This is the only place where PermissionService is queried for sidebar rendering —
 * no permission logic lives in the blade template itself.
 */
class SidebarComposer
{
    public function __construct(protected PermissionService $permissionService)
    {
    }

    public function compose(View $view): void
    {
        $isSuperAdmin = $this->permissionService->isSuperAdmin();

        $roleName        = 'Super Admin';
        $permissionSlugs = collect();

        if (!$isSuperAdmin && auth()->guard('staff')->check()) {
            $staff = auth()->guard('staff')->user();
            if ($staff->role_id) {
                $permissionSlugs = $this->permissionService->getPermissionSlugsForRole($staff->role_id);
                $roleName        = $staff->role ? $staff->role->name : 'Staff';
            } else {
                $roleName        = 'Staff';
            }
        }

        $view->with([
            'sidebarIsSuperAdmin' => $isSuperAdmin,
            'sidebarPermissions'  => $permissionSlugs,
            'sidebarRoleName'     => $roleName,
        ]);
    }
}
