@extends('layout.admin')

@section('content')

<div class="container mt-4">

    <h2>Add Brand</h2>

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

    <form action="/brand/store" method="POST">

        @csrf

        <div class="mb-3">

            <label class="form-label">

                Brand Name

            </label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ old('name') }}"
                placeholder="Enter Brand Name">

        </div>

        <div class="mb-3">

            <label class="form-label">

                Status

            </label>

            <select
                name="status"
                class="form-select">

                <option value="1">
                    Active
                </option>

                <option value="0">
                    Inactive
                </option>

            </select>

        </div>

        <button
            type="submit"
            class="btn btn-primary">

            Save Brand

        </button>

    </form>

</div>

@endsection