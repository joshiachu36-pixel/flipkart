<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Collection;

class WishlistController extends Controller
{
    public function add(Request $request, Product $product)
    {
        $customerId = session('customer_id');

        $variantId = null;
        $sizeId = null;
        if ($product->variants()->where('status', 1)->exists()) {
            $request->validate([
                'product_variant_id' => 'required|exists:product_variants,id',
                'size_id' => 'required|exists:sizes,id',
            ]);
            $variantId = $request->input('product_variant_id');
            $sizeId = $request->input('size_id');
        }

        $wishlist = Wishlist::where('customer_id', $customerId)
            ->where('product_id', $product->id)
            ->whereRaw('(product_variant_id <=> ?)', [$variantId])
            ->whereRaw('(size_id <=> ?)', [$sizeId])
            ->first();

        if ($wishlist) {
            return back()->with(
                'success',
                'Product is already in your wishlist.'
            );
        }

        Wishlist::create([
            'customer_id' => $customerId,
            'product_id' => $product->id,
            'product_variant_id' => $variantId,
            'size_id' => $sizeId,
        ]);

        return back()->with(
            'success',
            'Product added to wishlist successfully.'
        );
    }

    public function toggle(Request $request, Product $product)
    {
        $customerId = session('customer_id');

        $collection = null;

        if ($request->filled('collection_slug')) {
            $collection = Collection::where(
                'slug',
                $request->collection_slug
            )->first();
        }

        $variantId = null;
        $sizeId = null;
        if ($product->variants()->where('status', 1)->exists()) {
            $request->validate([
                'product_variant_id' => 'required|exists:product_variants,id',
                'size_id' => 'required|exists:sizes,id',
            ]);
            $variantId = $request->input('product_variant_id');
            $sizeId = $request->input('size_id');
        }

        $wishlist = Wishlist::where('customer_id', $customerId)
            ->where('product_id', $product->id)
            ->where('collection_id', optional($collection)->id)
            ->whereRaw('(product_variant_id <=> ?)', [$variantId])
            ->whereRaw('(size_id <=> ?)', [$sizeId])
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return back();
        }

        Wishlist::create([
            'customer_id'  => $customerId,
            'product_id'   => $product->id,
            'collection_id'=> optional($collection)->id,
            'product_variant_id' => $variantId,
            'size_id' => $sizeId,
        ]);

        return back();
    }

    public function index()
    {
        $customerId = session('customer_id');

        $wishlistItems = Wishlist::with([
                'product',
                'collection',
                'productVariant.color',
                'productVariant.sizes',
                'size'
            ])
            ->where('customer_id', $customerId)
            ->latest()
            ->get();

        return view(
            'wishlist.index',
            compact('wishlistItems')
        );
    }

    public function remove(Wishlist $wishlist)
    {
        // Security check: make sure the logged-in customer owns this wishlist item
        if ($wishlist->customer_id != session('customer_id')) {
            abort(403);
        }

        $wishlist->delete();

        return back()->with(
            'success',
            'Product removed from wishlist successfully.'
        );
    }
}