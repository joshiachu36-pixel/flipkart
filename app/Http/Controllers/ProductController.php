<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Collection;
use App\Models\Brand;
use App\Models\Seller;
use App\Models\Wishlist;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(
            'category',
            'brand',
            'collections'
        )->get();

        return view('products.index', compact('products'));
    }

      public function create()
        {
           $categories = Category::all();

            $collections = Collection::all();

            $brands = Brand::where('status', 1)
                        ->orderBy('name')
                        ->get();

            $colors = Color::where('status', 1)
                ->orderBy('name')
                ->get();
            return view(
                'products.create',
                compact(
                    'categories',
                    'collections',
                    'brands',
                    'colors'
                )
            );
        }

    public function store(Request $request)
{
    DB::beginTransaction();

    try {

        $image = null;

        if ($request->hasFile('image')) {

            $image = time().'.'.$request->image->extension();

            $request->image->move(
                public_path('uploads'),
                $image
            );

        }

        $product = Product::create([

            'category_id'    => $request->category_id,

            'brand_id'       => $request->brand_id,

            'name'           => $request->name,

            'description'    => $request->description,

            'price'          => str_replace(',', '', $request->price),

            'original_price' => $request->original_price,

            'stock'          => $request->stock,

            'status'         => $request->status,

            'image'          => $image,

        ]);

        $product->collections()->sync(
            $request->collections ?? []
        );

        DB::commit();

        

        if($request->filled('colors'))
        {

            return redirect()->route(
                'products.variants',
                [
                    'product' => $product->id,
                    'colors' => $request->colors
                ]
            );

        }

        

        return redirect('/products')
            ->with(
                'success',
                'Product created successfully.'
            );

    }
    catch(\Exception $e){

        DB::rollBack();

        return back()
            ->withInput()
            ->with(
                'error',
                $e->getMessage()
            );

    }
}

    public function edit($id)
{
    $product = Product::with([
        'collections',
        'variants'
    ])->findOrFail($id);

   $collections = Collection::all();

    $categories = Category::all();

    $brands = Brand::where('status', 1)
                ->orderBy('name')
                ->get();

    $colors = Color::where('status', 1)
    ->orderBy('name')
    ->get();

    return view(
        'products.edit',
        compact(
            'product',
            'collections',
            'categories',
            'brands',
            'colors'
        )
    );
}

    public function update(Request $request, $id)
{
    DB::beginTransaction();

    try {

        $product = Product::findOrFail($id);

        if($request->hasFile('image'))
        {
            $image = time().'.'.$request->image->extension();

            $request->image->move(
                public_path('uploads'),
                $image
            );

            $product->image = $image;
        }

        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->description = $request->description;
        $product->price = str_replace(',', '', $request->price);
        $product->original_price = $request->original_price;
        $product->stock = $request->stock;
        $product->status = $request->status;

        $product->save();

        $product->collections()->sync(
            $request->collections ?? []
        );

        $oldColors = $product->variants()
    ->pluck('color_id')
    ->sort()
    ->values()
    ->toArray();

    $newColors = collect($request->colors ?? [])
        ->map(fn($id) => (int) $id)
        ->sort()
        ->values()
        ->toArray();

    if ($oldColors != $newColors) {

    DB::commit();

    return redirect()->route(
        'products.variants',
        [
            'product' => $product->id,
            'colors' => $request->colors
        ]
    );

}

        DB::commit();

        return redirect('/products')
            ->with(
                'success',
                'Product updated successfully.'
            );

    }
    catch(\Exception $e)
    {
        DB::rollBack();

        return back()
            ->withInput()
            ->with(
                'error',
                $e->getMessage()
            );
    }
}
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect('/products');
    }

       public function viewProducts()
    {
            $products = Product::with(
            'category',
            'brand',
            'collections')->get();

        return view(
            'products.view',
            compact('products')
        );
    }

   public function show($id)
{
    $product = Product::findOrFail($id);

    return view('products.show', compact('product'));
}

public function shop()
{
    $sidebarCategories = Category::whereNull('parent_id')
    ->with('childrenRecursive')
    ->get();

    $products = Product::with('seller')->where(function($q) {
        $q->where('approval_status', 'Approved')
          ->orWhereNull('seller_id');
    })->latest()->get();
    $category = null;
    $collection = null;
    $selectedCollection = null;
    $openIds = [];

    $wishlistProductIds = [];

    if(session()->has('customer_id'))
    {
        $wishlistProductIds = Wishlist::where(
            'customer_id',
            session('customer_id')
        )
        ->pluck('product_id')
        ->toArray();
    }

    // Fetch approved sellers who have at least one approved + active product
    $approvedSellers = Seller::where('status', 'Approved')
        ->whereHas('products', function ($q) {
            $q->where('approval_status', 'Approved')
              ->where('status', 1);
        })
        ->orderBy('business_name')
        ->get();

    return view(
    'products.shop',
    compact(
    'sidebarCategories',
    'products',
    'category',
    'collection',
    'selectedCollection',
    'openIds',
    'wishlistProductIds',
    'approvedSellers'
));
}

public function collectionProducts($slug)
{
    $collection = Collection::where('slug', $slug)
        ->where('status', 1)
        ->firstOrFail();

    $products = $collection->products()
        ->with('seller')
        ->where(function($q) {
            $q->where('approval_status', 'Approved')
              ->orWhereNull('seller_id');
        })
        ->latest()
        ->get();

    $sidebarCategories = Category::whereNull('parent_id')
        ->with('childrenRecursive')
        ->get();

    $category = null;
    $openIds = [];

    $wishlistProductIds = [];

    if(session()->has('customer_id'))
    {
        $wishlistProductIds = Wishlist::where(
            'customer_id',
            session('customer_id')
        )
        ->pluck('product_id')
        ->toArray();
    }

    return view(
        'products.shop',
        compact(
            'sidebarCategories',
            'products',
            'category',
            'collection',
            'openIds',
            'wishlistProductIds'
        )
    );
}


public function search(Request $request)
{
    $search = trim((string) $request->input('search', ''));

    $productsQuery = Product::query()->with('seller')->where(function($q) {
        $q->where('approval_status', 'Approved')
          ->orWhereNull('seller_id');
    })->latest();

    if ($search !== '') {
        $productsQuery->where('name', 'LIKE', "%{$search}%");
    }

    $products = $productsQuery->get();

    $sidebarCategories = Category::whereNull('parent_id')
    ->with('childrenRecursive')
    ->get();
    $category = null;
    $openIds = [];
    $wishlistProductIds = [];

    if(session()->has('customer_id'))
    {
        $wishlistProductIds = Wishlist::where(
            'customer_id',
            session('customer_id')
        )
        ->pluck('product_id')
        ->toArray();
    }

    return view(
    'products.shop',
    compact(
        'sidebarCategories',
        'products',
        'category',
        'openIds',
        'wishlistProductIds'
    ));
}

public function categoryProducts($id)
{
    $category = Category::with('childrenRecursive')
                    ->findOrFail($id);

    $categoryIds = $category->getDescendantIds();

    $products = Product::with('seller')->whereIn(
                    'category_id',
                    $categoryIds
                )->where(function($q) {
                    $q->where('approval_status', 'Approved')
                      ->orWhereNull('seller_id');
                })->get();

    $rootCategory = Category::with('childrenRecursive')
        ->find($category->getRootCategory()->id);

    $sidebarCategories = collect([
        $rootCategory->pruneTree($category->id)]);

        $openIds = $category->getAncestorIds();

    return view(
    'products.shop',
    compact(
        'sidebarCategories',
        'products',
        'category',
        'openIds'
    )
);
}

public function productDetails(Request $request, $id)
{
        $product = Product::with([
            'category.parent.parent.parent',
            'collections',
            'variants.color',
            'variants.sizes',
            'seller',
        ])->findOrFail($id);

        $collection = null;

        if ($request->filled('collection')) {

            $collection = Collection::where(
                'slug',
                $request->collection
            )
            ->where('status', 1)
            ->first();

        }

        $relatedProducts = Product::where(
        'category_id',$product->category_id)
            ->where('id', '!=', $product->id)
            ->where(function ($q) {
                $q->where('approval_status', 'Approved')
                  ->orWhereNull('seller_id');
            })
            ->latest()
            ->take(4)
            ->get();

            

        return view(
    'products.product-details',
    compact(
        'product',
        'relatedProducts',
        'collection'
    ));

    
}

}
