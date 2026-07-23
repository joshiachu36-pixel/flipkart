<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PermissionService;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // Ensure 100% session isolation by clearing any lingering authentication state
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 1. Attempt Super Admin login (default web guard)
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            if (Auth::guard('web')->user()->role === 'admin') {
                app(PermissionService::class)->clearAllRuntimeCache();
                return redirect()->route('admin.sellers.index')->with('success', 'Admin logged in successfully.');
            } else {
                Auth::guard('web')->logout();
                return back()->with('error', 'You do not have admin access.');
            }
        }

        // 2. Attempt Staff login (staff guard)
        if (Auth::guard('staff')->attempt($credentials)) {
            $staff = Auth::guard('staff')->user();
            if ($staff->status === 'Active') {
                $request->session()->regenerate();
                
                // Update audit fields & load permissions
                $staff->forceFill([
                    'last_login_at' => now(),
                    'login_count'   => $staff->login_count + 1,
                ])->save();

                app(PermissionService::class)->clearAllRuntimeCache();

                if ($staff->role_id) {
                    app(PermissionService::class)->getPermissionSlugsForRole($staff->role_id);
                }

                return redirect()->route('admin.sellers.index')->with('success', 'Logged in successfully.');
            } else {
                Auth::guard('staff')->logout();
                $statusMessage = $staff->status === 'Suspended' ? 'suspended' : 'inactive';
                return back()->with('error', "Your account is {$statusMessage}.");
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Logout both web and staff guards to guarantee zero cross-guard session leakage
        Auth::guard('web')->logout();
        Auth::guard('staff')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        app(PermissionService::class)->clearAllRuntimeCache();

        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }
}
