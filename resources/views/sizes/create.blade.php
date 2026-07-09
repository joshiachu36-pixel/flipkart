@extends('layout.admin')

@section('content')

<div class="container mt-4">

    <h2>Add Size</h2>

    <div class="card shadow">

        <div class="card-body">

            <form action="{{ route('sizes.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">

                        Size Name

                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="Example: S">

                </div>

                <div class="mb-3">

                    <label>Status</label>

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

                <button class="btn btn-success">

                    Save

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