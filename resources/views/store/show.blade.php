@extends('layouts.shop')

@section('content')

{{-- ════════════════════════════════════════════════
     STORE PAGE STYLES
════════════════════════════════════════════════ --}}
<style>
/* ── Store Hero Banner ──────────────────────────── */
.store-hero {
    background: linear-gradient(135deg, #0f3460 0%, #16213e 50%, #1a1a2e 100%);
    padding: 48px 0 36px;
    position: relative;
    overflow: hidden;
}
.store-hero::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(40,116,240,0.15) 0%, transparent 70%);
    border-radius: 50%;
}
.store-hero::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: 5%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(40,116,240,0.08) 0%, transparent 70%);
    border-radius: 50%;
}

/* Store logo wrapper with ring */
.store-logo-ring {
    width: 100px;
    height: 100px;
    border-radius: 20px;
    padding: 4px;
    background: linear-gradient(135deg, #2874f0, #1de9b6);
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}
.store-logo-inner {
    width: 100%;
    height: 100%;
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
}
.store-logo-inner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.store-logo-placeholder-large {
    width: 100px;
    height: 100px;
    border-radius: 20px;
    background: linear-gradient(135deg, #2874f0, #0f4fc8);
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    color: #fff;
    position: relative;
    z-index: 1;
}

/* Store info text */
.store-name {
    font-size: 1.9rem;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.15;
    letter-spacing: -0.3px;
}
.store-verified-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: rgba(29,233,182,0.15);
    border: 1px solid rgba(29,233,182,0.35);
    color: #1de9b6;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 2px 10px;
    border-radius: 20px;
    letter-spacing: 0.4px;
}
.store-stat-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    color: #d0dced;
    font-size: 0.8rem;
    padding: 5px 14px;
    border-radius: 20px;
}
.store-stat-pill strong {
    color: #ffffff;
}

/* ── Product Grid ───────────────────────────────── */
.store-products-section {
    background: #f5f6fa;
    min-height: 60vh;
    padding: 32px 0;
}
.store-product-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    transition: box-shadow 0.25s ease, transform 0.25s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.store-product-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    transform: translateY(-3px);
}
.store-product-img-wrap {
    position: relative;
    overflow: hidden;
    background: #f8f9fa;
}
.store-product-img-wrap img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.35s ease;
    display: block;
}
.store-product-card:hover .store-product-img-wrap img {
    transform: scale(1.04);
}
.store-product-body {
    padding: 12px 14px 14px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.store-product-name {
    font-size: 0.88rem;
    font-weight: 600;
    color: #1a1a2e;
    line-height: 1.35;
    margin-bottom: 6px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.store-product-price {
    font-size: 1.05rem;
    font-weight: 700;
    color: #2874f0;
}
.store-product-original {
    font-size: 0.78rem;
    color: #9e9e9e;
    text-decoration: line-through;
}
.store-product-discount {
    font-size: 0.72rem;
    color: #388e3c;
    font-weight: 600;
}
.store-product-actions {
    display: flex;
    gap: 8px;
    margin-top: auto;
    padding-top: 10px;
}
.store-product-actions .btn-cart {
    flex: 1;
    background: #2874f0;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 7px 10px;
    transition: background 0.2s;
}
.store-product-actions .btn-cart:hover {
    background: #1558c0;
}
.store-product-actions .btn-wish {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    border: 1px solid #e0e0e0;
    background: #fff;
    color: #e53935;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s, border-color 0.2s;
    cursor: pointer;
    flex-shrink: 0;
    padding: 0;
}
.store-product-actions .btn-wish:hover {
    background: #fde8e8;
    border-color: #e53935;
}

/* ── No Products Empty State ────────────────────── */
.store-empty {
    text-align: center;
    padding: 80px 20px;
    color: #9e9e9e;
}
.store-empty-icon {
    font-size: 60px;
    margin-bottom: 16px;
    color: #dde2ef;
}

/* Breadcrumb */
.store-breadcrumb {
    background: rgba(255,255,255,0.07);
    padding: 8px 0;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}
.store-breadcrumb a {
    color: rgba(255,255,255,0.55);
    text-decoration: none;
    font-size: 0.78rem;
}
.store-breadcrumb a:hover { color: #fff; }
.store-breadcrumb span {
    color: rgba(255,255,255,0.8);
    font-size: 0.78rem;
}
.store-breadcrumb .sep {
    color: rgba(255,255,255,0.3);
    margin: 0 6px;
}
</style>

{{-- ════════════════════════════════════════════════
     STORE HERO BANNER
════════════════════════════════════════════════ --}}
<div class="store-hero">

    {{-- Mini Breadcrumb --}}
    <div class="store-breadcrumb mb-4">
        <div class="container">
            <a href="/shop">Shop</a>
            <span class="sep">/</span>
            <span>{{ $seller->business_name }}</span>
        </div>
    </div>

    <div class="container">
        <div class="d-flex align-items-center gap-4 flex-wrap">

            {{-- Business Logo --}}
            @if($seller->business_logo)
                <div class="store-logo-ring">
                    <div class="store-logo-inner">
                        <img src="{{ asset('storage/' . $seller->business_logo) }}"
                             alt="{{ $seller->business_name }}">
                    </div>
                </div>
            @else
                <div class="store-logo-placeholder-large">
                    <i class="bi bi-shop"></i>
                </div>
            @endif

            {{-- Store Info --}}
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                    <h1 class="store-name mb-0">{{ $seller->business_name }}</h1>
                    <span class="store-verified-badge">
                        <i class="bi bi-patch-check-fill"></i> Verified Seller
                    </span>
                </div>

                @if($seller->business_address)
                    <div style="color:rgba(255,255,255,0.5);font-size:0.8rem;margin-bottom:12px;">
                        <i class="bi bi-geo-alt me-1"></i>{{ $seller->business_address }}
                    </div>
                @endif

                <div class="d-flex gap-2 flex-wrap">
                    <span class="store-stat-pill">
                        <i class="bi bi-box-seam"></i>
                        <strong>{{ $totalProducts }}</strong> Products
                    </span>
                    <span class="store-stat-pill">
                        <i class="bi bi-star-fill" style="color:#f5c518;"></i>
                        Marketplace Seller
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════
     PRODUCTS GRID
════════════════════════════════════════════════ --}}
<div class="store-products-section">
    <div class="container">

        {{-- Section Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h5 class="mb-0 fw-bold" style="color:#1a1a2e;">
                    All Products from {{ $seller->business_name }}
                </h5>
                <small class="text-muted">{{ $totalProducts }} approved products</small>
            </div>
        </div>

        @if($products->count())

            <div class="row g-3">
                @foreach($products as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="store-product-card">

                            {{-- Product Image --}}
                            <a href="{{ url('/product-details/' . $product->id) }}"
                               class="store-product-img-wrap d-block">
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">

                                {{-- Discount Badge --}}
                                @if($product->discount_percentage > 0)
                                    <span class="badge bg-danger position-absolute"
                                          style="top:10px;left:10px;font-size:0.7rem;">
                                        {{ $product->discount_percentage }}% OFF
                                    </span>
                                @endif
                            </a>

                            {{-- Product Info --}}
                            <div class="store-product-body">
                                <a href="{{ url('/product-details/' . $product->id) }}"
                                   class="text-decoration-none">
                                    <div class="store-product-name">{{ $product->name }}</div>
                                </a>

                                {{-- Pricing --}}
                                <div class="d-flex align-items-baseline gap-2 mb-1">
                                    <span class="store-product-price">₹{{ number_format($product->price, 2) }}</span>
                                    @if($product->original_price && $product->original_price > $product->price)
                                        <span class="store-product-original">₹{{ number_format($product->original_price, 2) }}</span>
                                        <span class="store-product-discount">{{ $product->discount_percentage }}% off</span>
                                    @endif
                                </div>

                                {{-- Category --}}
                                @if($product->category)
                                    <div style="font-size:0.72rem;color:#9e9e9e;margin-bottom:8px;">
                                        <i class="bi bi-tag me-1"></i>{{ $product->category->name }}
                                    </div>
                                @endif

                                {{-- Stock status --}}
                                @if($product->stock > 0)
                                    <div style="font-size:0.72rem;color:#388e3c;margin-bottom:6px;">
                                        <i class="bi bi-check-circle me-1"></i>In Stock
                                    </div>
                                @else
                                    <div style="font-size:0.72rem;color:#e53935;margin-bottom:6px;">
                                        <i class="bi bi-x-circle me-1"></i>Out of Stock
                                    </div>
                                @endif

                                {{-- Action Buttons --}}
                                <div class="store-product-actions">
                                    @if(session()->has('customer_id') && $product->stock > 0)
                                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            <button type="submit" class="btn-cart w-100">
                                                🛒 Add to Cart
                                            </button>
                                        </form>

                                        <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-wish" title="Wishlist">
                                                <i class="bi bi-heart"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ url('/product-details/' . $product->id) }}"
                                           class="btn-cart text-center text-decoration-none d-block w-100"
                                           style="line-height:2;">View Details</a>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($products->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif

        @else
            <div class="store-empty">
                <div class="store-empty-icon"><i class="bi bi-box-seam"></i></div>
                <h5 style="color:#5a6475;font-weight:600;">No Products Available</h5>
                <p style="font-size:0.9rem;">This store hasn't listed any approved products yet.</p>
                <a href="/shop" class="btn btn-primary mt-2">
                    <i class="bi bi-arrow-left me-1"></i> Back to Shop
                </a>
            </div>
        @endif

    </div>
</div>

@endsection
