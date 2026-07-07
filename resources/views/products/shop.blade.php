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

            <div class="row">

        @foreach($products as $product)

            <div class="col-md-4 mb-4">

                <div class="card h-100 shadow-sm">

                    <a href="{{ url('/product-details/'.$product->id) }}{{ isset($collection) ? '?collection='.$collection->slug : '' }}">

                        <img
                            src="{{ asset('uploads/'.$product->image) }}"
                            class="card-img-top"
                            style="height:220px;object-fit:cover;"
                        >

                    </a>

                    <div class="card-body text-center">

                        <h6>{{ $product->name }}</h6>

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