<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class AdminProductController extends Controller
{
    /**
     * Display all seller products with search & filter.
     */
    public function index(Request $request)
    {
        $query = Product::with(['seller', 'category'])
            ->whereNotNull('seller_id'); // Only show seller-submitted products

        // Filter by approval status
        if ($request->filled('status') && in_array($request->status, ['Pending', 'Approved', 'Rejected'])) {
            $query->where('approval_status', $request->status);
        }

        // Search by product name, seller name, or business name
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

        // Counts for filter badges
        $counts = [
            'all'      => Product::whereNotNull('seller_id')->count(),
            'pending'  => Product::whereNotNull('seller_id')->where('approval_status', 'Pending')->count(),
            'approved' => Product::whereNotNull('seller_id')->where('approval_status', 'Approved')->count(),
            'rejected' => Product::whereNotNull('seller_id')->where('approval_status', 'Rejected')->count(),
        ];

        return view('admin.products.index', compact('products', 'counts'));
    }

    /**
     * Legacy pending-only route (kept for backward compatibility).
     */
    public function pending()
    {
        return redirect()->route('admin.products.index', ['status' => 'Pending']);
    }

    /**
     * Update the approval status of a product.
     */
    public function updateStatus(Request $request, Product $product)
    {
        $validated = $request->validate([
            'approval_status' => 'required|in:Pending,Approved,Rejected',
        ]);

        $data = ['approval_status' => $validated['approval_status']];

        // Stamp approved_at and approved_by when approving
        if ($validated['approval_status'] === 'Approved') {
            $data['approved_at'] = now();
            $data['approved_by'] = Auth::id();
        } else {
            // Clear the approval stamps when resetting to Pending or Rejected
            $data['approved_at'] = null;
            $data['approved_by'] = null;
        }

        $product->update($data);

        // Store a notification for the seller to see on their dashboard
        if ($product->seller_id) {
            $notifKey  = 'seller_product_notification_' . $product->seller_id;
            $notifMsg  = match ($validated['approval_status']) {
                'Approved' => "Your product \"{$product->name}\" has been approved and is now live on the shop.",
                'Rejected' => "Your product \"{$product->name}\" has been rejected. Please review and update it.",
                default    => "The status of your product \"{$product->name}\" has been updated to {$validated['approval_status']}.",
            };

            // Append to session-based queue (stored in DB cache if configured, otherwise use cache)
            $existing = cache()->get($notifKey, []);
            $existing[] = ['type' => strtolower($validated['approval_status']), 'message' => $notifMsg];
            cache()->put($notifKey, $existing, now()->addDays(7));
        }

        return back()->with('success', "Product \"{$product->name}\" status updated to {$validated['approval_status']}.");
    }
}
