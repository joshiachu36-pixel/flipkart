@extends('layout.admin')

@section('content')
<div class="container-fluid py-3">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 text-dark fw-bold"><i class="bi bi-shield-lock me-2 text-primary"></i>Role Management</h4>
            <p class="text-muted small mb-0">Create and manage roles, monitor staff assignments, and update statuses.</p>
        </div>
        @if(can_do('roles.create'))
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="bi bi-plus-lg me-1"></i>Add Role
        </a>
        @endif
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Roles Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Role Name</th>
                            <th>Description</th>
                            <th>Total Staff Count</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr>
                            <td class="ps-3">
                                <span class="badge text-white px-3 py-2 fw-semibold" style="background-color: {{ $role->badge_color }}">
                                    {{ $role->name }}
                                </span>
                            </td>
                            <td>
                                <span class="text-secondary small">{{ Str::limit($role->description, 60, '...') ?: 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 fw-bold">
                                    {{ $role->staff_count }} {{ Str::plural('Staff', $role->staff_count) }}
                                </span>
                            </td>
                            <td>
                                @if($role->status === 'Active')
                                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1 small">
                                        <i class="bi bi-check-circle-fill me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1 small">
                                        <i class="bi bi-x-circle-fill me-1"></i>Inactive
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="small text-muted">{{ $role->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-inline-flex gap-1 flex-wrap justify-content-end">
                                    @if(is_super_admin())
                                        @if($role->isSuperAdmin())
                                            <span class="btn btn-sm btn-outline-secondary disabled" title="Super Admin permissions are immutable">
                                                <i class="bi bi-lock-fill me-1"></i>Protected
                                            </span>
                                        @else
                                            <a href="{{ route('admin.roles.permissions.edit', $role) }}"
                                               class="btn btn-sm btn-outline-warning fw-semibold"
                                               title="Manage Permissions">
                                                <i class="bi bi-key-fill me-1"></i>Permissions
                                            </a>
                                        @endif
                                    @endif
                                    <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(can_do('roles.edit'))
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-primary" title="Edit Role">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endif
                                    @if(!$role->isSuperAdmin() && can_do('roles.delete'))
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Role"
                                                onclick="return confirm('Are you sure you want to delete this role?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                No roles found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($roles->hasPages())
        <div class="card-footer bg-light border-0 py-2">
            {{ $roles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
