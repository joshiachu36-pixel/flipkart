@extends('layout.admin')

@section('content')

@if(session('success'))

    <div class="alert alert-success">

        {{ session('success') }}

    </div>

@endif

<div class="container mt-4">

    <h2>Color List</h2>

    <a href="{{ route('colors.create') }}"
       class="btn btn-success mb-3">

        Add Color

    </a>

    <table class="table table-bordered">

        <thead>

            <tr>

                <th>ID</th>

                <th>Color Name</th>

                <th>Color Code</th>

                <th>Status</th>

                <th>Action</th>

            </tr>

        </thead>

        <tbody>

            @forelse($colors as $color)

                <tr>

                    <td>{{ $color->id }}</td>

                    <td>{{ $color->name }}</td>

                    <td>

                        {{ $color->code }}

                    </td>

                    <td>

                        @if($color->status)

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

                        <a href="{{ route('colors.edit',$color->id) }}"
                           class="btn btn-warning btn-sm">

                            Edit

                        </a>

                        <form
                            action="{{ route('colors.destroy',$color->id) }}"
                            method="POST"
                            style="display:inline;">

                            @csrf

                            @method('DELETE')

                            <button
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete this color?')">

                                Delete

                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="text-center">

                        No Colors Found.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection