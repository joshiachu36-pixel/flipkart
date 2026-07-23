<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        abort_unless(can_do('roles.view'), 403, 'You do not have permission to view roles.');
        $roles = Role::withCount('staff')->latest()->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        abort_unless(can_do('roles.create'), 403, 'You do not have permission to create roles.');
        return view('admin.roles.create');
    }

    public function store(StoreRoleRequest $request)
    {
        abort_unless(can_do('roles.create'), 403, 'You do not have permission to create roles.');
        $role = new Role($request->validated());
        
        if (Auth::check()) {
            $role->created_by = Auth::id();
            $role->created_by_type = get_class(Auth::user());
        }
        
        $role->save();

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function show(Role $role)
    {
        $role->load('creator', 'updater');
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        abort_unless(can_do('roles.edit'), 403, 'You do not have permission to edit roles.');
        return view('admin.roles.edit', compact('role'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        abort_unless(can_do('roles.edit'), 403, 'You do not have permission to edit roles.');
        $role->fill($request->validated());
        
        if (Auth::check()) {
            $role->updated_by = Auth::id();
            $role->updated_by_type = get_class(Auth::user());
        }
        
        $role->save();

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        abort_unless(can_do('roles.delete'), 403, 'You do not have permission to delete roles.');
        if ($role->isSuperAdmin()) {
            return back()->with('error', 'Super Admin role cannot be deleted.');
        }
        if ($role->staff()->count() > 0) {
            return back()->with('error', 'Cannot delete this role because staff members are assigned to it.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
