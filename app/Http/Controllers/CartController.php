<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Collection;

class CartController extends Controller
{
    public function add(Request $request, Product $product)
    {
        $customerId = session('customer_id');

        $collection = null;

        if ($request->filled('collection_slug')) {

            $collection = Collection::where(
                'slug',
                $request->collection_slug
            )->first();

        }

        // Set null for products without variants
        $variantId = null;
        $sizeId = null;

        if ($product->variants()->where('status', 1)->exists()) {
            $request->validate([
                'product_variant_id' => 'required|exists:product_variants,id',
                'size_id' => 'required|exists:sizes,id',
                'quantity' => 'required|integer|min:1',
            ]);
            $variantId = $request->input('product_variant_id');
            $sizeId = $request->input('size_id');
        } else {
            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);
        }

        $cart = Cart::where('customer_id', $customerId)
        ->where('product_id', $product->id)
        ->where('collection_id', optional($collection)->id)
        ->whereRaw('(product_variant_id <=> ?)', [$variantId])
        ->whereRaw('(size_id <=> ?)', [$sizeId])
        ->first();

        if ($cart) {

            $cart->increment('quantity', $request->input('quantity', 1));

        } else {

            Cart::create([
            'customer_id'       => $customerId,
            'product_id'        => $product->id,
            'collection_id'     => optional($collection)->id,
            'product_variant_id'=> $variantId,
            'size_id'           => $sizeId,
            'quantity'          => $request->input('quantity', 1),
        ]);

        }

        return back()->with('success', 'Product added to cart successfully.');
    }

   public function index()
    {
        $customerId = session('customer_id');

        $cartItems = Cart::with([
            'product',
            'collection'
        ])
        ->where('customer_id', $customerId)
        ->get();

        $total = 0;

foreach ($cartItems as $item) {

    $price = $item->product->price;

    if ($item->collection) {

        if ($item->collection->discount_type == 'percentage') {

            $price = $price -
                (($price * $item->collection->discount_value) / 100);

        }
        elseif ($item->collection->discount_type == 'fixed') {

            $price = max(
                0,
                $price - $item->collection->discount_value
            );

        }

    }

    $total += $price * $item->quantity;

}

        return view('cart.index', compact(
            'cartItems',
            'total'
        ));
    }

    public function increase(Cart $cart)
    {
        $cart->increment('quantity');

        return back();
    }

    public function decrease(Cart $cart)
    {
        if ($cart->quantity > 1) {

            $cart->decrement('quantity');

        } else {

            $cart->delete();

        }

        return back();
    }

    public function remove(Cart $cart)
    {
        $cart->delete();

        return back()->with(
            'success',
            'Product removed from cart successfully.'
        );
    }
}
