@extends('layout.admin')

@section('content')
<div class="container-fluid py-3">
    {{-- Header --}}
    <div class="mb-3">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i>Back to Roles
        </a>
        <h4 class="mb-1 text-dark fw-bold"><i class="bi bi-info-circle me-2 text-primary"></i>Role Details: {{ $role->name }}</h4>
        <p class="text-muted small">Detailed specifications and logs for this role.</p>
    </div>

    {{-- Details Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-0">
            <h5 class="card-title fw-bold text-dark mb-0">Role Overview</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3 fw-semibold text-secondary small">Role Name:</div>
                <div class="col-md-9">
                    <span class="badge text-white px-3 py-2 fw-semibold" style="background-color: {{ $role->badge_color }}">
                        {{ $role->name }}
                    </span>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-3 fw-semibold text-secondary small">Description:</div>
                <div class="col-md-9 text-dark small">
                    {{ $role->description ?: 'No description provided.' }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-semibold text-secondary small">Status:</div>
                <div class="col-md-9">
                    @if($role->status === 'Active')
                        <span class="badge bg-success-subtle text-success border border-success px-2 py-1 small">
                            Active
                        </span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1 small">
                            Inactive
                        </span>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-semibold text-secondary small">Created By:</div>
                <div class="col-md-9 text-dark small">
                    {{ $role->creator ? $role->creator->name : 'System' }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-semibold text-secondary small">Created At:</div>
                <div class="col-md-9 text-dark small">
                    {{ $role->created_at ? $role->created_at->format('M d, Y H:i:s') : 'N/A' }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 fw-semibold text-secondary small">Updated By:</div>
                <div class="col-md-9 text-dark small">
                    {{ $role->updater ? $role->updater->name : 'N/A' }}
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 fw-semibold text-secondary small">Updated At:</div>
                <div class="col-md-9 text-dark small">
                    {{ $role->updated_at ? $role->updated_at->format('M d, Y H:i:s') : 'N/A' }}
                </div>
            </div>
        </div>
        <div class="card-footer bg-light border-0 d-flex gap-2">
            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary btn-sm px-4">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm px-4"
                        onclick="return confirm('Are you sure you want to delete this role?')">
                    <i class="bi bi-trash me-1"></i>Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
