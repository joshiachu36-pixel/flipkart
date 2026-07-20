@extends('layout.seller')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-palette text-primary me-2"></i>My Colors</h2>
            <p class="text-muted mb-0">Manage color attributes for your product catalog.</p>
        </div>
        <a href="{{ route('seller.colors.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add New Color
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="10%">Preview</th>
                            <th>Color Name</th>
                            <th>Color Code</th>
                            <th>Status</th>
                            <th width="15%" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($colors as $color)
                            <tr>
                                <td>
                                    <div style="width: 28px; height: 28px; background-color: {{ $color->code }}; border-radius: 50%; border: 2px solid #ddd;" title="{{ $color->code }}"></div>
                                </td>
                                <td class="fw-semibold text-dark">{{ $color->name }}</td>
                                <td><code class="px-2 py-1 bg-light rounded text-dark fs-6">{{ $color->code }}</code></td>
                                <td>
                                    @if($color->status)
                                        <span class="badge bg-success-subtle text-success border border-success px-2 py-1">Active</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary px-2 py-1">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('seller.colors.edit', $color) }}" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('seller.colors.destroy', $color) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this color?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-palette fs-1 d-block mb-2 text-secondary"></i>
                                    No custom colors added yet. Click "Add New Color" to create your first color attribute.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
