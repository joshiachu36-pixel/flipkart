<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;

class AdminSellerController extends Controller
{
    // ── Index — list all sellers with filter & search ────────────────────────

    public function index(Request $request)
    {
        $query = Seller::query();

        // Status filter
        if ($request->filled('status') && in_array($request->status, ['Pending', 'Approved', 'Rejected', 'Suspended'])) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'LIKE', "%{$search}%")
                  ->orWhere('owner_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $sellers = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'all'       => Seller::count(),
            'pending'   => Seller::where('status', 'Pending')->count(),
            'approved'  => Seller::where('status', 'Approved')->count(),
            'rejected'  => Seller::where('status', 'Rejected')->count(),
            'suspended' => Seller::where('status', 'Suspended')->count(),
        ];

        return view('admin.sellers.index', compact('sellers', 'counts'));
    }

    // ── Show — full seller application review page ──────────────────────────

    public function show(Seller $seller)
    {
        $seller->loadCount('products');

        return view('admin.sellers.show', compact('seller'));
    }

    // ── Approve — activate seller account ───────────────────────────────────

    public function approve(Seller $seller)
    {
        if ($seller->isApproved()) {
            return back()->with('error', 'This seller is already approved.');
        }

        $seller->update([
            'status'            => 'Approved',
            'rejection_reason'  => null,
            'rejected_at'       => null,
            'suspension_reason' => null,
            'suspended_at'      => null,
        ]);

        $this->notifySeller($seller, 'approved',
            "Congratulations! Your seller application for \"{$seller->business_name}\" has been approved. You now have full access to the Seller Dashboard."
        );

        return back()->with('success', "Seller \"{$seller->business_name}\" has been approved.");
    }

    // ── Reject — requires mandatory reason ──────────────────────────────────

    public function reject(Request $request, Seller $seller)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:1000',
        ], [
            'rejection_reason.required' => 'A rejection reason is required.',
            'rejection_reason.min'      => 'Please provide a more detailed reason (at least 10 characters).',
        ]);

        $seller->update([
            'status'           => 'Rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'rejected_at'      => now(),
        ]);

        $this->notifySeller($seller, 'rejected',
            "Your seller application has been rejected. Reason: {$validated['rejection_reason']}"
        );

        return back()->with('success', "Seller \"{$seller->business_name}\" has been rejected with a reason.");
    }

    // ── Suspend — only for approved sellers ─────────────────────────────────

    public function suspend(Request $request, Seller $seller)
    {
        if (!$seller->isApproved()) {
            return back()->with('error', 'Only approved sellers can be suspended.');
        }

        $validated = $request->validate([
            'suspension_reason' => 'nullable|string|max:1000',
        ]);

        $seller->update([
            'status'            => 'Suspended',
            'suspension_reason' => $validated['suspension_reason'] ?? null,
            'suspended_at'      => now(),
        ]);

        $this->notifySeller($seller, 'suspended',
            "Your seller account has been suspended." .
            ($validated['suspension_reason'] ? " Reason: {$validated['suspension_reason']}" : '')
        );

        return back()->with('success', "Seller \"{$seller->business_name}\" has been suspended.");
    }

    // ── Restore — reactivate a suspended seller ─────────────────────────────

    public function restore(Seller $seller)
    {
        if (!$seller->isSuspended()) {
            return back()->with('error', 'Only suspended sellers can be restored.');
        }

        $seller->update([
            'status'            => 'Approved',
            'suspension_reason' => null,
            'suspended_at'      => null,
        ]);

        $this->notifySeller($seller, 'restored',
            "Your seller account has been restored. You now have full access to the Seller Dashboard again."
        );

        return back()->with('success', "Seller \"{$seller->business_name}\" has been restored to Approved.");
    }

    // ── Legacy updateStatus — kept for backward compatibility ───────────────

    public function updateStatus(Request $request, Seller $seller)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected,Suspended'
        ]);

        $seller->update(['status' => $validated['status']]);

        return back()->with('success', "Seller status updated to {$validated['status']}.");
    }

    // ── Private Helpers ─────────────────────────────────────────────────────

    private function notifySeller(Seller $seller, string $type, string $message): void
    {
        $key      = 'seller_account_notification_' . $seller->id;
        $existing = cache()->get($key, []);

        $existing[] = ['type' => $type, 'message' => $message];

        cache()->put($key, $existing, now()->addDays(7));
    }
}
