<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Color;
use App\Models\Size;
use App\Models\OrderItem;
use App\Models\SellerDocument;
use App\Models\SellerActivity;
use Illuminate\Support\Facades\DB;

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

    // ── Show — full seller review page with 8 Bootstrap tabs ────────────────

    public function show(Seller $seller)
    {
        $seller->loadCount(['products', 'colors', 'sizes', 'documents', 'activities']);

        // 1. Seller Products
        $products = Product::where('seller_id', $seller->id)
            ->with(['category', 'variants'])
            ->latest()
            ->get();

        // 2. Seller Variants
        $productIds = $products->pluck('id');
        $variants = ProductVariant::whereIn('product_id', $productIds)
            ->with(['product', 'color', 'sizes'])
            ->orderBy('priority', 'asc')
            ->get();

        // 3. Seller Colors & Sizes
        $colors = Color::where('seller_id', $seller->id)->latest()->get();
        $sizes  = Size::where('seller_id', $seller->id)->latest()->get();

        // 4. Seller Orders & Revenue Metrics
        $orderItems = OrderItem::whereIn('product_id', $productIds)->get();
        $totalOrdersCount = $orderItems->pluck('order_id')->unique()->count();
        $totalRevenue     = $orderItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Best selling product
        $bestSellingItem = OrderItem::whereIn('product_id', $productIds)
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->first();
        $bestSellingProduct = $bestSellingItem ? Product::find($bestSellingItem->product_id) : null;

        // Inventory status
        $lowStockProducts = $products->filter(fn($p) => $p->stock > 0 && $p->stock <= 5);
        $outOfStockProducts = $products->filter(fn($p) => $p->stock == 0);

        // Statistics Summary Cards
        $stats = [
            'total_products'   => $products->count(),
            'approved_products'=> $products->where('approval_status', 'Approved')->count(),
            'pending_products' => $products->where('approval_status', 'Pending')->count(),
            'rejected_products'=> $products->where('approval_status', 'Rejected')->count(),
            'total_variants'   => $variants->count(),
            'total_colors'     => $colors->count(),
            'total_sizes'      => $sizes->count(),
            'total_orders'     => $totalOrdersCount,
            'total_revenue'    => $totalRevenue,
            'low_stock_count'  => $lowStockProducts->count(),
            'out_of_stock_count'=> $outOfStockProducts->count(),
            'best_selling_product' => $bestSellingProduct ? $bestSellingProduct->name : 'N/A',
        ];

        // 5. Documents & Activities
        $documents  = SellerDocument::where('seller_id', $seller->id)->latest()->get();
        $activities = SellerActivity::where('seller_id', $seller->id)->latest()->get();

        return view('admin.sellers.show', compact(
            'seller',
            'products',
            'variants',
            'colors',
            'sizes',
            'stats',
            'documents',
            'activities',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }

    // ── Upload Document ─────────────────────────────────────────────────────

    public function storeDocument(Request $request, Seller $seller)
    {
        abort_unless(can_do('sellers.edit'), 403, 'You do not have permission to upload seller documents.');
        $validated = $request->validate([
            'document_type' => 'required|string|max:100',
            'document_name' => 'required|string|max:255',
            'document_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5000',
            'notes'         => 'nullable|string',
        ]);

        $filePath = $request->file('document_file')->store('seller-documents', 'public');

        SellerDocument::create([
            'seller_id'     => $seller->id,
            'document_type' => $validated['document_type'],
            'document_name' => $validated['document_name'],
            'file_path'     => $filePath,
            'status'        => 'Approved',
            'notes'         => $validated['notes'] ?? null,
        ]);

        SellerActivity::log($seller->id, 'Document Uploaded', "Uploaded document: {$validated['document_name']} ({$validated['document_type']})");

        return back()->with('success', 'Document uploaded successfully.');
    }

    // ── Approve — activate seller account ───────────────────────────────────

    public function approve(Seller $seller)
    {
        abort_unless(can_do('sellers.approve'), 403, 'You do not have permission to approve sellers.');
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

        SellerActivity::log($seller->id, 'Account Approved', 'Seller application was approved by Admin.');

        $this->notifySeller($seller, 'approved',
            "Congratulations! Your seller application for \"{$seller->business_name}\" has been approved. You now have full access to the Seller Dashboard."
        );

        return back()->with('success', "Seller \"{$seller->business_name}\" has been approved.");
    }

    // ── Reject — requires mandatory reason ──────────────────────────────────

    public function reject(Request $request, Seller $seller)
    {
        abort_unless(can_do('sellers.reject'), 403, 'You do not have permission to reject sellers.');
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

        SellerActivity::log($seller->id, 'Account Rejected', "Seller application rejected. Reason: {$validated['rejection_reason']}");

        $this->notifySeller($seller, 'rejected',
            "Your seller application has been rejected. Reason: {$validated['rejection_reason']}"
        );

        return back()->with('success', "Seller \"{$seller->business_name}\" has been rejected with a reason.");
    }

    // ── Suspend — only for approved sellers ─────────────────────────────────

    public function suspend(Request $request, Seller $seller)
    {
        abort_unless(can_do('sellers.suspend'), 403, 'You do not have permission to suspend sellers.');
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

        SellerActivity::log($seller->id, 'Account Suspended', "Seller account suspended." . ($validated['suspension_reason'] ? " Reason: {$validated['suspension_reason']}" : ''));

        $this->notifySeller($seller, 'suspended',
            "Your seller account has been suspended." .
            ($validated['suspension_reason'] ? " Reason: {$validated['suspension_reason']}" : '')
        );

        return back()->with('success', "Seller \"{$seller->business_name}\" has been suspended.");
    }

    // ── Restore — reactivate a suspended seller ─────────────────────────────

    public function restore(Seller $seller)
    {
        abort_unless(can_do('sellers.approve'), 403, 'You do not have permission to restore sellers.');
        if (!$seller->isSuspended()) {
            return back()->with('error', 'Only suspended sellers can be restored.');
        }

        $seller->update([
            'status'            => 'Approved',
            'suspension_reason' => null,
            'suspended_at'      => null,
        ]);

        SellerActivity::log($seller->id, 'Account Restored', 'Seller account restored to Approved.');

        $this->notifySeller($seller, 'restored',
            "Your seller account has been restored. You now have full access to the Seller Dashboard again."
        );

        return back()->with('success', "Seller \"{$seller->business_name}\" has been restored to Approved.");
    }

    // ── Legacy updateStatus — kept for backward compatibility ───────────────

    public function updateStatus(Request $request, Seller $seller)
    {
        abort_unless(can_do('sellers.edit'), 403, 'You do not have permission to update seller status.');
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
