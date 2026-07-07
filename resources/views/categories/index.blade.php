@extends('layout.admin')

@section('content')

<div class="content-header">
    <div class="container-fluid">

        <div class="d-flex justify-content-between mb-3">

            <h1>Categories</h1>

            <a href="/category/create"
               class="btn btn-primary">
                Add Category
            </a>

        </div>

        <table class="table table-bordered table-striped">

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Parent</th>
                    <th>Action</th>
                </tr>

            </thead>

            <tbody>

            @foreach($categories as $category)

                <tr>

                    <td>{{ $category->id }}</td>

                    <td>

                        @if($category->image)

                        <img src="{{ asset('category_images/'.$category->image) }}"
                             width="60">

                        @endif

                    </td>

                    <td>{{ $category->name }}</td>

                    <td>
                        {{ $category->parent?->name ?? 'Main Category' }}
                    </td>

                    <td>

                        <a href="/category/edit/{{ $category->id }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <a href="/category/delete/{{ $category->id }}"
                           class="btn btn-danger btn-sm">
                            Delete
                        </a>

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>
</div>

@endsection