<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerAuthController extends Controller
{
    public function showRegister()
    {
        return view('seller.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'business_name'        => 'required|string|max:255',
            'owner_name'           => 'required|string|max:255',
            'email'                => 'required|string|email|max:255|unique:sellers',
            'phone'                => 'required|string|max:20|unique:sellers',
            'password'             => 'required|string|min:8|confirmed',
            'gst_number'           => 'nullable|string|max:255',
            'pan_number'           => 'nullable|string|max:255',
            'business_address'     => 'required|string',
            'business_logo'        => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'bank_name'            => 'nullable|string|max:255',
            'bank_account_number'  => 'nullable|string|max:255',
            'ifsc_code'            => 'nullable|string|max:255',
        ]);

        // Store the uploaded business logo
        $logoPath = $request->file('business_logo')->store('logos', 'public');

        $seller = Seller::create([
            'business_name'        => $validated['business_name'],
            'owner_name'           => $validated['owner_name'],
            'email'                => $validated['email'],
            'phone'                => $validated['phone'],
            'password'             => Hash::make($validated['password']),
            'gst_number'           => $validated['gst_number'],
            'pan_number'           => $validated['pan_number'],
            'business_address'     => $validated['business_address'],
            'business_logo'        => $logoPath,
            'bank_name'            => $validated['bank_name'],
            'bank_account_number'  => $validated['bank_account_number'],
            'ifsc_code'            => $validated['ifsc_code'],
            'status'               => 'Pending',
        ]);

        return redirect()->route('seller.login')->with('success', 'Registration successful. Your account is pending admin approval.');
    }

    public function showLogin()
    {
        return view('seller.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('seller')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('seller.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('seller')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('seller.login');
    }
}
