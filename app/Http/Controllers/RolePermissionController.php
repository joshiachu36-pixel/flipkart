<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    public function __construct(protected PermissionService $permissionService)
    {
    }

    /**
     * Show the permission management page for a specific role.
     */
    public function edit(Role $role)
    {
        // Super Admin role permissions are immutable
        $isSuperAdminRole = $role->isSuperAdmin();

        // All permissions grouped by module for the UI
        $allPermissions = $this->permissionService->getAllGrouped();

        // Currently assigned permission IDs for this role
        $assignedPermissionIds = $this->permissionService->getAssignedPermissionIds($role->id);

        return view('admin.roles.permissions', compact(
            'role',
            'allPermissions',
            'assignedPermissionIds',
            'isSuperAdminRole'
        ));
    }

    /**
     * Update permissions for a role.
     */
    public function update(Request $request, Role $role)
    {
        // Block Super Admin role from being modified
        if ($role->isSuperAdmin()) {
            return back()->with('error', 'Super Admin permissions cannot be modified.');
        }

        $newPermissionIds = $request->input('permissions', []);

        // Validate all submitted IDs are real permission IDs
        $validIds = Permission::whereIn('id', $newPermissionIds)->pluck('id')->toArray();

        DB::transaction(function () use ($role, $validIds) {
            // Get previously assigned IDs for audit log
            $previousIds = $this->permissionService->getAssignedPermissionIds($role->id);

            // Sync the many-to-many pivot
            $role->permissions()->sync($validIds);

            // Calculate diff for audit log
            $added   = array_diff($validIds, $previousIds);
            $removed = array_diff($previousIds, $validIds);

            if (!empty($added) || !empty($removed)) {
                $addedSlugs   = Permission::whereIn('id', $added)->pluck('slug')->toArray();
                $removedSlugs = Permission::whereIn('id', $removed)->pluck('slug')->toArray();

                $changer     = null;
                $changerType = null;
                $changerName = null;

                if (Auth::guard('web')->check()) {
                    $changer     = Auth::guard('web')->id();
                    $changerType = get_class(Auth::guard('web')->user());
                    $changerName = Auth::guard('web')->user()->name ?? 'Web Admin';
                } elseif (Auth::guard('staff')->check()) {
                    $changer     = Auth::guard('staff')->id();
                    $changerType = get_class(Auth::guard('staff')->user());
                    $changerName = Auth::guard('staff')->user()->name;
                }

                DB::table('permission_audit_logs')->insert([
                    'role_id'             => $role->id,
                    'role_name'           => $role->name,
                    'changed_by'          => $changer,
                    'changed_by_type'     => $changerType,
                    'changed_by_name'     => $changerName,
                    'permissions_added'   => json_encode($addedSlugs),
                    'permissions_removed' => json_encode($removedSlugs),
                    'created_at'          => now(),
                ]);
            }
        });

        // Invalidate the permission cache for this role
        $this->permissionService->clearCacheForRole($role->id);

        return back()->with('success', 'Permissions updated successfully for "' . $role->name . '".');
    }
}
