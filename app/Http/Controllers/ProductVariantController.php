<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function manage(Product $product)
    {
        $variants = $product->variants()
            ->with('color', 'sizes')
            ->get();

        $colors = Color::orderBy('name')
            ->get();

        $sizes = Size::orderBy('name')
            ->get();
        
        return view(
            'products.variants.manage',
            compact(
                'product',
                'variants',
                'colors',
                'sizes'
            )
        );
    }

    public function index(Product $product)
    {
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
            'products.variants.index',
            compact(
                'product',
                'colors',
                'existingVariants',
                'sizes'
            )
        );
    }

    public function store(Request $request, Product $product)
    {
        
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

            $variant = $product->variants()->updateOrCreate(
                ['color_id' => (int) $variantData['color_id']],
                $variantDataToSave
            );

            $syncData = [];

        foreach (($variantData['sizes'] ?? []) as $sizeId => $sizeData) {

            if (!isset($sizeData['selected'])) {
                continue;
            }

            $syncData[$sizeId] = [

                'stock' => (int) ($sizeData['stock'] ?? 0),

                'price' => isset($sizeData['price'])
                    ? (float) $sizeData['price']
                    : null,

            ];
        }
            
            $variant->sizes()->sync($syncData);
        }

        return redirect()
            ->route('products.variants', $product)
            ->with('success', 'Variants saved successfully.');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        // Detach all pivot (product_variant_size) records first
        $variant->sizes()->detach();

        // Delete the variant itself
        $variant->delete();

        return response()->json(['success' => true]);
    }
}