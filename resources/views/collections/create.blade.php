@extends('layout.admin')

@section('content')

<div class="container">

    <div class="row">

        <!-- Add Collection -->
        <div class="col-md-6">

            <div class="card">
                <div class="card-header">
                    <h4>Add Collection</h4>
                </div>

                <div class="card-body">

                    <form action="{{ route('collections.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label>Collection Name</label>

                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>Description</label>

                            <textarea
                                name="description"
                                class="form-control"
                                rows="3"></textarea>
                        </div>

                        <div class="mb-3">

                            <label>Status</label>

                            <select name="status" class="form-control">

                                <option value="1">Active</option>

                                <option value="0">Inactive</option>

                            </select>

                        </div>

                        <div class="mb-3">
                            <label class="form-label">Discount Type</label>

                            <select name="discount_type" class="form-control">
                                <option value="">No Discount</option>
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Amount (₹)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Discount Value</label>

                            <input
                                type="number"
                                step="0.01"
                                name="discount_value"
                                class="form-control"
                                placeholder="Enter Discount Value">
                        </div>

                        <button type="submit"
                                class="btn btn-primary">
                            Save
                        </button>

                    </form>

                </div>
            </div>

        </div>

        <!-- Collection List -->
        <div class="col-md-6">

            <div class="card">
                <div class="card-header">
                    <h4>Collections</h4>
                </div>

                <div class="card-body">

                    <table class="table table-bordered">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($collections as $collection)

                            <tr>
                                <td>{{ $collection->id }}</td>

                                <td>{{ $collection->name }}</td>

                                <td>

                                    <button
                                        class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $collection->id }}">
                                        Edit
                                    </button>

                                </td>
                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>
            </div>

        </div>

    </div>

</div>


<!-- All Modals -->
@foreach($collections as $collection)

<div class="modal fade"
     id="editModal{{ $collection->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Edit Collection
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <!-- Update Form -->
            <form action="{{ route('collections.update',$collection->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="modal-body">

    <div class="mb-3">

        <label>Collection Name</label>

        <input
            type="text"
            name="name"
            class="form-control"
            value="{{ $collection->name }}"
            required>

    </div>

    <div class="mb-3">

        <label>Description</label>

        <textarea
            name="description"
            class="form-control"
            rows="3">{{ $collection->description }}</textarea>

    </div>

    <div class="mb-3">

        <label>Discount Type</label>

        <select
            name="discount_type"
            class="form-control">

            <option value="">No Discount</option>

            <option
                value="percentage"
                {{ $collection->discount_type=='percentage' ? 'selected' : '' }}>

                Percentage (%)

            </option>

            <option
                value="fixed"
                {{ $collection->discount_type=='fixed' ? 'selected' : '' }}>

                Fixed Amount (₹)

            </option>

        </select>

    </div>

    <div class="mb-3">

        <label>Discount Value</label>

        <input
            type="number"
            step="0.01"
            name="discount_value"
            class="form-control"
            value="{{ $collection->discount_value }}">

    </div>

    <div class="mb-3">

        <label>Status</label>

        <select
            name="status"
            class="form-control">

            <option
                value="1"
                {{ $collection->status ? 'selected' : '' }}>

                Active

            </option>

            <option
                value="0"
                {{ !$collection->status ? 'selected' : '' }}>

                Inactive

            </option>

        </select>

    </div>

</div>

                <div class="modal-footer">

                    <button type="submit"
                            class="btn btn-primary">
                        Update
                    </button>

            </form>

                    <!-- Delete Form -->
                    <form action="{{ route('collections.destroy',$collection->id) }}"
                          method="POST">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="btn btn-danger"
                                onclick="return confirm('Delete this collection?')">
                            Delete
                        </button>

                    </form>

                </div>

        </div>

    </div>

</div>

@endforeach

@endsection