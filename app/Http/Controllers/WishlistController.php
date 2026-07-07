<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Collection;

class WishlistController extends Controller
{
    public function add(Product $product)
    {
        $customerId = session('customer_id');

        $wishlist = Wishlist::where('customer_id', $customerId)
            ->where('product_id', $product->id)
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

    $wishlist = Wishlist::where('customer_id', $customerId)
        ->where('product_id', $product->id)
        ->where('collection_id', optional($collection)->id)
        ->first();

    if ($wishlist) {

        $wishlist->delete();

        return back();

    }

    Wishlist::create([
        'customer_id'  => $customerId,
        'product_id'   => $product->id,
        'collection_id'=> optional($collection)->id,
    ]);

    return back();
}

    public function index()
{
    $customerId = session('customer_id');

    $wishlistItems = Wishlist::with([
            'product',
            'collection'
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