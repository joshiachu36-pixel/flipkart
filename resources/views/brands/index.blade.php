@extends('layout.admin')

@section('content')

<div class="container mt-4">

    <h2>Brand List</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="/brand/create"
       class="btn btn-success mb-3">
        Add Brand
    </a>

    <table class="table table-bordered">

        <thead>

            <tr>

                <th width="10%">ID</th>

                <th>Brand Name</th>

                <th>Slug</th>

                <th>Status</th>

                <th width="25%">Action</th>

            </tr>

        </thead>

        <tbody>

            @forelse($brands as $brand)

                <tr>

                    <td>{{ $brand->id }}</td>

                    <td>{{ $brand->name }}</td>

                    <td>{{ $brand->slug }}</td>

                    <td>

                        @if($brand->status)

                            <span class="badge bg-success">
                                Active
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Inactive
                            </span>

                        @endif

                    </td>

                    <td>

                        <a href="/brand/edit/{{ $brand->id }}"
                           class="btn btn-warning btn-sm">

                            Edit

                        </a>

                        <a href="/brand/delete/{{ $brand->id }}"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this brand?')">

                            Delete

                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="text-center">

                        No Brands Found.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection