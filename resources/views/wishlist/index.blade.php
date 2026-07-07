@extends('layouts.shop')

@section('content')

<div class="container py-4">

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <h2 class="fw-bold mb-4">

        ❤️ My Wishlist

    </h2>

    @if($wishlistItems->count())

        <div class="row">

            @foreach($wishlistItems as $item)

                <div class="col-md-4 mb-4">

                    <div class="card shadow-sm h-100">

                        <!-- Product Image -->

                        <a href="{{ url('/product-details/'.$item->product->id) }}{{ $item->collection ? '?collection='.$item->collection->slug : '' }}">

                            <img
                                src="{{ asset('uploads/'.$item->product->image) }}"
                                class="card-img-top"
                                style="height:220px;object-fit:cover;">

                        </a>

                        <div class="card-body">

                            <!-- Product Name -->

                            <h5 class="mb-3">

                                <a
                                    href="{{ url('/product-details/'.$item->product->id) }}{{ $item->collection ? '?collection='.$item->collection->slug : '' }}"
                                    class="text-decoration-none text-dark fw-semibold">

                                    {{ $item->product->name }}

                                </a>

                            </h5>

                            @php

                                $price = $item->product->price;

                                if ($item->collection) {

                                    if ($item->collection->discount_type == 'percentage') {

                                        $price = $price -
                                            (($price * $item->collection->discount_value) / 100);

                                    }

                                    elseif ($item->collection->discount_type == 'fixed') {

                                        $price = max(
                                            0,
                                            $price - $item->collection->discount_value
                                        );

                                    }

                                }

                            @endphp

                            @if($item->collection)

                                <div class="mb-2">

                                    <div class="text-muted">

                                        <del>

                                            ₹{{ number_format($item->product->price,2) }}

                                        </del>

                                    </div>

                                    <h4 class="text-success fw-bold mb-1">

                                        ₹{{ number_format($price,2) }}

                                    </h4>

                                    <span class="badge bg-danger">

                                        @if($item->collection->discount_type == 'percentage')

                                            {{ $item->collection->discount_value }}% OFF

                                        @else

                                            ₹{{ $item->collection->discount_value }} OFF

                                        @endif

                                    </span>

                                </div>

                            @else

                                <h4 class="text-success fw-bold">

                                    ₹{{ number_format($item->product->price,2) }}

                                </h4>

                            @endif

                            <hr>

                            <!-- Bottom Actions -->

                            <div class="d-flex justify-content-between align-items-center mt-3">

                                <!-- Remove Wishlist -->

                                <form
                                    action="{{ route('wishlist.remove',$item) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-link text-danger fs-3 p-0 border-0"
                                        title="Remove from Wishlist">

                                        ❤️

                                    </button>

                                </form>

                                <!-- Add To Cart -->

                                <form
                                    action="{{ route('cart.add', $item->product) }}"
                                    method="POST">

                                    @csrf

                                    @if($item->collection)

                                        <input
                                            type="hidden"
                                            name="collection_slug"
                                            value="{{ $item->collection->slug }}">

                                    @endif

                                    <button
                                        type="submit"
                                        class="btn btn-warning fw-semibold px-4">

                                        🛒 Add To Cart

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="alert alert-info text-center">

            <h5>Your wishlist is empty.</h5>

            <p class="mb-0">

                Start adding your favourite products ❤️

            </p>

        </div>

    @endif

</div>

@endsection