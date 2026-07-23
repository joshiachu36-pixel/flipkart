<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Role;
use App\Models\User;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(can_do('staff.view'), 403, 'You do not have permission to view staff.');
        $query = Staff::with('role');

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('role', function ($qr) use ($search) {
                      $qr->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filters
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->input('role_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('created_date')) {
            $query->whereDate('created_at', $request->input('created_date'));
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        if ($sort === 'newest') {
            $query->latest();
        } elseif ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'a-z') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'role') {
            $query->join('roles', 'staff.role_id', '=', 'roles.id')
                  ->orderBy('roles.name', 'asc')
                  ->select('staff.*');
        }

        // Pagination
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $staffMembers = $query->paginate($perPage)->withQueryString();
        $roles = Role::where('status', 'Active')->get();

        return view('admin.staff.index', compact('staffMembers', 'roles'));
    }

    public function create()
    {
        abort_unless(can_do('staff.create'), 403, 'You do not have permission to create staff.');
        $roles = Role::where('status', 'Active')->get();
        return view('admin.staff.create', compact('roles'));
    }

    public function store(StoreStaffRequest $request)
    {
        abort_unless(can_do('staff.create'), 403, 'You do not have permission to create staff.');
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        // Profile Photo Upload
        if ($request->hasFile('profile_photo')) {
            $photoName = time() . '_' . uniqid() . '.' . $request->profile_photo->extension();
            $request->profile_photo->move(public_path('uploads/staff'), $photoName);
            $data['profile_photo'] = 'uploads/staff/' . $photoName;
        }

        $staff = new Staff($data);

        if (Auth::check()) {
            $staff->created_by = Auth::id();
            $staff->created_by_type = get_class(Auth::user());
        }

        $staff->save();

        return redirect()->route('admin.staff.index')->with('success', 'Staff created successfully.');
    }

    public function show(Staff $staff)
    {
        $staff->load('role', 'creator', 'updater');
        return view('admin.staff.show', compact('staff'));
    }

    public function edit(Staff $staff)
    {
        abort_unless(can_do('staff.edit'), 403, 'You do not have permission to edit staff.');
        $roles = Role::where('status', 'Active')->get();
        return view('admin.staff.edit', compact('staff', 'roles'));
    }

    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        abort_unless(can_do('staff.edit'), 403, 'You do not have permission to edit staff.');
        $data = $request->validated();

        // Prevent Change Own Role
        if ($staff->id === Auth::id() && Auth::getDefaultDriver() === 'staff') {
            if ((int) $data['role_id'] !== (int) $staff->role_id) {
                return back()->with('error', 'You cannot change your own role.')->withInput();
            }
            if ($data['status'] !== 'Active') {
                return back()->with('error', 'You cannot deactivate or suspend your own account.')->withInput();
            }
        }

        // Handle Password
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Handle Profile Photo Upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($staff->profile_photo && File::exists(public_path($staff->profile_photo))) {
                File::delete(public_path($staff->profile_photo));
            }

            $photoName = time() . '_' . uniqid() . '.' . $request->profile_photo->extension();
            $request->profile_photo->move(public_path('uploads/staff'), $photoName);
            $data['profile_photo'] = 'uploads/staff/' . $photoName;
        }

        $staff->fill($data);

        if (Auth::check()) {
            $staff->updated_by = Auth::id();
            $staff->updated_by_type = get_class(Auth::user());
        }

        $staff->save();

        return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully.');
    }

    public function destroy(Staff $staff)
    {
        abort_unless(can_do('staff.delete'), 403, 'You do not have permission to delete staff.');
        // 1. Prevent Delete own account / currently logged in user
        if ($staff->id === Auth::id() && Auth::getDefaultDriver() === 'staff') {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // 2. Prevent Delete last Super Admin
        if ($staff->role && strtolower($staff->role->name) === 'super admin' && $staff->status === 'Active') {
            $superAdminsCount = User::where('role', 'admin')->count() +
                                Staff::whereHas('role', function ($q) {
                                    $q->where('name', 'Super Admin');
                                })->where('status', 'Active')->count();

            if ($superAdminsCount <= 1) {
                return back()->with('error', 'Cannot delete the last active Super Admin.');
            }
        }

        // Delete profile photo from storage if exists
        if ($staff->profile_photo && File::exists(public_path($staff->profile_photo))) {
            File::delete(public_path($staff->profile_photo));
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff deleted successfully.');
    }
}
