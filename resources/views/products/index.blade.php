@extends('layout.admin')

@section('content')

<div class="container mt-4">

    <h2>Product List</h2>

    <a href="/product/create"
       class="btn btn-success mb-3">
        Add Product
    </a>

    <a href="/product/view"
       class="btn btn-warning mb-3">
        See Formate
    </a>
    
    <table class="table table-bordered">

        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Category</th>
            <th>Brand</th>
            <th>Collections</th>
            <th>Action</th>
        </tr>

        @foreach($products as $product)

        <tr>

            <td>

            <a href="/product/{{ $product->id }}">

                <img src="{{ asset('uploads/'.$product->image) }}"
                    width="80">

            </a>

            </td>

            <td>{{ $product->name }}</td>

            <td>₹{{ $product->price }}</td>

            <td>{{ $product->category->name ?? 'N/A' }}</td>

            <td>

                {{ $product->brand->name ?? 'N/A' }}

            </td>

            <td>

                @foreach($product->collections as $collection)

                    <span class="badge bg-primary">

                        {{ $collection->name }}

                    </span>

                @endforeach

            </td>

            <td>

                <a href="/product/edit/{{ $product->id }}"
                   class="btn btn-warning">
                    Edit
                </a>

                <a href="{{ route('products.variants.manage', $product->id) }}"
                   class="btn btn-info">
                    Update Variant
                </a>

                <a href="/product/delete/{{ $product->id }}"
                   class="btn btn-danger">
                    Delete
                </a>

                <a href="/product/{{ $product->id }}"
                class="btn btn-primary">
                    View
                </a>
            </td>

        </tr>

        @endforeach

    </table>

</div>

@endsection