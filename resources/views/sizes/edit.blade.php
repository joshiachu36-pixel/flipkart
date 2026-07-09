@extends('layout.admin')

@section('content')

<div class="container mt-4">

    <h2>Edit Size</h2>

    <div class="card shadow">

        <div class="card-body">

            <form action="{{ route('sizes.update',$size->id) }}"
                  method="POST">

                @csrf

                @method('PUT')

                <div class="mb-3">

                    <label>Size Name</label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ $size->name }}">

                </div>

                <div class="mb-3">

                    <label>Status</label>

                    <select
                        name="status"
                        class="form-select">

                        <option value="1"
                            {{ $size->status ? 'selected' : '' }}>

                            Active

                        </option>

                        <option value="0"
                            {{ !$size->status ? 'selected' : '' }}>

                            Inactive

                        </option>

                    </select>

                </div>

                <button
                    class="btn btn-primary">

                    Update

                </button>

                <a href="{{ route('sizes.index') }}"
                   class="btn btn-secondary">

                    Back

                </a>

            </form>

        </div>

    </div>

</div>

@endsection