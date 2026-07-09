@extends('layouts.shop')

@section('content')

<div class="container py-4">

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <h2 class="mb-4 fw-bold">

        🛒 My Shopping Cart

    </h2>

    @if($cartItems->count())

    <div class="row">

        <!-- Left Side - Cart Items -->
        <div class="col-lg-8">

            @foreach($cartItems as $item)

            @php

                $price = $item->productVariant
                ? $item->productVariant->price
                : $item->product->price;

                if($item->collection)
                {
                    if($item->collection->discount_type == 'percentage')
                    {
                        $price = $price -
                            (($price * $item->collection->discount_value) / 100);
                    }
                    elseif($item->collection->discount_type == 'fixed')
                    {
                        $price = max(
                            0,
                            $price - $item->collection->discount_value
                        );
                    }
                }

            @endphp

            <div class="card shadow-sm border-0 mb-4">

                <div class="row g-0">

                    <!-- Product Image -->

                    <div class="col-md-3 text-center p-3">

                        <img
                        src="{{ $item->productVariant && $item->productVariant->image
                                ? asset('storage/'.$item->productVariant->image)
                                : asset('uploads/'.$item->product->image) }}"
                        alt="{{ $item->product->name }}"
                        class="img-fluid"
                        style="height:180px;object-fit:contain;">

                    </div>

                    <!-- Product Details -->

                    <div class="col-md-9">

                        <div class="card-body">

                            <h4 class="fw-bold">

                                {{ $item->product->name }}

                            </h4>

                            @if($item->productVariant)

                                <div class="small text-muted mt-2">

                                    <strong>Color:</strong>
                                    {{ $item->productVariant->color->name }}

                                </div>

                            @endif

                            @if($item->size)

                                <div class="small text-muted mb-2">

                                    <strong>Size:</strong>
                                    {{ $item->size->name }}

                                </div>

                            @endif

                            <p class="text-muted mb-1">

                                <strong>In Stock :</strong>

                                @if($item->size && $item->productVariant)

                                    {{ $item->productVariant->sizes
                                        ->firstWhere('id', $item->size_id)?->pivot?->stock ?? 0 }}

                                @elseif($item->productVariant)

                                    {{ $item->productVariant->stock }}

                                @else

                                    {{ $item->product->stock }}

                                @endif

                            </p>

                            @if($item->collection)

                                    <div class="mb-3">

                                        <span class="text-muted text-decoration-line-through">

                                            ₹{{ number_format(
                                                $item->productVariant
                                                    ? $item->productVariant->price
                                                    : $item->product->price,
                                                2
                                            ) }}

                                        </span>

                                        <br>

                                        <h5 class="text-success fw-bold">

                                            ₹{{ number_format($price,2) }}

                                        </h5>

                                        <span class="badge bg-danger">

                                        @if($item->collection->discount_type == 'percentage')

                                            {{ $item->collection->discount_value }}% OFF

                                        @else

                                            ₹{{ $item->collection->discount_value }} OFF

                                        @endif

                                    </span>

                                </div>

                            @else

                                <h5 class="text-success fw-bold mb-3">

                                   ₹{{ number_format(
    $item->productVariant
        ? $item->productVariant->price
        : $item->product->price,
    2
) }}

                                </h5>

                            @endif

                            <div class="d-flex align-items-center mb-3">

                                <form
                                    method="POST"
                                    action="{{ route('cart.decrease',$item) }}">

                                    @csrf

                                    <button
                                        class="btn btn-outline-secondary">

                                        -

                                    </button>

                                </form>

                                <span class="mx-3 fs-5 fw-bold">

                                    {{ $item->quantity }}

                                </span>

                                <form
                                    method="POST"
                                    action="{{ route('cart.increase',$item) }}">

                                    @csrf

                                    <button
                                        class="btn btn-outline-secondary">

                                        +

                                    </button>

                                </form>

                            </div>

                            <h6 class="mb-3">

                                <strong>Subtotal :</strong>

                               ₹{{ number_format($price * $item->quantity,2) }}

                            </h6>

                            <form
                                method="POST"
                                action="{{ route('cart.remove',$item) }}">

                                @csrf

                                @method('DELETE')

                                <button
                                    class="btn btn-danger">

                                    🗑 Remove

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

            @endforeach

            <div class="d-flex justify-content-between mb-5">

                <a
                    href="{{ url('/shop') }}"
                    class="btn btn-outline-primary btn-lg">

                    ← Continue Shopping

                </a>

                <button
                    class="btn btn-warning btn-lg">

                    Proceed to Checkout →

                </button>

            </div>

        </div>

        <!-- Right Side - Price Details -->

        <div class="col-lg-4">

            <div class="card shadow">

                <div class="card-header bg-light">

                    <h5 class="mb-0">

                        PRICE DETAILS

                    </h5>

                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <span>

                            Price ({{ $cartItems->sum('quantity') }} Items)

                        </span>

                        <span>

                            ₹{{ number_format($total) }}

                        </span>

                    </div>

                    <div class="d-flex justify-content-between mb-3">

                        <span>

                            Delivery Charges

                        </span>

                        <span class="text-success">

                            FREE

                        </span>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fw-bold fs-5">

                        <span>

                            Total Amount

                        </span>

                        <span>

                            ₹{{ number_format($total) }}

                        </span>

                    </div>

                    <hr>

                    <p class="text-success mb-0">

                        🎉 You saved on delivery charges.

                    </p>

                </div>

            </div>

        </div>

    </div>

    @else

        <div class="alert alert-warning text-center">

            <h4>

                🛒 Your cart is empty.

            </h4>

            <a
                href="{{ url('/shop') }}"
                class="btn btn-primary mt-3">

                Continue Shopping

            </a>

        </div>

    @endif

</div>

@endsection