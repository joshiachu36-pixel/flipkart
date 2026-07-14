<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\SizeController;
use App\Models\Product;

Route::get('test', function () {
    $products = Product::with('category')->get();
    dd($products);
});

Route::get('/', function () {
    return view('layout.admin');
});

Route::get('/products',
    [ProductController::class,'index']);

Route::get('/product/create',
    [ProductController::class,'create']);

Route::post('/product/store',
    [ProductController::class,'store']);

Route::get('/product/edit/{id}',
    [ProductController::class,'edit']);

Route::post('/product/update/{id}',
    [ProductController::class,'update']);

Route::get('/product/delete/{id}',
    [ProductController::class,'destroy']);

Route::get('/product/view', [ProductController::class, 'viewProducts']);

Route::get('/product/{id}', [ProductController::class, 'show']);

Route::get('/search', [ProductController::class, 'search'])
        ->name('product.search');



Route::get('/categories',
    [CategoryController::class,'index']);

Route::get('/category/create',
    [CategoryController::class,'create']);

Route::post('/category/store',
    [CategoryController::class,'store']);

Route::get('/category/edit/{id}',
    [CategoryController::class,'edit']);

Route::post('/category/update/{id}',
    [CategoryController::class,'update']);

Route::get('/category/delete/{id}',
    [CategoryController::class,'destroy']);



Route::get('/shop',
    [ProductController::class,'shop']);



Route::get('/product-details/{id}',
    [ProductController::class,'productDetails']);

Route::get('/category/{id}',
    [ProductController::class, 'categoryProducts']);

Route::resource('collections', CollectionController::class);

Route::resource('colors', ColorController::class);

// Brand Routes

Route::get('/brands',
    [BrandController::class, 'index']);

Route::get('/brand/create',
    [BrandController::class, 'create']);

Route::post('/brand/store',
    [BrandController::class, 'store']);

Route::get('/brand/edit/{id}',
    [BrandController::class, 'edit']);

Route::post('/brand/update/{id}',
    [BrandController::class, 'update']);

Route::get('/brand/delete/{id}',
    [BrandController::class, 'destroy']);

Route::get('/register', [CustomerController::class, 'showRegister'])
    ->name('customer.register');

Route::post('/register', [CustomerController::class, 'register'])
    ->name('customer.register.store');

Route::get('/login', [CustomerController::class, 'showLogin'])
    ->name('customer.login');

Route::post('/login', [CustomerController::class, 'login'])
    ->name('customer.login.store');

Route::post('/logout', [CustomerController::class, 'logout'])
    ->name('customer.logout');

Route::get('/profile', [CustomerController::class, 'profile'])
    ->middleware('customer')
    ->name('customer.profile');

Route::post('/cart/add/{product}', [CartController::class, 'add'])
    ->middleware('customer')
    ->name('cart.add');

Route::middleware('customer')->group(function () {

    Route::post('/cart/add/{product}',
        [CartController::class, 'add'])
        ->name('cart.add');

    Route::get('/cart',
        [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart/increase/{cart}',
        [CartController::class, 'increase'])
        ->name('cart.increase');

    Route::post('/cart/decrease/{cart}',
        [CartController::class, 'decrease'])
        ->name('cart.decrease');

    Route::delete('/cart/remove/{cart}',
    [CartController::class, 'remove'])
    ->name('cart.remove');

    // Wishlist Routes
    Route::post('/wishlist/toggle/{product}',
    [WishlistController::class, 'toggle'])
    ->name('wishlist.toggle');

    Route::get('/wishlist',
        [WishlistController::class, 'index'])
        ->name('wishlist.index');

    Route::delete('/wishlist/remove/{wishlist}',
        [WishlistController::class, 'remove'])
        ->name('wishlist.remove');

});

Route::get('/shop/collection/{slug}', [ProductController::class, 'collectionProducts'])
    ->name('shop.collection');

Route::get('/variant-test', function () {
    return view('products.variants.index', [
        'product' => (object)['id' => 1, 'name' => 'Test Product'],
        'colors' => collect([
            (object)['id' => 1, 'name' => 'Black'],
            (object)['id' => 2, 'name' => 'Blue'],
        ])
    ]);
});

Route::resource('sizes', SizeController::class);

use App\Http\Controllers\SellerAuthController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\SellerProductController;
use App\Http\Controllers\SellerReportController;
use App\Http\Controllers\AdminSellerController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminReportController;

// --- Seller Auth Routes ---
Route::prefix('seller')->name('seller.')->group(function () {
    Route::get('/register', [SellerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [SellerAuthController::class, 'register'])->name('register.store');
    Route::get('/login', [SellerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [SellerAuthController::class, 'login'])->name('login.store');
});

// --- Seller Protected Routes ---
Route::prefix('seller')->name('seller.')->middleware(['seller'])->group(function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [SellerReportController::class, 'index'])->name('reports.index');
    Route::post('/logout', [SellerAuthController::class, 'logout'])->name('logout');

    
    // Seller Products (destroy is included — seller can delete their own products)
    Route::resource('products', SellerProductController::class)->except(['show']);

    // Seller Product Variants
    Route::get('/products/{product}/variants', [\App\Http\Controllers\SellerProductVariantController::class, 'index'])->name('products.variants');
    Route::get('/products/{product}/variants/manage', [\App\Http\Controllers\SellerProductVariantController::class, 'manage'])->name('products.variants.manage');
    Route::post('/products/{product}/variants', [\App\Http\Controllers\SellerProductVariantController::class, 'store'])->name('products.variants.store');
    Route::delete('/products/{product}/variants/{variant}', [\App\Http\Controllers\SellerProductVariantController::class, 'destroy'])->name('products.variants.destroy');
});

// --- Admin Auth Routes ---
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.store');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// --- Admin Marketplace Routes ---
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/sellers', [AdminSellerController::class, 'index'])->name('sellers.index');
    Route::post('/sellers/{seller}/update-status', [AdminSellerController::class, 'updateStatus'])->name('sellers.update');

    // Product Approval — full index + legacy pending redirect
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/pending', [AdminProductController::class, 'pending'])->name('products.pending');
    Route::post('/products/{product}/update-status', [AdminProductController::class, 'updateStatus'])->name('products.update');

    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
});

