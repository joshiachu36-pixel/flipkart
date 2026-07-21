<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\SellerActivity;
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
            ->orderBy('priority', 'asc')
            ->get();

        $sellerId = Auth::guard('seller')->id();
        $colors = Color::forSeller($sellerId)->where('status', 1)->orderBy('name')->get();
        $sizes  = Size::forSeller($sellerId)->where('status', 1)->orderBy('name')->get();

        return view(
            'seller.products.variants.manage',
            compact('product', 'variants', 'colors', 'sizes')
        );
    }

    public function index(Product $product)
    {
        return $this->manage($product);
    }

    public function store(Request $request, Product $product)
    {
        $this->checkOwnership($product);

        $sellerId = Auth::guard('seller')->id();
        $variants = $request->input('variants', []);

        // Validate priority uniqueness per product before saving
        $prioritiesCount = [];
        $submittedColorIds = [];

        foreach ($variants as $index => $variantData) {
            if (!empty($variantData['color_id'])) {
                $submittedColorIds[] = (int) $variantData['color_id'];
            }
        }

        foreach ($variants as $index => $variantData) {
            if (empty($variantData['color_id'])) {
                continue;
            }

            if (isset($variantData['priority']) && $variantData['priority'] !== '') {
                $p = (int) $variantData['priority'];
                if ($p < 1) {
                    $validator = \Illuminate\Support\Facades\Validator::make([], []);
                    $validator->errors()->add("variants.{$index}.priority", "Priority must be a positive integer.");
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $prioritiesCount[$p][] = $index;
            }
        }

        $validator = \Illuminate\Support\Facades\Validator::make([], []);
        $hasError = false;

        // 1. Detect duplicate priorities submitted within the current form
        foreach ($prioritiesCount as $p => $indices) {
            if (count($indices) > 1) {
                $hasError = true;
                foreach ($indices as $index) {
                    $validator->errors()->add(
                        "variants.{$index}.priority",
                        "Priority {$p} is already assigned to another variant of this product. Each variant must have a unique priority."
                    );
                }
            }
        }

        // 2. Detect conflicts with existing variants of this product not present in current form
        if (!$hasError) {
            foreach ($variants as $index => $variantData) {
                if (empty($variantData['color_id']) || !isset($variantData['priority']) || $variantData['priority'] === '') {
                    continue;
                }

                $p = (int) $variantData['priority'];
                $colorId = (int) $variantData['color_id'];

                $existingConflict = $product->variants()
                    ->where('priority', $p)
                    ->where('color_id', '!=', $colorId)
                    ->whereNotIn('color_id', $submittedColorIds)
                    ->exists();

                if ($existingConflict) {
                    $hasError = true;
                    $validator->errors()->add(
                        "variants.{$index}.priority",
                        "Priority {$p} is already assigned to another variant of this product. Each variant must have a unique priority."
                    );
                }
            }
        }

        if ($hasError) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($product, $variants, $request, $sellerId) {
            // Temporarily set negative priorities for variants being updated to prevent transient DB unique collisions
            $existingVariantIds = [];
            foreach ($variants as $variantData) {
                if (!empty($variantData['color_id'])) {
                    $v = $product->variants()->where('color_id', (int) $variantData['color_id'])->first();
                    if ($v) {
                        $existingVariantIds[] = $v->id;
                    }
                }
            }

            if (!empty($existingVariantIds)) {
                \Illuminate\Support\Facades\DB::table('product_variants')
                    ->whereIn('id', $existingVariantIds)
                    ->update(['priority' => \Illuminate\Support\Facades\DB::raw('-id')]);
            }

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

                $syncData = [];
                $variantStock = 0;
                $firstSizePrice = 0;
                $firstSizeOriginalPrice = 0;

                foreach (($variantData['sizes'] ?? []) as $sizeId => $sizeData) {
                    if (!isset($sizeData['selected'])) {
                        continue;
                    }

                    $sPrice = isset($sizeData['price']) && $sizeData['price'] !== '' ? (float) $sizeData['price'] : 0;
                    $sOrigPrice = isset($sizeData['original_price']) && $sizeData['original_price'] !== '' ? (float) $sizeData['original_price'] : 0;
                    $sStock = (int) ($sizeData['stock'] ?? 0);

                    if ($firstSizePrice == 0 && $sPrice > 0) {
                        $firstSizePrice = $sPrice;
                    }
                    if ($firstSizeOriginalPrice == 0 && $sOrigPrice > 0) {
                        $firstSizeOriginalPrice = $sOrigPrice;
                    }
                    $variantStock += $sStock;

                    $syncData[$sizeId] = [
                        'stock'          => $sStock,
                        'price'          => $sPrice,
                        'original_price' => $sOrigPrice,
                    ];
                }

                // Fallback prices if direct input provided
                if ($firstSizePrice == 0 && isset($variantData['price'])) {
                    $firstSizePrice = (float) $variantData['price'];
                }
                if ($firstSizeOriginalPrice == 0 && isset($variantData['original_price'])) {
                    $firstSizeOriginalPrice = (float) $variantData['original_price'];
                }

                $colorObj = Color::find($variantData['color_id']);
                $colorName = $colorObj ? strtoupper(substr($colorObj->name, 0, 3)) : 'CLR';
                $generatedSku = !empty($variantData['sku']) 
                    ? strtoupper(trim($variantData['sku'])) 
                    : 'SKU-' . $product->id . '-' . $colorName . '-' . ($index + 1);

                $variantDataToSave = [
                    'price'          => $firstSizePrice,
                    'original_price' => $firstSizeOriginalPrice,
                    'stock'          => $variantStock,
                    'priority'       => (int) ($variantData['priority'] ?? ($index + 1)),
                    'sku'            => $generatedSku,
                    'status'         => (int) ($variantData['status'] ?? 1),
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

                $variant->sizes()->sync($syncData);
            }

            // Sync main product level totals for backward compatibility
            $allVariants = $product->variants()->where('status', 1)->orderBy('priority', 'asc')->get();
            if ($allVariants->count() > 0) {
                $defaultVar = $allVariants->first();
                $totalProductStock = $allVariants->sum('stock');

                $productUpdate = [
                    'stock' => $totalProductStock,
                ];
                if ($defaultVar->price > 0) {
                    $productUpdate['price'] = $defaultVar->price;
                }
                if ($defaultVar->original_price > 0) {
                    $productUpdate['original_price'] = $defaultVar->original_price;
                }
                if ($defaultVar->image) {
                    $productUpdate['image'] = $defaultVar->image;
                }
                $product->update($productUpdate);
            }
        });

        SellerActivity::log($sellerId, 'Updated Variants', "Updated product variants for: {$product->name}");

        return redirect()
            ->route('seller.products.variants.manage', $product)
            ->with('success', 'Product variants updated successfully.');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        $this->checkOwnership($product);

        if ($variant->product_id !== $product->id) {
            abort(403, 'Unauthorized action.');
        }

        $variant->sizes()->detach();
        $variant->delete();

        SellerActivity::log(Auth::guard('seller')->id(), 'Deleted Variant', "Deleted variant from product: {$product->name}");

        return response()->json(['success' => true]);
    }
}
