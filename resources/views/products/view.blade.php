@extends('layout.admin')

@section('content')

<div class="container-fluid mt-4">

    <div class="row">

        @foreach($products as $product)

        <div class="col-md-3 mb-4">

            <div class="card h-100 shadow-sm">

                <a href="/product/{{ $product->id }}">

                    <img src="{{ asset('uploads/'.$product->image) }}"
                         class="card-img-top"
                         style="height:250px;object-fit:cover;">

                </a>

                <div class="card-body">

                    <h5>{{ $product->name }}</h5>

                    <h4 class="text-success">
                        ₹{{ $product->price }}
                    </h4>

                    <a href="/product/{{ $product->id }}"
                       class="btn btn-primary w-100">
                        View Details
                    </a>

                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>

@endsection