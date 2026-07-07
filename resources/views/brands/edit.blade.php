@extends('layout.admin')

@section('content')

<div class="container mt-4">

    <h2>Edit Brand</h2>

    <a href="/brands" class="btn btn-secondary mb-3">
        Back to Brands
    </a>

    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form action="/brand/update/{{ $brand->id }}" method="POST">

        @csrf

        <div class="mb-3">

            <label class="form-label">
                Brand Name
            </label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ old('name', $brand->name) }}">

        </div>

        <div class="mb-3">

            <label class="form-label">
                Status
            </label>

            <select
                name="status"
                class="form-select">

                <option value="1"
                    {{ $brand->status ? 'selected' : '' }}>
                    Active
                </option>

                <option value="0"
                    {{ !$brand->status ? 'selected' : '' }}>
                    Inactive
                </option>

            </select>

        </div>

        <button
            type="submit"
            class="btn btn-primary">
            Update Brand
        </button>

    </form>

</div>

@endsection