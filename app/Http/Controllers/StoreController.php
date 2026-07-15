<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Product;

class StoreController extends Controller
{
    /**
     * Display the public store page for a seller/business.
     * URL: /shop/store/{seller}
     */
    public function show(Seller $seller)
    {
        // Only show approved seller accounts (status enum: Pending, Approved, Rejected, Suspended)
        if ($seller->status !== 'Approved') {
            abort(404);
        }

        $products = Product::with(['category', 'seller'])
            ->where('seller_id', $seller->id)
            ->where('approval_status', 'Approved')
            ->where('status', 1)
            ->latest()
            ->paginate(12);

        $totalProducts = Product::where('seller_id', $seller->id)
            ->where('approval_status', 'Approved')
            ->where('status', 1)
            ->count();

        return view('store.show', compact('seller', 'products', 'totalProducts'));
    }
}

