@extends('layouts.shop')

@section('content')

<style>
/* ── Product Details Premium UI ────────────────────────── */
.pd-page { background: #f8fafc; min-height: 100vh; }

/* Breadcrumb */
.pd-breadcrumb { padding: 16px 0 0; }
.pd-breadcrumb .breadcrumb { background: none; margin: 0; padding: 0; font-size: 0.8rem; gap: 4px; }
.pd-breadcrumb .breadcrumb-item a { color: #64748b; text-decoration: none; transition: color .2s; }
.pd-breadcrumb .breadcrumb-item a:hover { color: #2874f0; }
.pd-breadcrumb .breadcrumb-item.active { color: #1e293b; font-weight: 500; }
.pd-breadcrumb .breadcrumb-item + .breadcrumb-item::before { color: #94a3b8; }

/* Alert */
.pd-alert { border-radius: 12px; border: none; font-size: .875rem; padding: 14px 18px; background: #f0fdf4; color: #166534; border-left: 4px solid #22c55e; display: flex; align-items: center; gap: 10px; }

/* Main card */
.pd-card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 4px 24px rgba(0,0,0,.06), 0 1px 4px rgba(0,0,0,.04);
  overflow: hidden;
  margin: 20px 0 32px;
}

/* Image panel */
.pd-img-panel { position: relative; background: #f8fafc; border-right: 1px solid #f1f5f9; padding: 32px; display: flex; flex-direction: column; align-items: center; gap: 16px; }
.pd-img-main {
  width: 100%; aspect-ratio: 1;
  max-height: 440px;
  object-fit: cover;
  border-radius: 14px;
  transition: transform .4s ease;
  cursor: zoom-in;
}
.pd-img-main:hover { transform: scale(1.03); }
.pd-img-badge {
  position: absolute; top: 20px; left: 20px;
  background: linear-gradient(135deg, #ef4444, #dc2626);
  color: #fff; font-size: .7rem; font-weight: 700;
  padding: 4px 10px; border-radius: 20px;
  letter-spacing: .5px;
}
.pd-share-row { display: flex; gap: 8px; margin-top: 8px; }
.pd-share-btn {
  width: 36px; height: 36px; border-radius: 50%;
  border: 1.5px solid #e2e8f0;
  background: #fff; color: #64748b;
  display: flex; align-items: center; justify-content: center;
  font-size: .85rem; cursor: pointer;
  transition: all .2s; text-decoration: none;
}
.pd-share-btn:hover { border-color: #2874f0; color: #2874f0; background: #eff6ff; }

/* Info panel */
.pd-info-panel { padding: 36px 36px 32px; display: flex; flex-direction: column; }

/* Seller strip */
.pd-seller-strip {
  display: flex; align-items: center; gap: 12px;
  background: #f8fafc; border: 1px solid #e2e8f0;
  border-radius: 12px; padding: 10px 14px;
  margin-bottom: 20px;
}
.pd-seller-strip .sold-by { font-size: .72rem; color: #94a3b8; font-weight: 500; text-transform: uppercase; letter-spacing: .5px; white-space: nowrap; }
.pd-seller-strip img, .pd-seller-strip .seller-icon { width: 34px; height: 34px; border-radius: 8px; object-fit: cover; border: 1.5px solid #e2e8f0; }
.pd-seller-strip .seller-icon { background: linear-gradient(135deg,#2874f0,#0f4fc8); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 14px; flex-shrink: 0; }
.pd-seller-name { font-weight: 600; color: #1e293b; font-size: .9rem; text-decoration: none; }
.pd-seller-name:hover { color: #2874f0; }
.pd-visit-btn { margin-left: auto; background: #eff6ff; color: #2874f0; border: 1px solid #bfdbfe; border-radius: 8px; padding: 5px 12px; font-size: .75rem; font-weight: 600; text-decoration: none; white-space: nowrap; transition: all .2s; }
.pd-visit-btn:hover { background: #2874f0; color: #fff; }

/* Title */
.pd-title { font-size: 1.45rem; font-weight: 700; color: #0f172a; line-height: 1.3; margin-bottom: 14px; }

/* Collections badge row */
.pd-collections-row { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 16px; }
.pd-collection-badge { background: #eff6ff; color: #2874f0; border: 1px solid #bfdbfe; border-radius: 20px; font-size: .72rem; font-weight: 600; padding: 3px 12px; }

/* Price block */
.pd-price-block { display: flex; align-items: baseline; gap: 12px; margin-bottom: 10px; flex-wrap: wrap; }
.pd-price-current { font-size: 2rem; font-weight: 800; color: #0f172a; letter-spacing: -.04em; }
.pd-price-original { font-size: 1.05rem; color: #94a3b8; text-decoration: line-through; font-weight: 400; }
.pd-price-off { background: linear-gradient(135deg, #f97316, #ef4444); color: #fff; font-size: .72rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; letter-spacing: .3px; }

/* Stock */
.pd-stock-in { display: inline-flex; align-items: center; gap: 6px; background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; border-radius: 20px; font-size: .8rem; font-weight: 600; padding: 4px 12px; margin-bottom: 20px; }
.pd-stock-out { display: inline-flex; align-items: center: gap: 6px; background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 20px; font-size: .8rem; font-weight: 600; padding: 4px 12px; margin-bottom: 20px; }

/* Divider */
.pd-divider { height: 1px; background: #f1f5f9; margin: 18px 0; }

/* Variant selectors */
.pd-select-label { font-size: .78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .6px; margin-bottom: 10px; }
.pd-color-btn {
  border: 2px solid #e2e8f0; border-radius: 8px;
  background: #f8fafc; color: #334155;
  font-size: .82rem; font-weight: 500;
  padding: 7px 16px; cursor: pointer;
  transition: all .2s; outline: none;
}
.pd-color-btn:hover { border-color: #2874f0; color: #2874f0; background: #eff6ff; }
.pd-color-btn.active { border-color: #2874f0; background: #2874f0; color: #fff; box-shadow: 0 4px 12px rgba(40,116,240,.3); }

.pd-size-btn {
  border: 1.5px solid #e2e8f0; border-radius: 8px;
  background: #f8fafc; color: #334155;
  font-size: .82rem; font-weight: 600;
  padding: 7px 14px; cursor: pointer;
  transition: all .2s; outline: none; min-width: 44px;
}
.pd-size-btn:hover { border-color: #2874f0; color: #2874f0; background: #eff6ff; }
.pd-size-btn.active { border-color: #2874f0; background: #eff6ff; color: #2874f0; }

/* Quantity */
.pd-qty-wrap { display: flex; align-items: center; gap: 0; border: 1.5px solid #e2e8f0; border-radius: 10px; overflow: hidden; width: fit-content; }
.pd-qty-btn { width: 38px; height: 44px; background: #f8fafc; border: none; color: #475569; font-size: 1.1rem; cursor: pointer; transition: background .2s; display: flex; align-items: center; justify-content: center; }
.pd-qty-btn:hover { background: #eff6ff; color: #2874f0; }
.pd-qty-input { width: 60px; height: 44px; border: none; border-left: 1.5px solid #e2e8f0; border-right: 1.5px solid #e2e8f0; text-align: center; font-size: .95rem; font-weight: 600; color: #1e293b; outline: none; }

/* CTA Buttons */
.pd-cta-row { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 20px; }
.pd-btn-cart {
  flex: 1; min-width: 160px;
  background: linear-gradient(135deg, #ff9f00, #f97316);
  color: #fff; border: none; border-radius: 12px;
  font-size: .92rem; font-weight: 700;
  padding: 14px 24px; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  transition: all .25s; box-shadow: 0 4px 16px rgba(249,115,22,.3);
}
.pd-btn-cart:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(249,115,22,.4); }
.pd-btn-cart:disabled { opacity: .5; cursor: not-allowed; transform: none; }

.pd-btn-buy {
  flex: 1; min-width: 160px;
  background: linear-gradient(135deg, #2874f0, #1254c4);
  color: #fff; border: none; border-radius: 12px;
  font-size: .92rem; font-weight: 700;
  padding: 14px 24px; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  transition: all .25s; box-shadow: 0 4px 16px rgba(40,116,240,.3);
}
.pd-btn-buy:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(40,116,240,.4); }

.pd-btn-wishlist {
  width: 52px; height: 52px;
  border: 1.5px solid #e2e8f0; border-radius: 12px;
  background: #fff; color: #ef4444;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.2rem; cursor: pointer; transition: all .2s; flex-shrink: 0;
}
.pd-btn-wishlist:hover { background: #fef2f2; border-color: #fecaca; }
.pd-btn-wishlist:disabled { opacity: .5; cursor: not-allowed; }

/* Delivery info strip */
.pd-delivery-strip {
  display: flex; gap: 0;
  background: #f8fafc; border: 1px solid #e2e8f0;
  border-radius: 14px; overflow: hidden;
  margin-top: 24px;
}
.pd-delivery-item {
  flex: 1; display: flex; flex-direction: column;
  align-items: center; gap: 6px; padding: 16px 10px;
  font-size: .78rem; color: #475569; text-align: center; font-weight: 500;
}
.pd-delivery-item + .pd-delivery-item { border-left: 1px solid #e2e8f0; }
.pd-delivery-icon { font-size: 1.3rem; color: #2874f0; }

/* Description */
.pd-desc-card {
  background: #fff; border-radius: 16px;
  border: 1px solid #f1f5f9;
  padding: 28px 32px; margin-bottom: 32px;
  box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.pd-desc-card h5 { font-size: .95rem; font-weight: 700; color: #1e293b; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
.pd-desc-card p { font-size: .9rem; color: #475569; line-height: 1.8; margin: 0; }

/* Related Products */
.pd-related-title { font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.pd-related-title::after { content: ''; flex: 1; height: 2px; background: linear-gradient(90deg, #e2e8f0, transparent); border-radius: 2px; }

.pd-related-card {
  background: #fff; border-radius: 14px;
  border: 1px solid #f1f5f9;
  overflow: hidden; transition: all .25s;
  box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.pd-related-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(40,116,240,.1); border-color: #bfdbfe; }
.pd-related-img { width: 100%; height: 200px; object-fit: cover; transition: transform .4s; }
.pd-related-card:hover .pd-related-img { transform: scale(1.04); }
.pd-related-body { padding: 14px 16px; }
.pd-related-name { font-size: .85rem; font-weight: 600; color: #1e293b; margin-bottom: 6px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.pd-related-price { font-size: 1rem; font-weight: 700; color: #2874f0; margin-bottom: 10px; }
.pd-related-btn { display: block; text-align: center; background: #eff6ff; color: #2874f0; border: 1px solid #bfdbfe; border-radius: 8px; padding: 8px; font-size: .8rem; font-weight: 600; text-decoration: none; transition: all .2s; }
.pd-related-btn:hover { background: #2874f0; color: #fff; }

/* Responsive */
@media (max-width: 768px) {
  .pd-info-panel { padding: 24px 20px; }
  .pd-img-panel { padding: 20px; }
  .pd-price-current { font-size: 1.6rem; }
  .pd-delivery-strip { flex-direction: column; }
  .pd-delivery-item + .pd-delivery-item { border-left: none; border-top: 1px solid #e2e8f0; }
  .pd-desc-card { padding: 20px; }
}
</style>

@if(session('success'))
<div class="container mt-3">
  <div class="pd-alert">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
  </div>
</div>
@endif

<div class="pd-page">
<div class="container">

  {{-- Breadcrumb --}}
  @php
    $breadcrumbs = [];
    $category = $product->category;
    while($category) {
        array_unshift($breadcrumbs, $category);
        $category = $category->parent;
    }
  @endphp
  <nav aria-label="breadcrumb" class="pd-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/shop"><i class="bi bi-house me-1"></i>Home</a></li>
      @foreach($breadcrumbs as $crumb)
        <li class="breadcrumb-item"><a href="/category/{{ $crumb->id }}">{{ $crumb->name }}</a></li>
      @endforeach
      <li class="breadcrumb-item active">{{ Str::limit($product->name, 40) }}</li>
    </ol>
  </nav>

  {{-- Main Product Card --}}
  <div class="pd-card">
    <div class="row g-0">

      {{-- Image Panel --}}
      <div class="col-lg-5 pd-img-panel">
        @php
          $discountPct = 0;
          if($product->original_price && $product->original_price > $product->price) {
              $discountPct = round((($product->original_price - $product->price) / $product->original_price) * 100);
          }
        @endphp
        @if($discountPct > 0)
          <div class="pd-img-badge">{{ $discountPct }}% OFF</div>
        @endif

        <img
          id="product-detail-image"
          src="{{ $product->seller_id ? asset('storage/' . $product->image) : asset('uploads/' . $product->image) }}"
          class="pd-img-main"
          alt="{{ $product->name }}">

        <div class="pd-share-row">
          <span style="font-size:.75rem;color:#94a3b8;align-self:center;">Share:</span>
          <a href="#" class="pd-share-btn" title="Share on WhatsApp"><i class="bi bi-whatsapp"></i></a>
          <a href="#" class="pd-share-btn" title="Share link"><i class="bi bi-link-45deg"></i></a>
          <a href="#" class="pd-share-btn" title="More"><i class="bi bi-share"></i></a>
        </div>
      </div>

      {{-- Info Panel --}}
      <div class="col-lg-7 pd-info-panel">

        {{-- Seller Strip --}}
        @if($product->seller_id && $product->seller)
        <div class="pd-seller-strip">
          <span class="sold-by">Sold by</span>
          @if($product->seller->business_logo)
            <img src="{{ asset('storage/' . $product->seller->business_logo) }}" alt="{{ $product->seller->business_name }}">
          @else
            <span class="seller-icon"><i class="bi bi-shop"></i></span>
          @endif
          <a href="{{ route('store.show', $product->seller_id) }}" class="pd-seller-name" title="Visit Store">{{ $product->seller->business_name }}</a>
          <a href="{{ route('store.show', $product->seller_id) }}" class="pd-visit-btn"><i class="bi bi-shop me-1"></i>Visit Store</a>
        </div>
        @endif

        {{-- Title --}}
        <h1 class="pd-title">{{ $product->name }}</h1>

        {{-- Collections --}}
        @if($product->collections->count())
          <div class="pd-collections-row">
            @foreach($product->collections as $productCollection)
              <span class="pd-collection-badge"><i class="bi bi-stars me-1"></i>{{ $productCollection->name }}</span>
            @endforeach
          </div>
        @endif

        {{-- Price Block --}}
        @php
          $finalPrice = $product->price;
          if(isset($collection) && $collection) {
              if($collection->discount_type == 'percentage') {
                  $finalPrice = $product->price - (($product->price * $collection->discount_value) / 100);
              } elseif($collection->discount_type == 'fixed') {
                  $finalPrice = max(0, $product->price - $collection->discount_value);
              }
          }
          $variants = $product->variants()->where('status', 1)->with('color', 'sizes')->orderBy('priority', 'asc')->get();
          $colors = $variants->map(function ($variant) {
              return [
                  'variant_id'     => $variant->id,
                  'color_id'       => $variant->color->id,
                  'name'           => $variant->color->name,
                  'image'          => $variant->image,
                  'price'          => $variant->price,
                  'original_price' => $variant->original_price,
                  'discount'       => $variant->discount_percentage,
                  'stock'          => $variant->stock,
                  'sizes' => $variant->sizes->map(function ($size) use ($variant) {
                      $sPrice = (float) ($size->pivot->price ?: $variant->price);
                      $sOrig  = (float) ($size->pivot->original_price ?: $variant->original_price);
                      $sDisc  = 0;
                      if ($sOrig > 0 && $sPrice > 0 && $sPrice < $sOrig) {
                          $sDisc = round((($sOrig - $sPrice) / $sOrig) * 100);
                      }
                      return [
                          'id'             => $size->id,
                          'name'           => $size->name,
                          'stock'          => $size->pivot->stock,
                          'price'          => $sPrice,
                          'original_price' => $sOrig,
                          'discount'       => $sDisc,
                      ];
                  })->toArray(),
              ];
          });
          $defaultVarObj = $variants->first();
          $displayPrice  = $defaultVarObj ? $defaultVarObj->price : $product->effective_price;
          $displayOrig   = $defaultVarObj ? $defaultVarObj->original_price : $product->effective_original_price;
          $displayDisc   = $defaultVarObj ? $defaultVarObj->discount_percentage : $product->discount_percentage;
        @endphp

        <div class="pd-price-block">
          @if(isset($collection) && $collection && $collection->discount_value)
            <span class="pd-price-current" id="product-price">₹{{ number_format($finalPrice, 2) }}</span>
            <span class="pd-price-original" id="product-original-price">₹{{ number_format($displayPrice, 2) }}</span>
            <span class="pd-price-off" id="product-discount-badge">
              @if($collection->discount_type == 'percentage')
                {{ $collection->discount_value }}% OFF
              @else
                ₹{{ $collection->discount_value }} OFF
              @endif
            </span>
          @else
            <span class="pd-price-current" id="product-price">₹{{ number_format($displayPrice, 2) }}</span>
            <span class="pd-price-original" id="product-original-price" style="{{ $displayDisc > 0 ? '' : 'display:none;' }}">₹{{ number_format($displayOrig, 2) }}</span>
            <span class="pd-price-off" id="product-discount-badge" style="{{ $displayDisc > 0 ? '' : 'display:none;' }}">{{ $displayDisc }}% OFF</span>
          @endif
        </div>

        {{-- Stock Status --}}
        @if($product->stock > 0)
          <span class="pd-stock-in"><i class="bi bi-check-circle-fill"></i> In Stock</span>
        @else
          <span class="pd-stock-out"><i class="bi bi-x-circle-fill"></i> Out of Stock</span>
        @endif

        <div class="pd-divider"></div>

        {{-- Color Variants --}}
        @if($colors->count())
          <div class="mb-3">
            <div class="pd-select-label">Select Color</div>
            <div class="d-flex gap-2 flex-wrap" id="product-colors">
              @foreach($colors as $index => $color)
                <button type="button"
                  class="pd-color-btn product-color-button {{ $index === 0 ? 'active' : '' }}"
                  data-variant-id="{{ $color['variant_id'] }}"
                  data-color-index="{{ $index }}"
                  data-color-image="{{ $color['image'] }}"
                  data-color-price="{{ $color['price'] }}"
                  data-color-original-price="{{ $color['original_price'] }}"
                  data-color-discount="{{ $color['discount'] }}"
                  data-color-stock="{{ $color['stock'] }}"
                  data-color-sizes='@json($color['sizes'])'>
                  {{ $color['name'] }}
                </button>
              @endforeach
            </div>
          </div>

          <div class="mb-3" id="product-sizes-container">
            <div class="pd-select-label">Select Size</div>
            <div id="product-sizes" class="d-flex gap-2 flex-wrap"></div>
            <div id="size-required-message" class="text-danger small mt-2 d-none">
              <i class="bi bi-exclamation-circle me-1"></i>Please select a size to continue.
            </div>
          </div>

          <div class="mb-3">
            <span class="pd-select-label">Stock: </span>
            <span id="product-variant-stock" style="font-weight:700;color:#1e293b;"></span>
          </div>

          <div class="pd-divider"></div>
        @endif

        {{-- Quantity + CTA --}}
        <form method="POST" action="{{ route('cart.add', $product) }}" id="add-to-cart-form">
          @csrf
          @if(isset($collection) && $collection)
            <input type="hidden" name="collection_slug" value="{{ $collection->slug }}">
          @endif
          <input type="hidden" name="product_variant_id" id="selected-product-variant-id" value="">
          <input type="hidden" name="size_id" id="selected-size-id" value="">

          <div class="mb-4">
            <div class="pd-select-label">Quantity</div>
            <div class="pd-qty-wrap">
              <button type="button" class="pd-qty-btn" id="qty-minus"><i class="bi bi-dash"></i></button>
              <input type="number" id="product-quantity" name="quantity" value="1" min="1" class="pd-qty-input">
              <button type="button" class="pd-qty-btn" id="qty-plus"><i class="bi bi-plus"></i></button>
            </div>
          </div>

          <div class="pd-cta-row">
            <button type="submit" id="add-to-cart-button" class="pd-btn-cart" {{ $colors->count() ? 'disabled' : '' }}>
              <i class="bi bi-cart-plus-fill"></i> Add to Cart
            </button>
            <button type="submit" id="add-to-wishlist-button"
              formaction="{{ route('wishlist.toggle', $product) }}"
              class="pd-btn-wishlist" {{ $colors->count() ? 'disabled' : '' }} title="Add to Wishlist">
              <i class="bi bi-heart-fill"></i>
            </button>
          </div>
        </form>

        <button type="button" class="pd-btn-buy mt-3" style="width:100%;border:none;">
          <i class="bi bi-lightning-fill"></i> Buy Now
        </button>

        {{-- Delivery Strip --}}
        <div class="pd-delivery-strip">
          <div class="pd-delivery-item">
            <i class="bi bi-truck pd-delivery-icon"></i>
            <div>Free Delivery</div>
            <div style="color:#94a3b8;font-size:.7rem;">3–5 Business Days</div>
          </div>
          <div class="pd-delivery-item">
            <i class="bi bi-arrow-counterclockwise pd-delivery-icon"></i>
            <div>7 Day Returns</div>
            <div style="color:#94a3b8;font-size:.7rem;">Easy & Hassle-free</div>
          </div>
          <div class="pd-delivery-item">
            <i class="bi bi-credit-card pd-delivery-icon"></i>
            <div>Cash on Delivery</div>
            <div style="color:#94a3b8;font-size:.7rem;">Available on all orders</div>
          </div>
        </div>

      </div>{{-- /info-panel --}}
    </div>{{-- /row --}}
  </div>{{-- /pd-card --}}

  {{-- Description --}}
  @if($product->description)
  <div class="pd-desc-card">
    <h5><i class="bi bi-file-text-fill text-primary"></i> Product Description</h5>
    <p>{{ $product->description }}</p>
  </div>
  @endif

  {{-- Related Products --}}
  @if($relatedProducts->count())
  <div class="mb-5">
    <h2 class="pd-related-title"><i class="bi bi-grid-3x3-gap-fill text-primary" style="font-size:1rem;"></i> Related Products</h2>
    <div class="row g-3">
      @foreach($relatedProducts as $item)
        <div class="col-xl-3 col-lg-4 col-md-4 col-6">
          <div class="pd-related-card">
            <div style="overflow:hidden;">
              <img src="{{ asset('uploads/'.$item->image) }}" class="pd-related-img" alt="{{ $item->name }}">
            </div>
            <div class="pd-related-body">
              <div class="pd-related-name">{{ $item->name }}</div>
              <div class="pd-related-price">₹{{ number_format($item->price, 2) }}</div>
              <a href="/product-details/{{ $item->id }}" class="pd-related-btn">View Details →</a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
  @endif

</div>{{-- /container --}}
</div>{{-- /pd-page --}}

{{-- JS: Quantity Controls --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  const qtyInput = document.getElementById('product-quantity');
  document.getElementById('qty-minus')?.addEventListener('click', function() {
    if(qtyInput && parseInt(qtyInput.value) > 1) qtyInput.value = parseInt(qtyInput.value) - 1;
  });
  document.getElementById('qty-plus')?.addEventListener('click', function() {
    if(qtyInput) qtyInput.value = parseInt(qtyInput.value) + 1;
  });
});
</script>

{{-- JS: Color/Size Variant Logic (UNCHANGED) --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const colorButtons = document.querySelectorAll('.product-color-button');
        const sizesContainer = document.getElementById('product-sizes');
        const sizesContainerWrapper = document.getElementById('product-sizes-container');
        const sizeRequiredMessage = document.getElementById('size-required-message');
        const priceElement = document.getElementById('product-price');
        const stockElement = document.getElementById('product-variant-stock');
        const selectedSizeIdInput = document.getElementById('selected-size-id');
        const selectedVariantInput = document.getElementById('selected-product-variant-id');
        const addToCartButton = document.getElementById('add-to-cart-button');
        const addToWishlistButton = document.getElementById('add-to-wishlist-button');
        const addToCartForm = document.getElementById('add-to-cart-form');
        const imageElement = document.getElementById('product-detail-image');
        let variants = [];

        if (!colorButtons.length) {
            addToCartButton.disabled = false;
            if(addToWishlistButton) addToWishlistButton.disabled = false;
            return;
        }

        const originalPriceElement = document.getElementById('product-original-price');
        const discountBadgeElement  = document.getElementById('product-discount-badge');

        colorButtons.forEach((button) => {
            variants.push({
                variant_id:     button.dataset.variantId,
                image:          button.dataset.colorImage,
                price:          Number(button.dataset.colorPrice),
                original_price: Number(button.dataset.colorOriginalPrice || 0),
                discount:       Number(button.dataset.colorDiscount || 0),
                stock:          Number(button.dataset.colorStock),
                sizes:          JSON.parse(button.dataset.colorSizes || '[]'),
            });
        });

        function updateVariant(index) {
            colorButtons.forEach(btn => btn.classList.remove('active'));
            colorButtons[index].classList.add('active');

            const variant = variants[index];
            if (priceElement) priceElement.textContent = '₹' + Number(variant.price).toFixed(2);

            if (originalPriceElement && discountBadgeElement) {
                if (variant.original_price > variant.price && variant.original_price > 0) {
                    originalPriceElement.textContent = '₹' + Number(variant.original_price).toFixed(2);
                    originalPriceElement.style.display = 'inline';
                    const pct = Math.round(((variant.original_price - variant.price) / variant.original_price) * 100);
                    discountBadgeElement.textContent = pct + '% OFF';
                    discountBadgeElement.style.display = 'inline-block';
                } else if (variant.discount > 0) {
                    discountBadgeElement.textContent = variant.discount + '% OFF';
                    discountBadgeElement.style.display = 'inline-block';
                } else {
                    originalPriceElement.style.display = 'none';
                    discountBadgeElement.style.display = 'none';
                }
            }

            const sizeButtons = variant.sizes.map((size, idx) => {
                return `<button type="button" class="pd-size-btn size-option ${idx === 0 ? 'active' : ''}" data-size-id="${size.id}" data-stock="${size.stock}" data-price="${size.price}" data-original-price="${size.original_price}" data-discount="${size.discount}">${size.name}</button>`;
            }).join('');

            sizesContainer.innerHTML = sizeButtons;
            if (variant.sizes && variant.sizes.length > 0) {
                stockElement.textContent = variant.sizes[0].stock;
                if (priceElement && variant.sizes[0].price > 0) {
                    priceElement.innerHTML = '₹' + Number(variant.sizes[0].price).toFixed(2);
                }
                if (originalPriceElement && discountBadgeElement) {
                    const s0Orig = Number(variant.sizes[0].original_price || 0);
                    const s0Price = Number(variant.sizes[0].price || 0);
                    if (s0Orig > s0Price && s0Orig > 0) {
                        originalPriceElement.textContent = '₹' + s0Orig.toFixed(2);
                        originalPriceElement.style.display = 'inline';
                        const pct = Math.round(((s0Orig - s0Price) / s0Orig) * 100);
                        discountBadgeElement.textContent = pct + '% OFF';
                        discountBadgeElement.style.display = 'inline-block';
                    } else if (variant.sizes[0].discount > 0) {
                        discountBadgeElement.textContent = variant.sizes[0].discount + '% OFF';
                        discountBadgeElement.style.display = 'inline-block';
                    } else {
                        originalPriceElement.style.display = 'none';
                        discountBadgeElement.style.display = 'none';
                    }
                }
            } else {
                stockElement.textContent = variant.stock ?? 0;
            }
            selectedVariantInput.value = variant.variant_id;

            if (variant.sizes && variant.sizes.length > 0) {
                selectedSizeIdInput.value = variant.sizes[0].id;
            } else {
                selectedSizeIdInput.value = '';
            }

            if (variant.sizes.length) {
                sizesContainerWrapper.classList.remove('d-none');
                sizeRequiredMessage.classList.add('d-none');
            } else {
                sizesContainerWrapper.classList.add('d-none');
                selectedSizeIdInput.value = '';
            }

            if (imageElement) {
                if (variant.image) {
                    imageElement.src = `{{ asset('storage') }}/${variant.image}`;
                } else {
                    imageElement.src = "{{ $product->seller_id ? asset('storage/' . $product->image) : asset('uploads/' . $product->image) }}";
                }
            }

            document.querySelectorAll('.size-option').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.size-option').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    selectedSizeIdInput.value = this.dataset.sizeId;
                    stockElement.textContent = this.dataset.stock;
                    const size = variant.sizes.find(s => s.id == this.dataset.sizeId);
                    if (size) {
                        if (priceElement && size.price > 0) {
                            priceElement.innerHTML = '₹' + Number(size.price).toFixed(2);
                        }
                        if (originalPriceElement && discountBadgeElement) {
                            const sOrig = Number(size.original_price || 0);
                            const sPrice = Number(size.price || 0);
                            if (sOrig > sPrice && sOrig > 0) {
                                originalPriceElement.textContent = '₹' + sOrig.toFixed(2);
                                originalPriceElement.style.display = 'inline';
                                const pct = Math.round(((sOrig - sPrice) / sOrig) * 100);
                                discountBadgeElement.textContent = pct + '% OFF';
                                discountBadgeElement.style.display = 'inline-block';
                            } else {
                                originalPriceElement.style.display = 'none';
                                discountBadgeElement.style.display = 'none';
                            }
                        }
                    }
                    updateAddToCartState();
                });
            });

            updateAddToCartState();
        }

        function updateAddToCartState() {
            const activeVariant = variants.find(v => v.variant_id === selectedVariantInput.value);
            const requiresSize = activeVariant && activeVariant.sizes.length;
            const hasSize = selectedSizeIdInput.value !== '';
            const inStock = Number(stockElement.textContent || 0) > 0;

            if (!inStock) {
                addToCartButton.disabled = true;
                if(addToWishlistButton) addToWishlistButton.disabled = true;
                sizeRequiredMessage.classList.add('d-none');
            } else if (requiresSize && !hasSize) {
                addToCartButton.disabled = true;
                if(addToWishlistButton) addToWishlistButton.disabled = true;
                sizeRequiredMessage.classList.remove('d-none');
            } else {
                addToCartButton.disabled = false;
                if(addToWishlistButton) addToWishlistButton.disabled = false;
                sizeRequiredMessage.classList.add('d-none');
            }
        }

        if (addToCartForm) {
            addToCartForm.addEventListener('submit', function(e) {
                if (addToCartButton.disabled) {
                    e.preventDefault();
                    alert('Please select all required options before adding to cart.');
                    return false;
                }
            });
        }

        if (colorButtons.length) {
            updateVariant(0);
        }

        colorButtons.forEach((button, index) => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                updateVariant(index);
            });
        });
    });
</script>

@endsection
