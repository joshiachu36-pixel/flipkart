<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerProductVariantController extends Controller
{
    private function checkOwnership(Product $product)
    {
        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function manage(Product $product)
    {
        $this->checkOwnership($product);

        $variants = $product->variants()
            ->with('color', 'sizes')
            ->get();

        $colors = Color::orderBy('name')->get();
        $sizes = Size::orderBy('name')->get();
        
        return view(
            'seller.products.variants.manage',
            compact('product', 'variants', 'colors', 'sizes')
        );
    }

    public function index(Product $product)
    {
        $this->checkOwnership($product);

        $colors = Color::whereIn(
            'id',
            request()->colors ?? []
        )->get();

        $existingVariants = $product->variants()
            ->with('sizes')
            ->get()
            ->keyBy('color_id');

        $sizes = Size::orderBy('name')->get();

        return view(
            'seller.products.variants.index',
            compact('product', 'colors', 'existingVariants', 'sizes')
        );
    }

    public function store(Request $request, Product $product)
    {
        $this->checkOwnership($product);
        
        $variants = $request->input('variants', []);

        foreach ($variants as $index => $variantData) {
            if (empty($variantData['color_id'])) {
                continue;
            }

            $imagePath = '';
            if ($request->hasFile('variants.' . $index . '.image')) {
                $imagePath = $request
                    ->file('variants.' . $index . '.image')
                    ->store('variant-images', 'public');
            }

            $defaultPrice = 0;
            foreach (($variantData['sizes'] ?? []) as $sizeData) {
                if (isset($sizeData['selected'])) {
                    $defaultPrice = (float) ($sizeData['price'] ?? 0);
                    break;
                }
            }

            $variantDataToSave = [
                'price' => $defaultPrice,
                'stock' => (int) ($variantData['stock'] ?? 0),
                'status' => (int) ($variantData['status'] ?? 1),
            ];
            
            if ($imagePath !== '') {
                $variantDataToSave['image'] = $imagePath;
            }

            $variant = $product->variants()->where('color_id', (int) $variantData['color_id'])->first();
            if ($variant) {
                $variant->update($variantDataToSave);
            } else {
                if (!isset($variantDataToSave['image'])) {
                    $variantDataToSave['image'] = '';
                }
                $variantDataToSave['color_id'] = (int) $variantData['color_id'];
                $variant = $product->variants()->create($variantDataToSave);
            }

            $syncData = [];
            foreach (($variantData['sizes'] ?? []) as $sizeId => $sizeData) {
                if (!isset($sizeData['selected'])) {
                    continue;
                }
                $syncData[$sizeId] = [
                    'stock' => (int) ($sizeData['stock'] ?? 0),
                    'price' => isset($sizeData['price']) ? (float) $sizeData['price'] : null,
                ];
            }
            
            $variant->sizes()->sync($syncData);
        }

        return redirect()
            ->route('seller.products.variants', $product)
            ->with('success', 'Variants saved successfully.');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        $this->checkOwnership($product);

        if ($variant->product_id !== $product->id) {
            abort(403, 'Unauthorized action.');
        }

        $variant->sizes()->detach();
        $variant->delete();

        return response()->json(['success' => true]);
    }
}
