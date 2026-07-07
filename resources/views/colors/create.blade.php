@extends('layout.admin')

@section('content')

<div class="container mt-4">

    <h2>Add Color</h2>

    <a href="{{ route('colors.index') }}"
       class="btn btn-secondary mb-3">

        Back to Colors

    </a>

    <form action="{{ route('colors.store') }}"
          method="POST">

        @csrf

        <div class="mb-3">

            <label class="form-label">

                Color Name

            </label>

            <input
                type="text"
                name="name"
                class="form-control"
                required>

        </div>

        <div class="mb-3">

            <label class="form-label">

                Color Code (Hex)

            </label>

            <input
                type="color"
                name="code"
                class="form-control form-control-color"
                value="#000000">

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

            Save Color

        </button>

    </form>

</div>

@endsection