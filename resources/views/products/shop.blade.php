@extends('layouts.shop')

@section('content')


<div class="container mt-4">
    <div class="row">

    @if($category)

        <div class="col-md-3">

            <div class="card shadow-sm p-3">

                <h5 class="mb-3">Categories</h5>

                @include('categories.sidebar-tree', [
                    'categories' => $sidebarCategories,
                    'selectedCategoryId' => optional($category)->id,
                    'openIds' => $openIds ?? [],
                ])

            </div>

        </div>

        <div class="col-md-9">

        @else

            <div class="col-md-12">

        @endif
            <div class="mb-4">

    <h3 class="mb-2">
        {{ $collection->name ?? optional($category)->name ?? 'All Products' }}
    </h3>

    @if(!empty($collection?->description))

        <p class="text-muted mb-0">

            {{ $collection->description }}

        </p>

    @endif

</div>

{{-- ════════════════════════════════════════════════
     SHOP BY BRANDS — Approved Seller Storefronts
════════════════════════════════════════════════ --}}
@if(!$category && !$collection && isset($approvedSellers) && $approvedSellers->count())
<div class="mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h5 class="fw-bold mb-0" style="color:#1a1a2e;">
                <i class="bi bi-shop me-2" style="color:#2874f0;"></i>Shop by Brands
            </h5>
            <small class="text-muted">Explore our verified seller storefronts</small>
        </div>
    </div>

    <style>
        /* ── Brand Cards ─────────────────────────────── */
        .brand-scroll-wrap {
            display: flex;
            gap: 14px;
            overflow-x: auto;
            padding-bottom: 8px;
            scrollbar-width: thin;
            scrollbar-color: #dee2e6 transparent;
        }
        .brand-scroll-wrap::-webkit-scrollbar { height: 4px; }
        .brand-scroll-wrap::-webkit-scrollbar-track { background: transparent; }
        .brand-scroll-wrap::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 4px; }

        .brand-card {
            flex-shrink: 0;
            width: 130px;
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
            padding: 18px 12px 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
            border: 1.5px solid transparent;
            transition: border-color 0.22s ease, box-shadow 0.22s ease, transform 0.22s ease;
            cursor: pointer;
        }
        .brand-card:hover {
            border-color: #2874f0;
            box-shadow: 0 6px 20px rgba(40,116,240,0.13);
            transform: translateY(-3px);
            color: inherit;
            text-decoration: none;
        }
        .brand-logo-wrap {
            width: 62px;
            height: 62px;
            border-radius: 12px;
            overflow: hidden;
            background: #f8f9fa;
            border: 1.5px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .brand-logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .brand-logo-placeholder {
            width: 62px;
            height: 62px;
            border-radius: 12px;
            background: linear-gradient(135deg, #2874f0, #0f4fc8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #fff;
            flex-shrink: 0;
        }
        .brand-name {
            font-size: 0.76rem;
            font-weight: 600;
            color: #1a1a2e;
            text-align: center;
            line-height: 1.3;
            max-width: 106px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .brand-visit-label {
            font-size: 0.65rem;
            color: #2874f0;
            font-weight: 500;
            letter-spacing: 0.3px;
        }
    </style>

    <div class="brand-scroll-wrap">
        @foreach($approvedSellers as $brandSeller)
            <a href="{{ route('store.show', $brandSeller->id) }}"
               class="brand-card"
               title="Visit {{ $brandSeller->business_name }} Store">

                {{-- Business Logo --}}
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

                {{-- Business Name --}}
                <div class="brand-name" title="{{ $brandSeller->business_name }}">
                    {{ $brandSeller->business_name }}
                </div>

                <div class="brand-visit-label">Visit Store →</div>
            </a>
        @endforeach
    </div>

    <hr class="mt-3 mb-1">
</div>
@endif

            <div class="row">

        @foreach($products as $product)

            <div class="col-md-4 mb-4">

                <div class="card h-100 shadow-sm">

                    <a href="{{ url('/product-details/'.$product->id) }}{{ isset($collection) ? '?collection='.$collection->slug : '' }}">

                        <img
                            src="{{ $product->seller_id ? asset('storage/'.$product->image) : asset('uploads/'.$product->image) }}"
                            class="card-img-top"
                            style="height:220px;object-fit:cover;"
                        >

                    </a>

                    <div class="card-body text-center">

                        <h6>{{ $product->name }}</h6>

                        {{-- Seller / Store Branding --}}
                        @if($product->seller_id && $product->seller)
                        <div class="d-flex align-items-center justify-content-center gap-1 mb-2">
                            <a href="{{ route('store.show', $product->seller_id) }}"
                               class="d-flex align-items-center gap-1 text-decoration-none text-muted"
                               title="Visit {{ $product->seller->business_name }} Store"
                               style="font-size:0.75rem;">
                                @if($product->seller->business_logo)
                                    <img src="{{ asset('storage/' . $product->seller->business_logo) }}"
                                         alt="{{ $product->seller->business_name }}"
                                         style="width:20px;height:20px;border-radius:4px;object-fit:cover;border:1px solid #dee2e6;">
                                @else
                                    <span style="width:20px;height:20px;border-radius:4px;background:linear-gradient(135deg,#2874f0,#0f4fc8);display:inline-flex;align-items:center;justify-content:center;font-size:9px;color:#fff;flex-shrink:0;">
                                        <i class="bi bi-shop"></i>
                                    </span>
                                @endif
                                <span style="max-width:110px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $product->seller->business_name }}</span>
                            </a>
                        </div>
                        @endif

                        @php

    $finalPrice = $product->price;

    if(isset($collection) && $collection->discount_type == 'percentage')
    {
        $finalPrice = $product->price -
            (($product->price * $collection->discount_value) / 100);
    }

    elseif(isset($collection) && $collection->discount_type == 'fixed')
    {
        $finalPrice = max(
            0,
            $product->price - $collection->discount_value
        );
    }

@endphp

@if(isset($collection) && $collection->discount_value)

    <h6 class="text-muted">
        <del>₹{{ number_format($product->price, 2) }}</del>
    </h6>

    <h5 class="text-success">
        ₹{{ number_format($finalPrice, 2) }}
    </h5>

    <span class="badge bg-danger">
        @if($collection->discount_type == 'percentage')
            {{ $collection->discount_value }}% OFF
        @else
            ₹{{ $collection->discount_value }} OFF
        @endif
    </span>

@else

                                <h5 class="text-success">
                                    ₹{{ number_format($product->price, 2) }}
                                </h5>

                            @endif

                        <hr>

                        <div class="d-flex align-items-center justify-content-between">

                            <form action="{{ route('wishlist.toggle', $product) }}"
                            method="POST">

                                @csrf

                                @if(isset($collection))

                                    <input type="hidden"
                                    name="collection_slug"
                                    value="{{ $collection->slug }}">

                                @endif

                                <button
                                type="submit"
                                class="btn btn-link text-dark p-0 fs-3 border-0">

                                @if(in_array($product->id, $wishlistProductIds ?? []))

                                    <i class="bi bi-heart-fill text-danger"></i>

                                @else

                                    <i class="bi bi-heart"></i>

                                @endif

                            </button>

                            </form>

                            <form action="{{ route('cart.add', $product) }}"
                                method="POST">

                                @csrf

                                @if(isset($collection))

                                    <input type="hidden"
                                        name="collection_slug"
                                        value="{{ $collection->slug }}">

                                @endif

                                <button
                                    type="submit"
                                    class="btn btn-warning">

                                    🛒 Add To Cart

                                </button>

                            </form>

                        </div>
                    </div>

                </div>

            </div>

        @endforeach

            </div>
        </div>
    </div>
</div>

@endsection


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // toggle + / - when collapse elements show/hide
        document.querySelectorAll('.collapse').forEach(function (el) {
            el.addEventListener('show.bs.collapse', function (e) {
                var btn = document.querySelector('[data-bs-target="#' + e.target.id + '"]');
                if (btn) btn.innerText = '-';
            });
            el.addEventListener('hide.bs.collapse', function (e) {
                var btn = document.querySelector('[data-bs-target="#' + e.target.id + '"]');
                if (btn) btn.innerText = '+';
            });
        });
    });
</script>