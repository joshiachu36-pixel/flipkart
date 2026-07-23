@extends('layout.admin')

@section('content')
<div class="container-fluid py-3">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 text-dark fw-bold"><i class="bi bi-people me-2 text-primary"></i>Staff Management</h4>
            <p class="text-muted small mb-0">Create new staff, assign roles, monitor access status, and check login analytics.</p>
        </div>
        @if(can_do('staff.create'))
        <a href="{{ route('admin.staff.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i>Add Staff
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

    {{-- Filter Nav & Search Bar --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body py-3">
            <form action="{{ route('admin.staff.index') }}" method="GET" class="row align-items-center g-2">
                {{-- Role Filter --}}
                <div class="col-md-2">
                    <select name="role_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Filter --}}
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Suspended" {{ request('status') === 'Suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>

                {{-- Date Filter --}}
                <div class="col-md-2">
                    <input type="date" name="created_date" class="form-control form-control-sm" 
                           value="{{ request('created_date') }}" onchange="this.form.submit()" placeholder="Created Date">
                </div>

                {{-- Sorting --}}
                <div class="col-md-2">
                    <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="a-z" {{ request('sort') === 'a-z' ? 'selected' : '' }}>A-Z</option>
                        <option value="role" {{ request('sort') === 'role' ? 'selected' : '' }}>By Role</option>
                    </select>
                </div>

                {{-- Per Page --}}
                <div class="col-md-1 col-sm-2">
                    <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                {{-- Search Box --}}
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search name, email, phone..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                        @if(request('search') || request('role_id') || request('status') || request('created_date') || request('sort') || request('per_page'))
                            <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Staff Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Profile</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Created Date</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staffMembers as $member)
                        <tr>
                            <td class="ps-3">
                                @if($member->profile_photo)
                                    <img src="{{ asset($member->profile_photo) }}" alt="Avatar" 
                                         class="rounded-circle border shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle border bg-light d-flex align-items-center justify-content-center text-muted fw-bold shadow-sm" 
                                         style="width: 40px; height: 40px; font-size: 1.1rem;">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $member->name }}</span>
                            </td>
                            <td>
                                <span class="small text-secondary">{{ $member->email }}</span>
                            </td>
                            <td>
                                <span class="small text-secondary">{{ $member->phone ?: 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge text-white px-2.5 py-1.5 fw-semibold small" style="background-color: {{ $member->role ? $member->role->badge_color : '#6c757d' }}">
                                    {{ $member->role ? $member->role->name : 'N/A' }}
                                </span>
                            </td>
                            <td>
                                @if($member->status === 'Active')
                                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1 small">
                                        <i class="bi bi-check-circle-fill me-1"></i>Active
                                    </span>
                                @elseif($member->status === 'Inactive')
                                    <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1 small">
                                        <i class="bi bi-x-circle-fill me-1"></i>Inactive
                                    </span>
                                @elseif($member->status === 'Suspended')
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-2 py-1 small">
                                        <i class="bi bi-exclamation-octagon-fill me-1"></i>Suspended
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($member->last_login_at)
                                    <div class="small fw-semibold text-dark">{{ $member->last_login_at->diffForHumans() }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Logins: {{ $member->login_count }}</div>
                                @else
                                    <span class="text-muted small">Never logged in</span>
                                @endif
                            </td>
                            <td>
                                <span class="small text-muted">{{ $member->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.staff.show', $member) }}" class="btn btn-sm btn-outline-info" title="View Profile">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(can_do('staff.edit'))
                                    <a href="{{ route('admin.staff.edit', $member) }}" class="btn btn-sm btn-outline-primary" title="Edit Staff">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endif
                                    @if(can_do('staff.delete'))
                                    <form action="{{ route('admin.staff.destroy', $member) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Staff"
                                                onclick="return confirm('Are you sure you want to delete this staff member?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="bi bi-people fs-3 d-block mb-1"></i>
                                No staff members found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($staffMembers->hasPages())
        <div class="card-footer bg-light border-0 py-2">
            {{ $staffMembers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
