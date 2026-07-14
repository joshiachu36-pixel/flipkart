<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductAnalytics;
use App\Models\Product;

class SellerReportController extends Controller
{
    public function index()
    {
        $seller = Auth::guard('seller')->user();

        // All analytics records for this seller only (security: seller_id filter)
        $analytics = ProductAnalytics::with(['product.category', 'product.variants.color', 'product.variants.sizes'])
            ->where('seller_id', $seller->id)
            ->get();

        // ── Summary Cards ────────────────────────────────────────────────────
        $totalProducts   = $seller->products()->count();
        $totalWishlist   = $analytics->sum('wishlist_count');
        $totalCart       = $analytics->sum('cart_count');

        $mostPopular = $analytics
            ->sortByDesc(fn($a) => $a->wishlist_count + $a->cart_count)
            ->first();

        // ── Top Products by Total Interest ────────────────────────────────────
        $topProducts = $analytics
            ->map(function ($a) {
                $a->total_interest = $a->wishlist_count + $a->cart_count;
                return $a;
            })
            ->sortByDesc('total_interest')
            ->values();

        // ── Top Wishlist Products (top 10) ────────────────────────────────────
        $topWishlist = $analytics->sortByDesc('wishlist_count')->take(10)->values();

        // ── Top Cart Products (top 10) ─────────────────────────────────────────
        $topCart = $analytics->sortByDesc('cart_count')->take(10)->values();

        // ── Zero Interest Products ─────────────────────────────────────────────
        $zeroInterest = $analytics->filter(function ($a) {
            return $a->wishlist_count == 0 && $a->cart_count == 0;
        })->values();

        // ── Products with NO analytics record yet (never added to cart/wishlist)
        $productIds   = $analytics->pluck('product_id')->toArray();
        $untracked    = $seller->products()
            ->whereNotIn('id', $productIds)
            ->get();

        // ── Variant Analytics ─────────────────────────────────────────────────
        $variantAnalytics = collect();
        foreach ($analytics as $a) {
            if ($a->product && $a->product->variants->isNotEmpty()) {
                foreach ($a->product->variants as $variant) {
                    $variantAnalytics->push([
                        'product'   => $a->product,
                        'variant'   => $variant,
                        'color'     => $variant->color,
                        'sizes'     => $variant->sizes,
                        'wishlist'  => $a->wishlist_count,
                        'cart'      => $a->cart_count,
                    ]);
                }
            }
        }

        // ── Chart Data ────────────────────────────────────────────────────────
        $chartLabels    = $topProducts->take(10)->map(fn($a) => $a->product?->name ?? 'Unknown')->values();
        $chartWishlist  = $topProducts->take(10)->map(fn($a) => $a->wishlist_count)->values();
        $chartCart      = $topProducts->take(10)->map(fn($a) => $a->cart_count)->values();
        $chartInterest  = $topProducts->take(10)->map(fn($a) => $a->total_interest)->values();

        return view('seller.reports.index', compact(
            'seller',
            'totalProducts',
            'totalWishlist',
            'totalCart',
            'mostPopular',
            'topProducts',
            'topWishlist',
            'topCart',
            'zeroInterest',
            'untracked',
            'variantAnalytics',
            'chartLabels',
            'chartWishlist',
            'chartCart',
            'chartInterest'
        ));
    }
}
