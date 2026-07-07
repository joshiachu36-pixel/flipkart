@extends('layout.admin')

@section('content')

<div class="container-fluid mt-4">

    <div class="row">

        <div class="col-md-3">

            <div class="card-body">

    <ul class="list-group">

    @foreach($categories as $parent)

        <li class="list-group-item">

            <a href="/category/{{ $parent->id }}">
                {{ $parent->name }}
            </a>

            @if($parent->children->count())

    <ul class="mt-2">

        @foreach($parent->children as $child)

            <li>

                <a href="/category/{{ $child->id }}">
                    {{ $child->name }}
                </a>

                @if($child->children->count())

                    <ul class="ms-3 mt-1">

                        @foreach($child->children as $subchild)

                            <li>

                                <a href="/category/{{ $subchild->id }}">
                                    {{ $subchild->name }}
                                </a>

                            </li>

                        @endforeach

                    </ul>

                @endif

            </li>

        @endforeach

    </ul>

@endif

        </li>

    @endforeach

    </ul>

</div>

        </div>

        <div class="col-md-9">

            <div class="row">

                @foreach($products as $product)

                <div class="col-md-4 mb-4">

                    <div class="card h-100">

                        <a href="/product-details/{{ $product->id }}">

                            <img src="{{ asset('uploads/'.$product->image) }}"
                                 class="card-img-top"
                                 style="height:250px;object-fit:cover;">

                        </a>

                        <div class="card-body">

                            <h5>
                                {{ $product->name }}
                            </h5>

                            <h6>
                                ₹{{ $product->price }}
                            </h6>

                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

    </div>

</div>

@endsection