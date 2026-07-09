@extends('layout.admin')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2>Sizes</h2>

        <a href="{{ route('sizes.create') }}"
           class="btn btn-primary">
            + Add Size
        </a>

    </div>

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <div class="card shadow">

        <div class="card-body">

            <table class="table table-bordered align-middle">

                <thead class="table-dark">

                    <tr>

                        <th width="10%">ID</th>

                        <th>Name</th>

                        <th>Status</th>

                        <th width="20%">Action</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($sizes as $size)

                        <tr>

                            <td>{{ $size->id }}</td>

                            <td>{{ $size->name }}</td>

                            <td>

                                @if($size->status)

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

                                <a href="{{ route('sizes.edit',$size->id) }}"
                                   class="btn btn-warning btn-sm">

                                    Edit

                                </a>

                                <form action="{{ route('sizes.destroy',$size->id) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this size?')">

                                        Delete

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4"
                                class="text-center">

                                No Sizes Found

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

            {{ $sizes->links() }}

        </div>

    </div>

</div>

@endsection