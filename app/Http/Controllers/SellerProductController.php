<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;

class SellerProductController extends Controller
{
    public function index()
    {
        $seller   = Auth::guard('seller')->user();
        $products = $seller->products()->with(['category', 'brand'])->paginate(10);
        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'required|string',
            'price'          => 'required|numeric',
            'original_price' => 'nullable|numeric',
            'category_id'    => 'required|exists:categories,id',
            'stock'          => 'required|integer',
            'image'          => 'nullable|image',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $seller = Auth::guard('seller')->user();
        $seller->products()->create([
            'name'            => $validated['name'],
            'description'     => $validated['description'],
            'price'           => $validated['price'],
            'original_price'  => $validated['original_price'] ?? 0,
            'category_id'     => $validated['category_id'],
            'stock'           => $validated['stock'],
            'image'           => $imagePath,
            'approval_status' => 'Pending',
            'status'          => '1',
        ]);

        return redirect()
            ->route('seller.products.variants', $seller->products()->latest()->first())
            ->with('success', 'Product submitted for review. Configure its variants while we review it.');
    }

    public function edit(Product $product)
    {
        // Ownership check
        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403);
        }

        // Pending products are locked during admin review
        if ($product->approval_status === 'Pending') {
            return redirect()
                ->route('seller.products.index')
                ->with('error', 'This product is currently under admin review and cannot be edited.');
        }

        // Approved products are permanently locked
        if ($product->isApprovalLocked()) {
            return redirect()
                ->route('seller.products.index')
                ->with('error', 'Approved products cannot be edited via this page.');
        }

        $categories = Category::all();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // Ownership check
        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403);
        }

        // Only Rejected products can be re-uploaded by the seller
        if (! $product->canBeReUploaded()) {
            return redirect()
                ->route('seller.products.index')
                ->with('error', 'Only rejected products can be re-submitted for review.');
        }

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'required|string',
            'price'          => 'required|numeric',
            'original_price' => 'nullable|numeric',
            'category_id'    => 'required|exists:categories,id',
            'stock'          => 'required|integer',
            'image'          => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Re-upload: reset the approval workflow back to Pending
        $validated['approval_status'] = 'Pending';
        $validated['rejection_reason'] = null;
        $validated['rejected_by']      = null;
        $validated['rejected_at']      = null;

        $product->update($validated);

        return redirect()
            ->route('seller.products.index')
            ->with('success', 'Product re-submitted successfully. It is now under admin review.');
    }

    public function destroy(Product $product)
    {
        // Ownership check
        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403, 'Unauthorized: you do not own this product.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($product) {

                // 1. Delete variant images from storage + detach size pivot rows
                foreach ($product->variants as $variant) {
                    $variant->sizes()->detach();

                    if ($variant->image) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($variant->image);
                    }

                    $variant->delete();
                }

                // 2. Detach product from all collections (pivot table)
                $product->collections()->detach();

                // 3. Delete wishlist records for this product
                \App\Models\Wishlist::where('product_id', $product->id)->delete();

                // 4. Delete cart records for this product
                \App\Models\Cart::where('product_id', $product->id)->delete();

                // 5. Delete main product image from storage
                if ($product->image) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
                }

                // 6. Finally delete the product itself
                $product->delete();
            });

            return redirect()
                ->route('seller.products.index')
                ->with('success', 'Product deleted successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->route('seller.products.index')
                ->with('error', 'Failed to delete product. Please try again.');
        }
    }
}
