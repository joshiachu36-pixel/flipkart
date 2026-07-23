<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * PermissionService
 *
 * Central authority for all permission resolution.
 * Uses in-memory + cache to avoid repeated DB queries per request.
 */
class PermissionService
{
    /** @var array<int, Collection> In-memory per-request cache keyed by role_id */
    private static array $runtimeCache = [];

    // ─── Core Permission Checks ───────────────────────────────────────────────

    /**
     * Check if the currently authenticated user (web admin or staff) is a Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        // Legacy web guard User with role='admin' is always Super Admin
        if (auth()->guard('web')->check() && auth()->guard('web')->user()->role === 'admin') {
            return true;
        }

        // Staff guard - check if their role is Super Admin
        if (auth()->guard('staff')->check()) {
            $staff = auth()->guard('staff')->user();
            return $staff->isSuperAdmin();
        }

        return false;
    }

    /**
     * Check if the currently authenticated staff member has the given permission.
     * Web guard admins (Super Admins) always return true.
     */
    public function hasPermission(string $slug): bool
    {
        // Legacy web admin → always granted
        if (auth()->guard('web')->check() && auth()->guard('web')->user()->role === 'admin') {
            return true;
        }

        if (!auth()->guard('staff')->check()) {
            return false;
        }

        $staff = auth()->guard('staff')->user();

        // Super Admin staff → always granted
        if ($staff->isSuperAdmin()) {
            return true;
        }

        if (!$staff->role_id) {
            return false;
        }

        return $this->getPermissionSlugsForRole($staff->role_id)->contains($slug);
    }

    // ─── Permission Loading ────────────────────────────────────────────────────

    /**
     * Get all permission slugs for a given role_id.
     * Uses per-request memory cache + persistent Cache to avoid DB hits.
     */
    public function getPermissionSlugsForRole(int $roleId): Collection
    {
        if (isset(static::$runtimeCache[$roleId])) {
            return static::$runtimeCache[$roleId];
        }

        $slugsArray = Cache::remember(
            "rbac.role_permissions.{$roleId}",
            now()->addMinutes(60),
            function () use ($roleId) {
                return Permission::whereHas('roles', function ($q) use ($roleId) {
                    $q->where('roles.id', $roleId);
                })->pluck('slug')->toArray();
            }
        );

        if (!is_array($slugsArray)) {
            $slugsArray = [];
        }

        $slugs = collect($slugsArray);

        static::$runtimeCache[$roleId] = $slugs;

        return $slugs;
    }

    /**
     * Get all permissions for a role, keyed by module, for the UI.
     * Returns: ['products' => Collection<Permission>, 'categories' => Collection<Permission>, ...]
     */
    public function getGroupedPermissions(): array
    {
        return Permission::orderBy('sort_order')->orderBy('name')
            ->get()
            ->groupBy('module')
            ->toArray();
    }

    /**
     * Get permissions collection for a role (full model objects for the UI).
     */
    public function getPermissionsForRole(int $roleId): Collection
    {
        return Permission::whereHas('roles', function ($q) use ($roleId) {
            $q->where('roles.id', $roleId);
        })->pluck('id');
    }

    // ─── Cache Invalidation ───────────────────────────────────────────────────

    /**
     * Clear the cached permissions for a specific role.
     * Call this after any permission update.
     */
    public function clearCacheForRole(int $roleId): void
    {
        Cache::forget("rbac.role_permissions.{$roleId}");
        unset(static::$runtimeCache[$roleId]);
    }

    /**
     * Flush all in-memory runtime permission cache.
     * Call this during session logout.
     */
    public function clearAllRuntimeCache(): void
    {
        static::$runtimeCache = [];
    }

    // ─── Grouped Permissions for UI ───────────────────────────────────────────

    /**
     * Get all permissions grouped by module as a Collection.
     * Returns: Collection where keys = module name, values = Collection<Permission>
     */
    public function getAllGrouped(): Collection
    {
        return Permission::orderBy('sort_order')->orderBy('name')
            ->get()
            ->groupBy('module');
    }

    /**
     * Get the set of permission IDs assigned to a role, as a flat array.
     */
    public function getAssignedPermissionIds(int $roleId): array
    {
        return Permission::whereHas('roles', function ($q) use ($roleId) {
            $q->where('roles.id', $roleId);
        })->pluck('id')->toArray();
    }
}
