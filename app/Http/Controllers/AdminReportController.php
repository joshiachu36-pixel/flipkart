<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductAnalytics;
use App\Models\Seller;
use App\Models\Category;
use App\Models\Product;

class AdminReportController extends Controller
{
    public function index()
    {
        // ── All analytics with eager-loaded relations ──────────────────────────
        $analytics = ProductAnalytics::with([
            'product.category',
            'product.seller',
        ])->get();

        // ── Summary Cards ─────────────────────────────────────────────────────
        $totalSellers      = Seller::count();
        $totalProducts     = Product::count();
        $totalWishlist     = $analytics->sum('wishlist_count');
        $totalCart         = $analytics->sum('cart_count');

        $mostPopular = $analytics
            ->sortByDesc(fn($a) => $a->wishlist_count + $a->cart_count)
            ->first();

        // Top seller by total interest
        $topSellerData = $analytics
            ->groupBy('seller_id')
            ->map(fn($group) => [
                'seller'          => $group->first()->seller,
                'total_interest'  => $group->sum(fn($a) => $a->wishlist_count + $a->cart_count),
            ])
            ->sortByDesc('total_interest')
            ->first();

        // ── Most Popular Products (all sellers) ───────────────────────────────
        $popularProducts = $analytics
            ->map(function ($a) {
                $a->total_interest = $a->wishlist_count + $a->cart_count;
                return $a;
            })
            ->sortByDesc('total_interest')
            ->values();

        // ── Seller Performance ────────────────────────────────────────────────
        $sellerPerformance = $analytics
            ->groupBy('seller_id')
            ->map(function ($group) {
                $seller = $group->first()->seller;
                return [
                    'seller'          => $seller,
                    'product_count'   => $group->count(),
                    'wishlist_count'  => $group->sum('wishlist_count'),
                    'cart_count'      => $group->sum('cart_count'),
                    'total_interest'  => $group->sum(fn($a) => $a->wishlist_count + $a->cart_count),
                ];
            })
            ->sortByDesc('total_interest')
            ->values();

        // ── Category Performance ──────────────────────────────────────────────
        $categoryPerformance = $analytics
            ->groupBy(fn($a) => optional($a->product?->category)->id)
            ->map(function ($group) {
                $category = $group->first()?->product?->category;
                return [
                    'category'       => $category,
                    'wishlist_count' => $group->sum('wishlist_count'),
                    'cart_count'     => $group->sum('cart_count'),
                    'total_interest' => $group->sum(fn($a) => $a->wishlist_count + $a->cart_count),
                ];
            })
            ->filter(fn($row) => $row['category'] !== null)
            ->sortByDesc('total_interest')
            ->values();

        // ── Chart Data ────────────────────────────────────────────────────────
        // Top sellers chart
        $sellerChartLabels   = $sellerPerformance->take(10)->map(fn($s) => optional($s['seller'])->business_name ?? 'Unknown');
        $sellerChartWishlist = $sellerPerformance->take(10)->map(fn($s) => $s['wishlist_count']);
        $sellerChartCart     = $sellerPerformance->take(10)->map(fn($s) => $s['cart_count']);

        // Top products chart
        $productChartLabels   = $popularProducts->take(10)->map(fn($a) => $a->product?->name ?? 'Unknown');
        $productChartWishlist = $popularProducts->take(10)->map(fn($a) => $a->wishlist_count);
        $productChartCart     = $popularProducts->take(10)->map(fn($a) => $a->cart_count);

        // Category chart
        $catChartLabels   = $categoryPerformance->take(10)->map(fn($c) => $c['category']->name ?? 'Unknown');
        $catChartInterest = $categoryPerformance->take(10)->map(fn($c) => $c['total_interest']);

        return view('admin.reports.index', compact(
            'totalSellers',
            'totalProducts',
            'totalWishlist',
            'totalCart',
            'mostPopular',
            'topSellerData',
            'popularProducts',
            'sellerPerformance',
            'categoryPerformance',
            'sellerChartLabels',
            'sellerChartWishlist',
            'sellerChartCart',
            'productChartLabels',
            'productChartWishlist',
            'productChartCart',
            'catChartLabels',
            'catChartInterest'
        ));
    }
}
