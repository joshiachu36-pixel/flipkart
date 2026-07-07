@extends('layout.admin')

@section('content')

<div class="container mt-5">

    <div class="row">

        <div class="col-md-6">

            <img src="{{ asset('uploads/'.$product->image) }}"
     class="img-fluid rounded shadow"
     alt="{{ $product->name }}">

        </div>

        <div class="col-md-6">

            <h2>{{ $product->name }}</h2>

            <div class="mb-3">

                <h2 class="text-success fw-bold mb-1">
                    ₹{{ number_format($product->price, 2) }}
                </h2>

                @if($product->discount_percentage > 0)

                    <div class="d-flex align-items-center gap-2">

                        <span class="text-muted text-decoration-line-through fs-5">
                            ₹{{ number_format($product->original_price, 2) }}
                        </span>

                        <span class="badge bg-danger fs-6">
                            {{ $product->discount_percentage }}% OFF
                        </span>

                    </div>

                @endif

            </div>

            @if($product->stock > 0)

                <p class="text-success fw-bold fs-5">
                    ✔ In Stock
                </p>

            @else

                <p class="text-danger fw-bold fs-5">
                    ✖ Out of Stock
                </p>

            @endif

            <p class="mt-3">
                {{ $product->description }}
            </p>

        </div>

    </div>

</div>

@endsection