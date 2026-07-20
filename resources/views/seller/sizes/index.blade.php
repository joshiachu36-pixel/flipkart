@extends('layout.seller')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-ruler text-primary me-2"></i>My Sizes</h2>
            <p class="text-muted mb-0">Manage size options for your product catalog.</p>
        </div>
        <a href="{{ route('seller.sizes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add New Size
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
                            <th>Size Name</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th width="15%" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sizes as $size)
                            <tr>
                                <td class="fw-bold text-dark fs-6">{{ $size->name }}</td>
                                <td>
                                    @if($size->status)
                                        <span class="badge bg-success-subtle text-success border border-success px-2 py-1">Active</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary px-2 py-1">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $size->created_at ? $size->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('seller.sizes.edit', $size) }}" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('seller.sizes.destroy', $size) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this size?');">
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
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-ruler fs-1 d-block mb-2 text-secondary"></i>
                                    No custom sizes added yet. Click "Add New Size" to create your first size option.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($sizes->hasPages())
            <div class="card-footer bg-white py-3 border-0">
                {{ $sizes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
