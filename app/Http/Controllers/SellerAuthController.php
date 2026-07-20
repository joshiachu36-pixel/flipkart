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

        // Notify admin about new seller application
        $this->notifyAdmin('new_application',
            "New seller application received from \"{$seller->business_name}\" ({$seller->owner_name})."
        );

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

    // ── Resubmit Application ────────────────────────────────────────────────

    public function showResubmit()
    {
        $seller = Auth::guard('seller')->user();

        if (!$seller->isRejected()) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'You can only resubmit when your application has been rejected.');
        }

        return view('seller.auth.resubmit', compact('seller'));
    }

    public function resubmit(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        if (!$seller->isRejected()) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'You can only resubmit when your application has been rejected.');
        }

        $validated = $request->validate([
            'business_name'        => 'required|string|max:255',
            'owner_name'           => 'required|string|max:255',
            'gst_number'           => 'nullable|string|max:255',
            'pan_number'           => 'nullable|string|max:255',
            'business_address'     => 'required|string',
            'business_logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'bank_name'            => 'nullable|string|max:255',
            'bank_account_number'  => 'nullable|string|max:255',
            'ifsc_code'            => 'nullable|string|max:255',
        ]);

        // Handle logo upload — preserve existing if no new one provided
        if ($request->hasFile('business_logo')) {
            // Delete old logo
            if ($seller->business_logo) {
                Storage::disk('public')->delete($seller->business_logo);
            }
            $validated['business_logo'] = $request->file('business_logo')->store('logos', 'public');
        }

        // Reset status back to Pending and clear rejection data
        $validated['status']           = 'Pending';
        $validated['rejection_reason'] = null;
        $validated['rejected_at']      = null;

        $seller->update($validated);

        // Notify admin about resubmission
        $this->notifyAdmin('resubmission',
            "Seller \"{$seller->business_name}\" ({$seller->owner_name}) has resubmitted their application for review."
        );

        return redirect()->route('seller.dashboard')
            ->with('success', 'Your application has been resubmitted successfully. It is now under admin review.');
    }

    // ── Private Helpers ─────────────────────────────────────────────────────

    private function notifyAdmin(string $type, string $message): void
    {
        $key      = 'admin_seller_notification';
        $existing = cache()->get($key, []);

        $existing[] = ['type' => $type, 'message' => $message];

        cache()->put($key, $existing, now()->addDays(7));
    }
}
