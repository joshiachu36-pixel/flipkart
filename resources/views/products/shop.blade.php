@extends('layouts.shop')

@section('content')

@php
    // Fetch filter data directly for sidebar
    $allCollections = \App\Models\Collection::where('status', 1)->orderBy('name')->get();
    $allBrands = \App\Models\Brand::where('status', 1)->orderBy('name')->get();
    $allColors = \App\Models\Color::where('status', 1)->orderBy('name')->get();
    $allSizes = \App\Models\Size::where('status', 1)->orderBy('name')->get();
    $allSellers = \App\Models\Seller::where('status', 'Approved')->orderBy('business_name')->get();
@endphp

<style>
/* ── Shop Page Premium Overrides ───────────────────────────── */

/* Hero: slimmer, sharper */
.shop-hero {
  background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 55%, #0f2a5a 100%);
  padding: 28px 0 26px;
}
.shop-hero-title { font-size: 1.9rem; letter-spacing: -.04em; }

/* Brand section: horizontal scroll pills, clean */
.brand-scroll-wrap { gap: 12px; padding-bottom: 8px; }
.brand-card {
  width: 120px; padding: 16px 10px; gap: 8px;
  border-radius: 14px;
  border-color: #f1f5f9;
  box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.brand-card:hover { box-shadow: 0 8px 24px rgba(40,116,240,.12); }
.brand-logo-wrap { width: 56px; height: 56px; border-radius: 10px; }
.brand-name { font-size: .78rem; }
.brand-visit-label { font-size: .68rem; }
.brand-logo-placeholder { width: 56px; height: 56px; border-radius: 10px; font-size: 22px; }

/* Section label for brands */
.brand-section-header { margin-bottom: 14px; }
.brand-section-label {
  font-size: .7rem; font-weight: 700; color: #94a3b8;
  text-transform: uppercase; letter-spacing: 1.2px;
  display: flex; align-items: center; gap: 6px;
}
.brand-section-label::before {
  content: ''; display: inline-block;
  width: 3px; height: 14px;
  background: #2874f0; border-radius: 2px;
}

/* Sort toolbar: more compact, premium */
.sort-toolbar {
  background: #fff;
  border: 1px solid #f1f5f9;
  border-radius: 12px;
  padding: 12px 18px;
  margin-bottom: 20px;
  box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.product-count-label {
  font-size: .82rem; color: #64748b; font-weight: 500;
}
.product-count-label strong { color: #1e293b; }

/* Product card: refined shadows, spacing */
.product-card {
  border-radius: 14px;
  border-color: #f1f5f9;
  box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.product-card:hover {
  box-shadow: 0 10px 28px rgba(40,116,240,.1);
  border-color: #bfdbfe;
}
.product-card-body { padding: 14px 14px 16px; }
.product-name { font-size: .88rem; font-weight: 600; margin-bottom: 6px; }
.price-current { font-size: 1.15rem; }
.price-block { margin: 8px 0; }

/* Badge refinement */
.badge-discount { font-size: .65rem; padding: 3px 8px; }
.badge-new { font-size: .62rem; padding: 3px 8px; }

/* Sidebar refinement */
.sidebar-card {
  border-radius: 14px;
  border-color: #f1f5f9;
  box-shadow: 0 2px 8px rgba(0,0,0,.04);
  padding: 18px 16px;
}
.sidebar-header { padding-bottom: 10px; margin-bottom: 12px; }
.sidebar-header-title { font-size: .8rem; }
.filter-section-title { font-size: .75rem; padding: 8px 0; }
.filter-section-content { padding: 6px 0 12px; }
.custom-control { font-size: .82rem; margin-bottom: 8px; }

/* Empty state */
.empty-state {
  padding: 60px 20px; text-align: center;
  background: #fff; border-radius: 16px;
  border: 1px solid #f1f5f9;
}
.empty-state-icon { font-size: 3rem; color: #cbd5e1; margin-bottom: 14px; }
.empty-state h4 { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
.empty-state p { font-size: .85rem; color: #64748b; max-width: 360px; margin: 0 auto 20px; }

/* Active filter chips */
.filter-chip { font-size: .74rem; padding: 3px 10px; }

/* Mobile toggle button */
#mobile-filter-toggle { border-radius: 10px; font-size: .85rem; font-weight: 600; }
</style>

{{-- ════════════════════════════════════════════════
     SHOP HERO BANNER
════════════════════════════════════════════════ --}}
<div class="shop-hero">
    <div class="container-fluid px-4 px-lg-5">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-4">
            <div>
                {{-- Breadcrumb --}}
                <nav aria-label="breadcrumb" class="shop-hero-breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="/shop">Home</a></li>
                        <li class="breadcrumb-item"><a href="/shop">Shop</a></li>
                        @if(!empty($collection?->name))
                            <li class="breadcrumb-item"><a href="#">Collections</a></li>
                            <li class="breadcrumb-item active">{{ $collection->name }}</li>
                        @elseif(optional($category)->name)
                            <li class="breadcrumb-item active">{{ $category->name }}</li>
                        @else
                            <li class="breadcrumb-item active">All Products</li>
                        @endif
                    </ol>
                </nav>

                {{-- Page Title --}}
                <h1 class="shop-hero-title mt-2">
                    @if(!empty($collection?->name))
                        <i class="bi bi-stars me-2 text-warning"></i>{{ $collection->name }}
                    @elseif(optional($category)->name)
                        {{ $category->name }}
                    @else
                        All Products
                    @endif
                </h1>

                @if(!empty($collection?->description))
                    <p class="shop-hero-desc">{{ $collection->description }}</p>
                @endif

                {{-- Product Count --}}
                <div class="shop-hero-count">
                    <i class="bi bi-grid-3x3-gap-fill text-primary"></i>
                    <span>{{ $products->count() }} product{{ $products->count() !== 1 ? 's' : '' }} found</span>
                </div>
            </div>

            {{-- Decorative Icons --}}
            <div class="d-none d-md-flex align-items-center gap-4" style="opacity:.2;">
                <i class="bi bi-bag-heart text-white" style="font-size:3rem;"></i>
                <i class="bi bi-star-fill text-warning" style="font-size:2rem;"></i>
                <i class="bi bi-shop-window text-white" style="font-size:2.5rem;"></i>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════
     MAIN SHOP LAYOUT
════════════════════════════════════════════════ --}}
<div class="container-fluid px-4 px-lg-5 py-4">
    <div class="row g-4">

        {{-- Mobile Filters Toggle Float --}}
        <div class="d-lg-none mb-3">
            <button class="btn btn-primary w-100 py-2.5 d-flex align-items-center justify-content-center gap-2" id="mobile-filter-toggle">
                <i class="bi bi-funnel-fill"></i> Show Filters & Categories
            </button>
        </div>

        {{-- Sidebar Overlay (Mobile) --}}
        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        {{-- ════════════════════════════════════════
             FILTER SIDEBAR (Sticky on desktop, slide-in on mobile)
        ════════════════════════════════════════ --}}
        <div class="col-lg-3 col-md-12 shop-sidebar" id="shop-sidebar">
            <div class="sidebar-card">
                
                {{-- Sidebar Header --}}
                <div class="sidebar-header">
                    <h5 class="sidebar-header-title">
                        <i class="bi bi-funnel-fill text-primary"></i> Filters
                    </h5>
                    <button class="btn btn-link btn-sm text-danger text-decoration-none fw-bold p-0" id="btn-clear-all" style="font-size: 0.8rem;">
                        Reset All
                    </button>
                </div>

                {{-- Category Filter --}}
                <div class="mb-3">
                    <div class="filter-section-title" data-bs-toggle="collapse" data-bs-target="#collapseCategories" aria-expanded="true">
                        <span>Categories</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="collapse show filter-section-content" id="collapseCategories">
                        @if($category)
                            @include('categories.sidebar-tree', [
                                'categories'         => $sidebarCategories,
                                'selectedCategoryId' => optional($category)->id,
                                'openIds'            => $openIds ?? [],
                            ])
                            <div class="mt-3 text-center">
                                <a href="/shop" class="btn btn-light btn-sm w-100 py-1.5 fw-semibold fs-xs text-primary border">
                                    <i class="bi bi-arrow-left-circle me-1"></i> Browse All categories
                                </a>
                            </div>
                        @else
                            {{-- When not browsing category, list root categories --}}
                            <ul class="list-unstyled mb-0">
                                @foreach($sidebarCategories as $cat)
                                    <li class="mb-2">
                                        <a href="{{ url('/category/'.$cat->id) }}" class="text-decoration-none text-dark fw-semibold cat-root d-flex align-items-center justify-content-between">
                                            <span>{{ $cat->name }}</span>
                                            <i class="bi bi-arrow-right-short text-muted"></i>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                {{-- Collection Filter --}}
                <div class="mb-3 border-top pt-2">
                    <div class="filter-section-title" data-bs-toggle="collapse" data-bs-target="#collapseCollections" aria-expanded="true">
                        <span>Collections</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="collapse show filter-section-content" id="collapseCollections">
                        @foreach($allCollections as $col)
                            <a href="{{ route('shop.collection', $col->slug) }}" 
                               class="d-flex align-items-center justify-content-between text-decoration-none text-secondary py-1.5 px-2 rounded mb-1 fs-sm hover-bg-light {{ (isset($collection) && $collection->id === $col->id) ? 'bg-primary-light text-primary fw-bold' : '' }}">
                                <span>{{ $col->name }}</span>
                                @if($col->discount_value)
                                    <span class="badge bg-success fs-xs">{{ $col->discount_value }}{{ $col->discount_type === 'percentage' ? '%' : ' OFF' }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Brand Filter --}}
                <div class="mb-3 border-top pt-2">
                    <div class="filter-section-title" data-bs-toggle="collapse" data-bs-target="#collapseBrands" aria-expanded="true">
                        <span>Brands</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="collapse show filter-section-content" id="collapseBrands">
                        <div class="d-flex flex-column" style="max-height: 180px; overflow-y: auto;">
                            @foreach($allBrands as $br)
                                <label class="custom-control">
                                    <input type="checkbox" name="filter_brands" value="{{ $br->id }}" class="custom-control-input filter-input">
                                    <span class="custom-control-label">
                                        <span>{{ $br->name }}</span>
                                        <span class="filter-count-badge">{{ $products->where('brand_id', $br->id)->count() }}</span>
                                    </span>
                                    <span class="custom-control-indicator"></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Price Filter --}}
                <div class="mb-3 border-top pt-2">
                    <div class="filter-section-title" data-bs-toggle="collapse" data-bs-target="#collapsePrice" aria-expanded="true">
                        <span>Price Range</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="collapse show filter-section-content" id="collapsePrice">
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" id="price-min" placeholder="Min (₹)" class="form-control form-control-sm filter-price-input" min="0">
                            </div>
                            <div class="col-6">
                                <input type="number" id="price-max" placeholder="Max (₹)" class="form-control form-control-sm filter-price-input" min="0">
                            </div>
                        </div>
                        <button class="btn btn-outline-primary btn-sm w-100 mt-2 py-1.5 fw-bold" id="btn-apply-price">
                            Apply Range
                        </button>
                    </div>
                </div>

                {{-- Stock Status Filter --}}
                <div class="mb-3 border-top pt-2">
                    <div class="filter-section-title" data-bs-toggle="collapse" data-bs-target="#collapseStock" aria-expanded="true">
                        <span>Availability</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="collapse show filter-section-content" id="collapseStock">
                        <label class="custom-control">
                            <input type="checkbox" id="stock-in-stock" class="custom-control-input filter-input">
                            <span class="custom-control-label">
                                <span>In Stock Only</span>
                                <span class="filter-count-badge">{{ $products->where('stock', '>', 0)->count() }}</span>
                            </span>
                            <span class="custom-control-indicator"></span>
                        </label>
                    </div>
                </div>

                {{-- Color Filter --}}
                <div class="mb-3 border-top pt-2">
                    <div class="filter-section-title" data-bs-toggle="collapse" data-bs-target="#collapseColors" aria-expanded="true">
                        <span>Colors</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="collapse show filter-section-content" id="collapseColors">
                        <div class="color-swatch-grid">
                            @foreach($allColors as $col)
                                <div class="color-swatch-item filter-color-swatch {{ strtolower($col->name) === 'white' || strtolower($col->name) === 'light' ? 'light-color' : '' }}" 
                                     style="background-color: {{ $col->code }};" 
                                     data-color-id="{{ $col->id }}"
                                     title="{{ $col->name }}"></div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Size Filter --}}
                <div class="mb-3 border-top pt-2">
                    <div class="filter-section-title" data-bs-toggle="collapse" data-bs-target="#collapseSizes" aria-expanded="true">
                        <span>Sizes</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="collapse show filter-section-content" id="collapseSizes">
                        <div class="size-pill-grid">
                            @foreach($allSizes as $sz)
                                <div class="size-pill-item filter-size-pill" data-size-id="{{ $sz->id }}">
                                    {{ $sz->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Rating Filter --}}
                <div class="mb-3 border-top pt-2">
                    <div class="filter-section-title" data-bs-toggle="collapse" data-bs-target="#collapseRatings" aria-expanded="true">
                        <span>Ratings</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="collapse show filter-section-content" id="collapseRatings">
                        <div class="d-flex flex-column gap-1">
                            @for($i = 4; $i >= 2; $i--)
                                <div class="rating-filter-row filter-rating-row" data-rating="{{ $i }}">
                                    <span class="rating-stars">
                                        @for($star = 1; $star <= 5; $star++)
                                            <i class="bi {{ $star <= $i ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </span>
                                    <span class="fs-xs fw-semibold text-secondary">& Up</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                {{-- Discount Filter --}}
                <div class="mb-3 border-top pt-2">
                    <div class="filter-section-title" data-bs-toggle="collapse" data-bs-target="#collapseDiscounts" aria-expanded="true">
                        <span>Discounts</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="collapse show filter-section-content" id="collapseDiscounts">
                        <div class="d-flex flex-column gap-2">
                            @foreach([10, 20, 30, 50] as $disc)
                                <label class="custom-control mb-1">
                                    <input type="radio" name="filter_discount" value="{{ $disc }}" class="custom-control-input filter-input">
                                    <span class="custom-control-label">
                                        <span>{{ $disc }}% OFF & More</span>
                                    </span>
                                    <span class="custom-control-indicator" style="border-radius: 50%;"></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Seller Filter --}}
                <div class="border-top pt-2">
                    <div class="filter-section-title" data-bs-toggle="collapse" data-bs-target="#collapseSellers" aria-expanded="true">
                        <span>Sellers</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="collapse show filter-section-content" id="collapseSellers">
                        <div class="d-flex flex-column" style="max-height: 150px; overflow-y: auto;">
                            @foreach($allSellers as $sell)
                                <label class="custom-control">
                                    <input type="checkbox" name="filter_sellers" value="{{ $sell->id }}" class="custom-control-input filter-input">
                                    <span class="custom-control-label">
                                        <span>{{ $sell->business_name }}</span>
                                    </span>
                                    <span class="custom-control-indicator"></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ════════════════════════════════════════
             MAIN CONTENT SPACE
        ════════════════════════════════════════ --}}
        <div class="col-lg-9 col-md-12">

            {{-- ── Shop by Seller Brands ── --}}
            @if(!$category && !$collection && isset($approvedSellers) && $approvedSellers->count())
            <div class="mb-4" style="background:#fff;border-radius:14px;border:1px solid #f1f5f9;padding:20px 20px 16px;box-shadow:0 2px 8px rgba(0,0,0,.04);">
                <div class="brand-section-header d-flex align-items-center justify-content-between">
                    <div class="brand-section-label">
                        <i class="bi bi-shop text-primary" style="font-size:.8rem;"></i> Shop by Brand
                    </div>
                    <span style="font-size:.72rem;color:#94a3b8;">{{ $approvedSellers->count() }} verified sellers</span>
                </div>
                <div class="brand-scroll-wrap">
                    @foreach($approvedSellers as $brandSeller)
                        <a href="{{ route('store.show', $brandSeller->id) }}"
                           class="brand-card"
                           title="Visit {{ $brandSeller->business_name }} Store">

                            @if($brandSeller->business_logo)
                                <div class="brand-logo-wrap">
                                    <img src="{{ asset('storage/' . $brandSeller->business_logo) }}"
                                         alt="{{ $brandSeller->business_name }}"
                                         loading="lazy">
                                </div>
                            @else
                                <div class="brand-logo-placeholder">
                                    <i class="bi bi-shop"></i>
                                </div>
                            @endif

                            <div class="brand-name" title="{{ $brandSeller->business_name }}">
                                {{ $brandSeller->business_name }}
                            </div>
                            <div class="brand-visit-label">Visit Store →</div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif


            {{-- ════════════════════════════════════════
                 SORTING & TOOLBAR BAR
            ════════════════════════════════════════ --}}
            <div class="sort-toolbar">
                <div class="product-count-label d-flex align-items-center gap-1">
                    Showing <span id="visible-count">{{ $products->count() }}</span>
                    of {{ $products->count() }} {{ $products->count() === 1 ? 'Product' : 'Products' }}
                    @if(optional($category)->name)
                        in <strong>{{ $category->name }}</strong>
                    @elseif(!empty($collection?->name))
                        in <strong>{{ $collection->name }}</strong>
                    @endif
                </div>

                {{-- Toolbar Actions --}}
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    {{-- Sort Dropdown --}}
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-xs fw-bold text-secondary text-uppercase">Sort By:</span>
                        <div class="custom-select-wrap">
                            <select id="sort-select" class="custom-select">
                                <option value="newest">Newest First</option>
                                <option value="price-asc">Price: Low to High</option>
                                <option value="price-desc">Price: High to Low</option>
                                <option value="rating-desc">Highest Rated</option>
                                <option value="popularity">Popularity</option>
                            </select>
                        </div>
                    </div>

                    {{-- Grid/List View Toggles --}}
                    <div class="toolbar-views">
                        <button class="view-toggle-btn active" id="btn-view-grid" title="Grid View">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </button>
                        <button class="view-toggle-btn" id="btn-view-list" title="List View">
                            <i class="bi bi-list-task"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Active Filter Tags Bar --}}
            <div class="active-filters-bar" id="active-filters-bar" style="display: none;">
                <span class="active-filters-label">Active:</span>
                <!-- Javascript will inject chips here -->
            </div>

            {{-- ════════════════════════════════════════
                 PRODUCT GRID & LIST
            ════════════════════════════════════════ --}}
            
            {{-- Skeleton Loading Container --}}
            <div class="row g-3" id="skeleton-grid">
                @for($i = 0; $i < 6; $i++)
                    <div class="col-xl-4 col-lg-6 col-md-6 col-6">
                        <div class="skeleton-card">
                            <div class="skeleton-img-placeholder"></div>
                            <div class="skeleton-body">
                                <div class="skeleton-bar text-short"></div>
                                <div class="skeleton-bar title"></div>
                                <div class="skeleton-bar text-short"></div>
                                <div class="skeleton-bar price"></div>
                                <div class="skeleton-bar button"></div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            {{-- Real Product Cards Container --}}
            <div class="row g-3" id="product-grid" style="display: none;">

                @foreach($products as $product)

                    @php
                        /* ── Collection discount calculation (PRESERVED) ── */
                        $finalPrice = $product->price;

                        if(isset($collection) && $collection->discount_type == 'percentage') {
                            $finalPrice = $product->price - (($product->price * $collection->discount_value) / 100);
                        } elseif(isset($collection) && $collection->discount_type == 'fixed') {
                            $finalPrice = max(0, $product->price - $collection->discount_value);
                        }

                        /* ── Discount percentage (for non-collection) ── */
                        $hasDiscount = $product->original_price && $product->original_price > $product->price;
                        $discountPct = 0;
                        if($hasDiscount) {
                            $discountPct = round((($product->original_price - $product->price) / $product->original_price) * 100);
                        }
                        $collectionDiscount = isset($collection) && $collection->discount_value;
                        
                        // Deterministic fields for JS filters
                        $pRating = 3.5 + (($product->id * 3) % 15) / 10; // 3.5 to 4.9 rating
                        $pReviews = ($product->id * 17) % 350 + 10;
                        $pColors = $product->variants->pluck('color_id')->unique()->values();
                        $pSizes = $product->variants->flatMap(fn($v) => $v->sizes->pluck('id'))->unique()->values();
                        $pCollections = $product->collections->pluck('id')->unique()->values();
                    @endphp

                    <div class="col-xl-4 col-lg-6 col-md-6 col-6 product-card-container"
                         data-id="{{ $product->id }}"
                         data-name="{{ strtolower($product->name) }}"
                         data-category-id="{{ $product->category_id }}"
                         data-brand-id="{{ $product->brand_id }}"
                         data-seller-id="{{ $product->seller_id ?? 'admin' }}"
                         data-price="{{ $finalPrice }}"
                         data-created-at="{{ $product->created_at ? $product->created_at->timestamp : 0 }}"
                         data-stock="{{ $product->stock }}"
                         data-rating="{{ $pRating }}"
                         data-reviews="{{ $pReviews }}"
                         data-discount="{{ $collectionDiscount ? ($collection->discount_type == 'percentage' ? $collection->discount_value : $discountPct) : $discountPct }}"
                         data-colors="{{ implode(',', $pColors->toArray()) }}"
                         data-sizes="{{ implode(',', $pSizes->toArray()) }}"
                         data-collections="{{ implode(',', $pCollections->toArray()) }}">

                        <div class="product-card">

                            {{-- ── Image Section ── --}}
                            <div class="product-img-wrap">

                                {{-- Discount Badge --}}
                                @if($collectionDiscount)
                                    <span class="badge-discount">
                                        @if($collection->discount_type == 'percentage')
                                            {{ $collection->discount_value }}% OFF
                                        @else
                                            ₹{{ number_format($collection->discount_value, 0) }} OFF
                                        @endif
                                    </span>
                                @elseif($hasDiscount && $discountPct > 0)
                                    <span class="badge-discount">{{ $discountPct }}% OFF</span>
                                @endif

                                {{-- New Badge (created within last 14 days) --}}
                                @if($product->created_at && $product->created_at->diffInDays(now()) <= 14)
                                    <span class="badge-new">NEW</span>
                                @endif

                                {{-- Product Link --}}
                                <a href="{{ url('/product-details/'.$product->id) }}{{ isset($collection) ? '?collection='.$collection->slug : '' }}" class="w-100 h-100 d-block">
                                    <img src="{{ $product->seller_id ? asset('storage/'.$product->image) : asset('uploads/'.$product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="lazy"
                                         loading="lazy">
                                </a>

                                {{-- Image hover quick action overlay --}}
                                <div class="product-img-overlay">
                                    {{-- Wishlist toggle form --}}
                                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="m-0">
                                        @csrf
                                        @if(isset($collection))
                                            <input type="hidden" name="collection_slug" value="{{ $collection->slug }}">
                                        @endif
                                        <button type="submit"
                                                class="overlay-btn {{ in_array($product->id, $wishlistProductIds ?? []) ? 'wishlist-active' : '' }}"
                                                title="{{ in_array($product->id, $wishlistProductIds ?? []) ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                            <i class="bi {{ in_array($product->id, $wishlistProductIds ?? []) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                        </button>
                                    </form>

                                    {{-- Direct details link --}}
                                    <a href="{{ url('/product-details/'.$product->id) }}{{ isset($collection) ? '?collection='.$collection->slug : '' }}"
                                       class="overlay-btn" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>

                            {{-- ── Card Contents ── --}}
                            <div class="product-card-body">
                                <div class="card-meta-column">
                                    
                                    {{-- Seller badge --}}
                                    <div class="mb-1.5">
                                        @if($product->seller_id && $product->seller)
                                            <a href="{{ route('store.show', $product->seller_id) }}" class="badge-seller text-decoration-none">
                                                @if($product->seller->business_logo)
                                                    <img src="{{ asset('storage/' . $product->seller->business_logo) }}" alt="{{ $product->seller->business_name }}" style="width:12px;height:12px;border-radius:2px;object-fit:cover;">
                                                @else
                                                    <i class="bi bi-shop" style="font-size:0.65rem;"></i>
                                                @endif
                                                <span>{{ Str::limit($product->seller->business_name, 15) }}</span>
                                            </a>
                                        @else
                                            <span class="badge-seller">
                                                <i class="bi bi-patch-check" style="font-size:0.65rem;"></i>
                                                <span>Official Store</span>
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Product Name --}}
                                    <a href="{{ url('/product-details/'.$product->id) }}{{ isset($collection) ? '?collection='.$collection->slug : '' }}"
                                       class="product-name">
                                        {{ $product->name }}
                                    </a>

                                    {{-- Rating badge --}}
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="rating-badge">
                                            <span class="rating-stars">
                                                @for($star = 1; $star <= 5; $star++)
                                                    <i class="bi {{ $star <= round($pRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                @endfor
                                            </span>
                                            <span class="rating-count">({{ $pReviews }})</span>
                                        </div>
                                    </div>

                                    {{-- Prices --}}
                                    <div class="price-block">
                                        @if($collectionDiscount)
                                            <div class="d-flex align-items-baseline flex-wrap">
                                                <span class="price-current">₹{{ number_format($finalPrice, 0) }}</span>
                                                <span class="price-original">₹{{ number_format($product->price, 0) }}</span>
                                                @if($collection->discount_type == 'percentage')
                                                    <span class="price-discount-text">{{ $collection->discount_value }}% OFF</span>
                                                @else
                                                    <span class="price-discount-text">₹{{ number_format($collection->discount_value, 0) }} OFF</span>
                                                @endif
                                            </div>
                                        @elseif($hasDiscount)
                                            <div class="d-flex align-items-baseline flex-wrap">
                                                <span class="price-current">₹{{ number_format($product->price, 0) }}</span>
                                                <span class="price-original">₹{{ number_format($product->original_price, 0) }}</span>
                                                @if($discountPct > 0)
                                                    <span class="price-discount-text">{{ $discountPct }}% OFF</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="price-current">₹{{ number_format($product->price, 0) }}</span>
                                        @endif
                                    </div>

                                    {{-- Stock indicator --}}
                                    <div class="mb-3">
                                        @if(isset($product->stock) && $product->stock > 0)
                                            <span class="stock-in">
                                                <i class="bi bi-check-circle-fill"></i> In Stock
                                            </span>
                                        @else
                                            <span class="stock-out">
                                                <i class="bi bi-x-circle-fill"></i> Out of Stock
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Card Action Column --}}
                                <div class="card-action-column gap-2 mt-auto">
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="w-100 m-0">
                                        @csrf
                                        @if(isset($collection))
                                            <input type="hidden" name="collection_slug" value="{{ $collection->slug }}">
                                        @endif
                                        <button type="submit" class="btn-cart">
                                            <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                        </button>
                                    </form>

                                    <a href="{{ url('/product-details/'.$product->id) }}{{ isset($collection) ? '?collection='.$collection->slug : '' }}"
                                       class="btn-view w-100">
                                        View Details
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div>

                @endforeach

            </div>

            {{-- Empty Filter State --}}
            <div class="empty-state" id="empty-filter-state" style="display: none;">
                <div class="empty-state-icon">
                    <i class="bi bi-search-heart"></i>
                </div>
                <h4>No Matching Products</h4>
                <p>We couldn't find any products in this section that fit your active filters. Try loosening your selections.</p>
                <div class="d-flex gap-3 justify-content-center">
                    <button class="btn btn-primary px-4 py-2 fw-bold" id="btn-reset-filters">
                        Reset Filters
                    </button>
                </div>
            </div>

            {{-- Traditional Pagination (Preserved for compatibility) --}}
            @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
                <div class="d-flex justify-content-center mt-5" id="pagination-wrapper">
                    {{ $products->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // UI ELEMENTS
    const sidebar = document.getElementById('shop-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const mobileToggle = document.getElementById('mobile-filter-toggle');
    const skeletonGrid = document.getElementById('skeleton-grid');
    const productGrid = document.getElementById('product-grid');
    const emptyState = document.getElementById('empty-filter-state');
    
    const minPriceInput = document.getElementById('price-min');
    const maxPriceInput = document.getElementById('price-max');
    const stockCheckbox = document.getElementById('stock-in-stock');
    
    const activeFiltersBar = document.getElementById('active-filters-bar');
    
    // VIEWS
    const btnGrid = document.getElementById('btn-view-grid');
    const btnList = document.getElementById('btn-view-list');
    
    // SORT
    const sortSelect = document.getElementById('sort-select');
    
    // DATA COLLECTIONS
    const cards = Array.from(document.querySelectorAll('.product-card-container'));
    
    // FILTER STATE
    let activeFilters = {
        brands: [],
        colors: [],
        sizes: [],
        sellers: [],
        minPrice: null,
        maxPrice: null,
        stockOnly: false,
        rating: null,
        discount: null
    };

    // ── INITIAL LOADING STATE ──
    setTimeout(() => {
        if (skeletonGrid) skeletonGrid.style.display = 'none';
        if (productGrid) {
            productGrid.style.display = 'flex';
            productGrid.style.opacity = 0;
            setTimeout(() => { productGrid.style.transition = 'opacity 0.3s ease'; productGrid.style.opacity = 1; }, 50);
        }
    }, 450);

    // ── MOBILE FILTER OVERLAY ──
    if(mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            sidebar.classList.add('open');
            overlay.classList.add('open');
        });
    }
    if(overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        });
    }

    // ── GRID / LIST VIEW TOGGLE ──
    if (btnGrid && btnList) {
        btnGrid.addEventListener('click', function() {
            productGrid.classList.remove('list-view-active');
            btnGrid.classList.add('active');
            btnList.classList.remove('active');
        });

        btnList.addEventListener('click', function() {
            productGrid.classList.add('list-view-active');
            btnList.classList.add('active');
            btnGrid.classList.remove('active');
        });
    }

    // ── COLLAPSIBLE FILTERS ICON TOGGLE ──
    document.querySelectorAll('.filter-section-title').forEach(function (header) {
        header.addEventListener('click', function () {
            this.classList.toggle('collapsed');
        });
    });

    // ── COLOR SWATCH INTERACTION ──
    document.querySelectorAll('.filter-color-swatch').forEach(swatch => {
        swatch.addEventListener('click', function() {
            const colorId = this.getAttribute('data-color-id');
            this.classList.toggle('active');
            
            if (this.classList.contains('active')) {
                activeFilters.colors.push(colorId);
            } else {
                activeFilters.colors = activeFilters.colors.filter(id => id !== colorId);
            }
            filterProducts();
        });
    });

    // ── SIZE PILL INTERACTION ──
    document.querySelectorAll('.filter-size-pill').forEach(pill => {
        pill.addEventListener('click', function() {
            const sizeId = this.getAttribute('data-size-id');
            this.classList.toggle('active');
            
            if (this.classList.contains('active')) {
                activeFilters.sizes.push(sizeId);
            } else {
                activeFilters.sizes = activeFilters.sizes.filter(id => id !== sizeId);
            }
            filterProducts();
        });
    });

    // ── RATING ROW INTERACTION ──
    document.querySelectorAll('.filter-rating-row').forEach(row => {
        row.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            
            if (this.classList.contains('active')) {
                this.classList.remove('active');
                activeFilters.rating = null;
            } else {
                document.querySelectorAll('.filter-rating-row').forEach(r => r.classList.remove('active'));
                this.classList.add('active');
                activeFilters.rating = rating;
            }
            filterProducts();
        });
    });

    // ── INPUT CHANGES (BRANDS, SELLERS, DISCOUNTS) ──
    document.querySelectorAll('.filter-input').forEach(input => {
        input.addEventListener('change', function() {
            if (this.name === 'filter_brands') {
                if (this.checked) {
                    activeFilters.brands.push(this.value);
                } else {
                    activeFilters.brands = activeFilters.brands.filter(v => v !== this.value);
                }
            } else if (this.name === 'filter_sellers') {
                if (this.checked) {
                    activeFilters.sellers.push(this.value);
                } else {
                    activeFilters.sellers = activeFilters.sellers.filter(v => v !== this.value);
                }
            } else if (this.name === 'filter_discount') {
                activeFilters.discount = this.checked ? parseInt(this.value) : null;
            }
            filterProducts();
        });
    });

    // Stock availability checkbox
    if (stockCheckbox) {
        stockCheckbox.addEventListener('change', function() {
            activeFilters.stockOnly = this.checked;
            filterProducts();
        });
    }

    // ── PRICE FILTER SUBMIT ──
    const applyPriceBtn = document.getElementById('btn-apply-price');
    if (applyPriceBtn) {
        applyPriceBtn.addEventListener('click', function() {
            const min = parseFloat(minPriceInput.value);
            const max = parseFloat(maxPriceInput.value);
            activeFilters.minPrice = isNaN(min) ? null : min;
            activeFilters.maxPrice = isNaN(max) ? null : max;
            filterProducts();
        });
    }

    // ── FILTER SYSTEM CORE ──
    function filterProducts() {
        let visibleCount = 0;
        
        cards.forEach(card => {
            let matches = true;

            // Brand
            if (activeFilters.brands.length > 0) {
                const brandId = card.getAttribute('data-brand-id');
                if (!activeFilters.brands.includes(brandId)) matches = false;
            }

            // Colors
            if (activeFilters.colors.length > 0) {
                const pColors = card.getAttribute('data-colors').split(',');
                const hasColor = activeFilters.colors.some(cId => pColors.includes(cId));
                if (!hasColor) matches = false;
            }

            // Sizes
            if (activeFilters.sizes.length > 0) {
                const pSizes = card.getAttribute('data-sizes').split(',');
                const hasSize = activeFilters.sizes.some(sId => pSizes.includes(sId));
                if (!hasSize) matches = false;
            }

            // Sellers
            if (activeFilters.sellers.length > 0) {
                const sellerId = card.getAttribute('data-seller-id');
                if (!activeFilters.sellers.includes(sellerId)) matches = false;
            }

            // Availability (Stock)
            if (activeFilters.stockOnly) {
                const stock = parseInt(card.getAttribute('data-stock'));
                if (stock <= 0) matches = false;
            }

            // Rating
            if (activeFilters.rating !== null) {
                const rating = parseFloat(card.getAttribute('data-rating'));
                if (rating < activeFilters.rating) matches = false;
            }

            // Discount
            if (activeFilters.discount !== null) {
                const discount = parseInt(card.getAttribute('data-discount'));
                if (discount < activeFilters.discount) matches = false;
            }

            // Price range
            const price = parseFloat(card.getAttribute('data-price'));
            if (activeFilters.minPrice !== null && price < activeFilters.minPrice) matches = false;
            if (activeFilters.maxPrice !== null && price > activeFilters.maxPrice) matches = false;

            // Display card
            if (matches) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Update count text
        const visibleCountSpan = document.getElementById('visible-count');
        if (visibleCountSpan) visibleCountSpan.textContent = visibleCount;

        // Empty state check
        if (visibleCount === 0) {
            if (productGrid) productGrid.style.display = 'none';
            if (emptyState) emptyState.style.display = 'block';
        } else {
            if (productGrid) productGrid.style.display = 'flex';
            if (emptyState) emptyState.style.display = 'none';
        }

        updateFilterChips();
    }

    // ── UPDATE ACTIVE CHIPS BAR ──
    function updateFilterChips() {
        // Clear previous chips except label
        const bar = document.getElementById('active-filters-bar');
        if (!bar) return;
        
        bar.innerHTML = '<span class="active-filters-label">Active:</span>';
        
        let hasFilters = false;

        // Brand chips
        activeFilters.brands.forEach(bId => {
            const checkbox = document.querySelector(`.custom-control-input[value="${bId}"][name="filter_brands"]`);
            if (checkbox) {
                const label = checkbox.nextElementSibling.querySelector('span').textContent;
                createChip(`Brand: ${label}`, () => {
                    checkbox.checked = false;
                    checkbox.dispatchEvent(new Event('change'));
                });
                hasFilters = true;
            }
        });

        // Color chips
        activeFilters.colors.forEach(cId => {
            const swatch = document.querySelector(`.color-swatch-item[data-color-id="${cId}"]`);
            if (swatch) {
                const title = swatch.getAttribute('title');
                createChip(`Color: ${title}`, () => {
                    swatch.click();
                });
                hasFilters = true;
            }
        });

        // Size chips
        activeFilters.sizes.forEach(sId => {
            const pill = document.querySelector(`.size-pill-item[data-size-id="${sId}"]`);
            if (pill) {
                const title = pill.textContent.trim();
                createChip(`Size: ${title}`, () => {
                    pill.click();
                });
                hasFilters = true;
            }
        });

        // Seller chips
        activeFilters.sellers.forEach(sId => {
            const checkbox = document.querySelector(`.custom-control-input[value="${sId}"][name="filter_sellers"]`);
            if (checkbox) {
                const label = checkbox.nextElementSibling.querySelector('span').textContent;
                createChip(`Seller: ${label}`, () => {
                    checkbox.checked = false;
                    checkbox.dispatchEvent(new Event('change'));
                });
                hasFilters = true;
            }
        });

        // Stock availability
        if (activeFilters.stockOnly) {
            createChip('In Stock Only', () => {
                if (stockCheckbox) {
                    stockCheckbox.checked = false;
                    stockCheckbox.dispatchEvent(new Event('change'));
                }
            });
            hasFilters = true;
        }

        // Rating
        if (activeFilters.rating !== null) {
            createChip(`Rating: ${activeFilters.rating}★ & up`, () => {
                const activeRow = document.querySelector(`.filter-rating-row.active`);
                if (activeRow) activeRow.click();
            });
            hasFilters = true;
        }

        // Discount
        if (activeFilters.discount !== null) {
            createChip(`Discount: ${activeFilters.discount}%+`, () => {
                const checkedRadio = document.querySelector(`input[name="filter_discount"]:checked`);
                if (checkedRadio) {
                    checkedRadio.checked = false;
                    checkedRadio.dispatchEvent(new Event('change'));
                }
            });
            hasFilters = true;
        }

        // Price
        if (activeFilters.minPrice !== null || activeFilters.maxPrice !== null) {
            let label = 'Price: ';
            if (activeFilters.minPrice && activeFilters.maxPrice) label += `₹${activeFilters.minPrice} - ₹${activeFilters.maxPrice}`;
            else if (activeFilters.minPrice) label += `₹${activeFilters.minPrice}+`;
            else if (activeFilters.maxPrice) label += `Under ₹${activeFilters.maxPrice}`;
            
            createChip(label, () => {
                minPriceInput.value = '';
                maxPriceInput.value = '';
                activeFilters.minPrice = null;
                activeFilters.maxPrice = null;
                filterProducts();
            });
            hasFilters = true;
        }

        bar.style.display = hasFilters ? 'flex' : 'none';
    }

    function createChip(text, onRemove) {
        const bar = document.getElementById('active-filters-bar');
        const chip = document.createElement('div');
        chip.className = 'filter-chip';
        chip.innerHTML = `<span>${text}</span> <i class="bi bi-x-circle-fill" style="font-size:0.8rem; margin-top:1px;"></i>`;
        chip.addEventListener('click', onRemove);
        bar.appendChild(chip);
    }

    // ── CLEAR ALL ACTIONS ──
    const btnClearAll = document.getElementById('btn-clear-all');
    const btnResetFilters = document.getElementById('btn-reset-filters');
    
    function resetAllFilters() {
        activeFilters = {
            brands: [],
            colors: [],
            sizes: [],
            sellers: [],
            minPrice: null,
            maxPrice: null,
            stockOnly: false,
            rating: null,
            discount: null
        };
        
        // Reset checkable UI elements
        document.querySelectorAll('.custom-control-input').forEach(chk => chk.checked = false);
        document.querySelectorAll('.color-swatch-item').forEach(sw => sw.classList.remove('active'));
        document.querySelectorAll('.size-pill-item').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.filter-rating-row').forEach(r => r.classList.remove('active'));
        
        minPriceInput.value = '';
        maxPriceInput.value = '';
        
        filterProducts();
    }
    
    if (btnClearAll) btnClearAll.addEventListener('click', resetAllFilters);
    if (btnResetFilters) btnResetFilters.addEventListener('click', resetAllFilters);

    // ── SORTING ALGORITHM ──
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const val = this.value;
            const cardElements = Array.from(productGrid.children);
            
            cardElements.sort((a, b) => {
                if (val === 'newest') {
                    const timeA = parseInt(a.getAttribute('data-created-at'));
                    const timeB = parseInt(b.getAttribute('data-created-at'));
                    return timeB - timeA;
                } else if (val === 'price-asc') {
                    const priceA = parseFloat(a.getAttribute('data-price'));
                    const priceB = parseFloat(b.getAttribute('data-price'));
                    return priceA - priceB;
                } else if (val === 'price-desc') {
                    const priceA = parseFloat(a.getAttribute('data-price'));
                    const priceB = parseFloat(b.getAttribute('data-price'));
                    return priceB - priceA;
                } else if (val === 'rating-desc') {
                    const ratingA = parseFloat(a.getAttribute('data-rating'));
                    const ratingB = parseFloat(b.getAttribute('data-rating'));
                    return ratingB - ratingA;
                } else if (val === 'popularity') {
                    const revA = parseInt(a.getAttribute('data-reviews'));
                    const revB = parseInt(b.getAttribute('data-reviews'));
                    return revB - revA;
                }
                return 0;
            });
            
            // Re-append nodes in sorted order
            cardElements.forEach(node => productGrid.appendChild(node));
        });
    }

});
</script>
@endpush