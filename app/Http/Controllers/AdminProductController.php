<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductApprovalHistory;

class AdminProductController extends Controller
{
    // ── Index — list all seller products with filter & search ─────────────────

    public function index(Request $request)
    {
        $query = Product::with(['seller', 'category', 'variants'])
            ->whereNotNull('seller_id');

        if ($request->filled('status') && in_array($request->status, ['Pending', 'Approved', 'Rejected'])) {
            $query->where('approval_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhereHas('seller', function ($sq) use ($search) {
                      $sq->where('owner_name', 'LIKE', "%{$search}%")
                         ->orWhere('business_name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'all'      => Product::whereNotNull('seller_id')->count(),
            'pending'  => Product::whereNotNull('seller_id')->where('approval_status', 'Pending')->count(),
            'approved' => Product::whereNotNull('seller_id')->where('approval_status', 'Approved')->count(),
            'rejected' => Product::whereNotNull('seller_id')->where('approval_status', 'Rejected')->count(),
        ];

        return view('admin.products.index', compact('products', 'counts'));
    }

    // ── Legacy redirect — kept for backward compatibility ─────────────────────

    public function pending()
    {
        return redirect()->route('admin.products.index', ['status' => 'Pending']);
    }

    // ── Show — full product review page ──────────────────────────────────────

    public function show(Product $product)
    {
        $product->load([
            'seller',
            'category',
            'brand',
            'variants.color',
            'variants.sizes',
            'approvalHistories',
        ]);

        return view('admin.products.show', compact('product'));
    }

    // ── Approve — locks the status permanently ────────────────────────────────

    public function approve(Request $request, Product $product)
    {
        abort_unless(can_do('products.approve'), 403, 'You do not have permission to approve products.');
        // Guard: only Pending products can be approved
        if (! $product->canBeReviewed()) {
            return back()->with('error', 'This product cannot be approved in its current state.');
        }

        DB::transaction(function () use ($product) {
            $admin = Auth::user();

            $product->update([
                'approval_status' => 'Approved',
                'approved_at'     => now(),
                'approved_by'     => $admin->id,
                // Clear any previous rejection data
                'rejection_reason' => null,
                'rejected_by'      => null,
                'rejected_at'      => null,
            ]);

            // Audit history
            ProductApprovalHistory::create([
                'product_id' => $product->id,
                'action'     => 'Approved',
                'actor_id'   => $admin->id,
                'actor_name' => $admin->name,
                'reason'     => null,
                'acted_at'   => now(),
            ]);

            // Seller notification via cache
            $this->notifySeller($product, 'approved',
                "Your product \"{$product->name}\" has been approved and is now live on the shop! 🎉"
            );
        });

        return redirect()
            ->route('admin.products.index', ['status' => 'Pending'])
            ->with('success', "Product \"{$product->name}\" has been approved and is now live.");
    }

    // ── Reject — requires a mandatory reason ─────────────────────────────────

    public function reject(Request $request, Product $product)
    {
        abort_unless(can_do('products.reject'), 403, 'You do not have permission to reject products.');
        // Guard: only Pending products can be rejected
        if (! $product->canBeReviewed()) {
            return back()->with('error', 'This product cannot be rejected in its current state.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:1000',
        ], [
            'rejection_reason.required' => 'A rejection reason is required.',
            'rejection_reason.min'      => 'Please provide a more detailed reason (at least 10 characters).',
        ]);

        DB::transaction(function () use ($product, $validated) {
            $admin = Auth::user();

            $product->update([
                'approval_status'  => 'Rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'rejected_by'      => $admin->id,
                'rejected_at'      => now(),
                // Clear any previous approval data
                'approved_at'  => null,
                'approved_by'  => null,
            ]);

            // Audit history
            ProductApprovalHistory::create([
                'product_id' => $product->id,
                'action'     => 'Rejected',
                'actor_id'   => $admin->id,
                'actor_name' => $admin->name,
                'reason'     => $validated['rejection_reason'],
                'acted_at'   => now(),
            ]);

            // Seller notification via cache
            $this->notifySeller($product, 'rejected',
                "Your product \"{$product->name}\" was rejected. Reason: {$validated['rejection_reason']}"
            );
        });

        return redirect()
            ->route('admin.products.index', ['status' => 'Pending'])
            ->with('success', "Product \"{$product->name}\" has been rejected with a reason.");
    }

    // ── Legacy updateStatus — kept for strict backward compatibility ──────────
    // NOTE: The new workflow uses approve() and reject() instead.
    // This method is preserved so no existing routes break.

    public function updateStatus(Request $request, Product $product)
    {
        abort_unless(can_do('products.edit'), 403, 'You do not have permission to update product status.');
        $validated = $request->validate([
            'approval_status' => 'required|in:Pending,Approved,Rejected',
        ]);

        // If product is already approved, block changes via this legacy endpoint
        if ($product->isApprovalLocked()) {
            return back()->with('error', 'This product is approved and locked. No further status changes are allowed.');
        }

        $data = ['approval_status' => $validated['approval_status']];

        if ($validated['approval_status'] === 'Approved') {
            $data['approved_at'] = now();
            $data['approved_by'] = Auth::id();
        } else {
            $data['approved_at'] = null;
            $data['approved_by'] = null;
        }

        $product->update($data);

        if ($product->seller_id) {
            $notifMsg = match ($validated['approval_status']) {
                'Approved' => "Your product \"{$product->name}\" has been approved and is now live on the shop.",
                'Rejected' => "Your product \"{$product->name}\" has been rejected. Please review and update it.",
                default    => "The status of your product \"{$product->name}\" has been updated to {$validated['approval_status']}.",
            };
            $existing   = cache()->get('seller_product_notification_' . $product->seller_id, []);
            $existing[] = ['type' => strtolower($validated['approval_status']), 'message' => $notifMsg];
            cache()->put('seller_product_notification_' . $product->seller_id, $existing, now()->addDays(7));
        }

        return back()->with('success', "Product \"{$product->name}\" status updated to {$validated['approval_status']}.");
    }

    // ── Private Helpers ───────────────────────────────────────────────────────

    private function notifySeller(Product $product, string $type, string $message): void
    {
        if (! $product->seller_id) {
            return;
        }

        $key      = 'seller_product_notification_' . $product->seller_id;
        $existing = cache()->get($key, []);

        $existing[] = ['type' => $type, 'message' => $message];

        cache()->put($key, $existing, now()->addDays(7));
    }
}
