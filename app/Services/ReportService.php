<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductAnalytics;
use App\Models\Seller;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Wishlist;
use App\Models\Cart;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportService
{
    // ─────────────────────────────────────────────────────────────────────────
    //  SELLER REPORT DATA
    //  Scope: only this seller's products. Never leaks other sellers' data.
    // ─────────────────────────────────────────────────────────────────────────

    public function getSellerReportData(int $sellerId, Request $request): array
    {
        [$dateFrom, $dateTo] = $this->resolveDateRange($request);

        // ── Base product query for this seller ────────────────────────────────
        $productQuery = Product::with(['category', 'brand', 'variants.color', 'variants.sizes'])
            ->where('seller_id', $sellerId);

        // Search filter
        if ($request->filled('search')) {
            $productQuery->where('name', 'like', '%' . $request->search . '%');
        }

        // Product filter (specific product selected)
        if ($request->filled('product_id')) {
            $productQuery->where('id', $request->product_id);
        }

        // Category filter
        if ($request->filled('category_id')) {
            $productQuery->where('category_id', $request->category_id);
        }

        // Status filter (active=1 / inactive=0)
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'pending') {
                $productQuery->where('approval_status', 'pending');
            } elseif ($request->status === 'active') {
                $productQuery->where('status', 1)->where('approval_status', 'approved');
            } elseif ($request->status === 'inactive') {
                $productQuery->where('status', 0);
            }
        }

        $products   = $productQuery->get();
        $productIds = $products->pluck('id')->toArray();

        // ── Analytics (product-level totals) ──────────────────────────────────
        $analyticsQuery = ProductAnalytics::with(['product.category'])
            ->where('seller_id', $sellerId)
            ->whereIn('product_id', $productIds);

        // Date range filter on analytics updated_at
        if ($dateFrom) {
            $analyticsQuery->whereDate('updated_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $analyticsQuery->whereDate('updated_at', '<=', $dateTo);
        }

        $analytics = $analyticsQuery->get();

        // ── Summary Cards ──────────────────────────────────────────────────────
        $totalProducts    = $products->count();
        $totalWishlist    = $analytics->sum('wishlist_count');
        $totalCart        = $analytics->sum('cart_count');
        $totalInterest    = $totalWishlist + $totalCart;
        $zeroInterestCount= $analytics->filter(fn($a) => $a->wishlist_count == 0 && $a->cart_count == 0)->count()
                          + $products->filter(fn($p) => !$analytics->pluck('product_id')->contains($p->id))->count();
        $pendingCount     = $products->where('approval_status', 'pending')->count();

        $topProducts = $analytics->map(function ($a) {
            $a->total_interest = $a->wishlist_count + $a->cart_count;
            return $a;
        })->sortByDesc('total_interest')->values();

        $mostPopular = $topProducts->first();

        // ── Zero Interest ──────────────────────────────────────────────────────
        $trackedIds    = $analytics->pluck('product_id')->toArray();
        $untrackedProds = $products->whereNotIn('id', $trackedIds)->values();
        $zeroInterest  = $analytics->filter(fn($a) => $a->wishlist_count == 0 && $a->cart_count == 0)->values();

        // ── Variant Analytics (FIXED: per variant_id from wishlists/carts) ────
        $variantAnalytics = $this->buildVariantAnalytics($productIds, $products);

        // ── Charts ─────────────────────────────────────────────────────────────
        $top10           = $topProducts->take(10);
        $chartLabels     = $top10->map(fn($a) => $a->product?->name ?? 'Unknown')->values();
        $chartWishlist   = $top10->map(fn($a) => $a->wishlist_count)->values();
        $chartCart       = $top10->map(fn($a) => $a->cart_count)->values();
        $chartInterest   = $top10->map(fn($a) => $a->total_interest)->values();

        // Category distribution for donut chart
        $categoryDist    = $analytics->groupBy(fn($a) => $a->product?->category?->name ?? 'Uncategorized')
            ->map(fn($g) => $g->sum(fn($a) => $a->wishlist_count + $a->cart_count))
            ->sortDesc()->take(8);

        $catChartLabels  = $categoryDist->keys()->values();
        $catChartData    = $categoryDist->values();

        // ── Dropdown data for filters ─────────────────────────────────────────
        $sellerProducts  = Product::where('seller_id', $sellerId)->orderBy('name')->get(['id', 'name']);
        $categories      = Category::whereNull('parent_id')->orderBy('name')->get();

        return compact(
            'totalProducts',
            'totalWishlist',
            'totalCart',
            'totalInterest',
            'zeroInterestCount',
            'pendingCount',
            'mostPopular',
            'topProducts',
            'zeroInterest',
            'untrackedProds',
            'variantAnalytics',
            'chartLabels',
            'chartWishlist',
            'chartCart',
            'chartInterest',
            'catChartLabels',
            'catChartData',
            'sellerProducts',
            'categories',
            'dateFrom',
            'dateTo'
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  ADMIN REPORT DATA
    //  Scope: entire marketplace.
    // ─────────────────────────────────────────────────────────────────────────

    public function getAdminReportData(Request $request): array
    {
        [$dateFrom, $dateTo] = $this->resolveDateRange($request);

        // ── Product query with all filters ────────────────────────────────────
        $productQuery = Product::with(['category', 'brand', 'seller']);

        if ($request->filled('search')) {
            $productQuery->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('seller_id')) {
            $productQuery->where('seller_id', $request->seller_id);
        }
        if ($request->filled('category_id')) {
            $productQuery->where('category_id', $request->category_id);
        }
        if ($request->filled('brand_id')) {
            $productQuery->where('brand_id', $request->brand_id);
        }
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $productQuery->where('status', 1);
            } elseif ($request->status === 'inactive') {
                $productQuery->where('status', 0);
            }
        }
        if ($request->filled('approval_status') && $request->approval_status !== 'all') {
            $productQuery->where('approval_status', $request->approval_status);
        }

        $allProducts    = $productQuery->get();
        $allProductIds  = $allProducts->pluck('id')->toArray();

        // ── Analytics ─────────────────────────────────────────────────────────
        $analyticsQuery = ProductAnalytics::with(['product.category', 'product.seller', 'seller'])
            ->whereIn('product_id', $allProductIds);

        if ($dateFrom) {
            $analyticsQuery->whereDate('updated_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $analyticsQuery->whereDate('updated_at', '<=', $dateTo);
        }

        $analytics = $analyticsQuery->get()->map(function ($a) {
            $a->total_interest = $a->wishlist_count + $a->cart_count;
            return $a;
        });

        // ── Seller Summary ─────────────────────────────────────────────────────
        $totalSellers    = Seller::count();
        $approvedSellers = Seller::where('status', 'approved')->count();
        $pendingSellers  = Seller::where('status', 'pending')->count();
        $rejectedSellers = Seller::where('status', 'rejected')->count();

        // ── Product Summary ────────────────────────────────────────────────────
        $totalProducts    = $allProducts->count();
        $pendingProducts  = $allProducts->where('approval_status', 'pending')->count();
        $approvedProducts = $allProducts->where('approval_status', 'approved')->count();
        $totalWishlist    = $analytics->sum('wishlist_count');
        $totalCart        = $analytics->sum('cart_count');
        $totalInterest    = $totalWishlist + $totalCart;

        // ── Most Popular ───────────────────────────────────────────────────────
        $popularProducts = $analytics->sortByDesc('total_interest')->values();
        $mostPopular     = $popularProducts->first();

        // ── Top Seller ────────────────────────────────────────────────────────
        $topSellerData = $analytics->groupBy('seller_id')
            ->map(fn($g) => [
                'seller'         => $g->first()->seller,
                'total_interest' => $g->sum('total_interest'),
                'wishlist_count' => $g->sum('wishlist_count'),
                'cart_count'     => $g->sum('cart_count'),
                'product_count'  => $g->count(),
            ])
            ->sortByDesc('total_interest')
            ->first();

        $mostPopularSeller = $topSellerData['seller'] ?? null;

        // ── Seller Performance ─────────────────────────────────────────────────
        $sellerPerformance = $analytics->groupBy('seller_id')
            ->map(fn($g) => [
                'seller'         => $g->first()->seller,
                'product_count'  => $g->count(),
                'wishlist_count' => $g->sum('wishlist_count'),
                'cart_count'     => $g->sum('cart_count'),
                'total_interest' => $g->sum('total_interest'),
            ])
            ->sortByDesc('total_interest')
            ->values();

        // ── Category Performance ───────────────────────────────────────────────
        $categoryPerformance = $analytics->groupBy(fn($a) => $a->product?->category?->id)
            ->map(fn($g) => [
                'category'       => $g->first()?->product?->category,
                'wishlist_count' => $g->sum('wishlist_count'),
                'cart_count'     => $g->sum('cart_count'),
                'total_interest' => $g->sum('total_interest'),
            ])
            ->filter(fn($row) => $row['category'] !== null)
            ->sortByDesc('total_interest')
            ->values();

        // ── Pending / Rejected Products ────────────────────────────────────────
        $pendingProductsList  = $allProducts->where('approval_status', 'pending')->values();
        $rejectedProductsList = $allProducts->where('approval_status', 'rejected')->values();

        // ── Zero Interest ──────────────────────────────────────────────────────
        $trackedIds         = $analytics->pluck('product_id')->toArray();
        $zeroInterestList   = $analytics->filter(fn($a) => $a->total_interest == 0)->values();
        $untrackedProducts  = $allProducts->whereNotIn('id', $trackedIds)->values();

        // ── Variant Analytics ──────────────────────────────────────────────────
        $variantAnalytics = $this->buildVariantAnalytics($allProductIds, $allProducts);

        // ── Chart Data ─────────────────────────────────────────────────────────
        $top10Products         = $popularProducts->take(10);
        $productChartLabels    = $top10Products->map(fn($a) => $a->product?->name ?? 'Unknown')->values();
        $productChartWishlist  = $top10Products->map(fn($a) => $a->wishlist_count)->values();
        $productChartCart      = $top10Products->map(fn($a) => $a->cart_count)->values();

        $top10Sellers          = $sellerPerformance->take(10);
        $sellerChartLabels     = $top10Sellers->map(fn($s) => $s['seller']?->business_name ?? 'Unknown')->values();
        $sellerChartWishlist   = $top10Sellers->map(fn($s) => $s['wishlist_count'])->values();
        $sellerChartCart       = $top10Sellers->map(fn($s) => $s['cart_count'])->values();

        $top8Categories        = $categoryPerformance->take(8);
        $catChartLabels        = $top8Categories->map(fn($c) => $c['category']?->name ?? 'Unknown')->values();
        $catChartInterest      = $top8Categories->map(fn($c) => $c['total_interest'])->values();
        $catChartWishlist      = $top8Categories->map(fn($c) => $c['wishlist_count'])->values();
        $catChartCart          = $top8Categories->map(fn($c) => $c['cart_count'])->values();

        // ── Dropdown filter data ───────────────────────────────────────────────
        $sellers    = Seller::orderBy('business_name')->get(['id', 'business_name']);
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();
        $brands     = Brand::orderBy('name')->get(['id', 'name']);

        return compact(
            'totalSellers', 'approvedSellers', 'pendingSellers', 'rejectedSellers',
            'totalProducts', 'pendingProducts', 'approvedProducts',
            'totalWishlist', 'totalCart', 'totalInterest',
            'mostPopular', 'mostPopularSeller', 'topSellerData',
            'popularProducts', 'sellerPerformance', 'categoryPerformance',
            'pendingProductsList', 'rejectedProductsList',
            'zeroInterestList', 'untrackedProducts',
            'variantAnalytics',
            'productChartLabels', 'productChartWishlist', 'productChartCart',
            'sellerChartLabels', 'sellerChartWishlist', 'sellerChartCart',
            'catChartLabels', 'catChartInterest', 'catChartWishlist', 'catChartCart',
            'sellers', 'categories', 'brands',
            'dateFrom', 'dateTo'
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  VARIANT ANALYTICS (FIXED)
    //  Queries wishlists and carts directly by product_variant_id.
    //  This is the CORRECT way — product_analytics only stores product-level.
    // ─────────────────────────────────────────────────────────────────────────

    public function buildVariantAnalytics(array $productIds, Collection $products): Collection
    {
        if (empty($productIds)) {
            return collect();
        }

        // Count wishlists per variant (from wishlists table directly)
        $wishlistCounts = Wishlist::whereIn('product_id', $productIds)
            ->whereNotNull('product_variant_id')
            ->selectRaw('product_variant_id, COUNT(*) as wl_count')
            ->groupBy('product_variant_id')
            ->pluck('wl_count', 'product_variant_id');

        // Count carts per variant (from carts table directly)
        $cartCounts = Cart::whereIn('product_id', $productIds)
            ->whereNotNull('product_variant_id')
            ->selectRaw('product_variant_id, COUNT(*) as ct_count')
            ->groupBy('product_variant_id')
            ->pluck('ct_count', 'product_variant_id');

        $result = collect();

        foreach ($products as $product) {
            foreach ($product->variants as $variant) {
                $wl = $wishlistCounts->get($variant->id, 0);
                $ct = $cartCounts->get($variant->id, 0);

                $result->push([
                    'product'        => $product,
                    'variant'        => $variant,
                    'color'          => $variant->color,
                    'sizes'          => $variant->sizes,
                    'wishlist'       => $wl,
                    'cart'           => $ct,
                    'total_interest' => $wl + $ct,
                ]);
            }
        }

        return $result->sortByDesc('total_interest')->values();
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  DATE RANGE RESOLVER
    // ─────────────────────────────────────────────────────────────────────────

    private function resolveDateRange(Request $request): array
    {
        $quick = $request->input('quick_date', '');

        switch ($quick) {
            case 'today':
                return [Carbon::today(), Carbon::today()];
            case 'yesterday':
                return [Carbon::yesterday(), Carbon::yesterday()];
            case 'last7':
                return [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()];
            case 'last30':
                return [Carbon::now()->subDays(29)->startOfDay(), Carbon::now()->endOfDay()];
            case 'this_month':
                return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
            case 'last_month':
                return [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()];
            case 'custom':
                $from = $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : null;
                $to   = $request->filled('date_to')   ? Carbon::parse($request->date_to)->endOfDay()     : null;
                return [$from, $to];
            default:
                return [null, null];
        }
    }
}
