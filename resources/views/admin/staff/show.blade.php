@extends('layout.admin')

@section('content')
<div class="container-fluid py-3">
    {{-- Header --}}
    <div class="mb-3">
        <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i>Back to Staff
        </a>
        <h4 class="mb-1 text-dark fw-bold"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Staff Profile: {{ $staff->name }}</h4>
        <p class="text-muted small">View full details and activity records of this staff member.</p>
    </div>

    {{-- Profile Details --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center py-4 mb-4">
                <div class="card-body">
                    @if($staff->profile_photo)
                        <img src="{{ asset($staff->profile_photo) }}" alt="Avatar" 
                             class="rounded-circle border shadow-sm img-thumbnail mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="rounded-circle border bg-light d-flex align-items-center justify-content-center text-muted fw-bold shadow-sm mx-auto mb-3" 
                             style="width: 120px; height: 120px; font-size: 3rem;">
                            {{ strtoupper(substr($staff->name, 0, 1)) }}
                        </div>
                    @endif
                    <h5 class="fw-bold text-dark mb-1">{{ $staff->name }}</h5>
                    <span class="badge text-white px-3 py-1.5 fw-semibold small mb-3" style="background-color: {{ $staff->role ? $staff->role->badge_color : '#6c757d' }}">
                        {{ $staff->role ? $staff->role->name : 'No Role Assigned' }}
                    </span>

                    <div class="border-top pt-3">
                        @if($staff->status === 'Active')
                            <span class="badge bg-success-subtle text-success border border-success px-3 py-1.5 fw-semibold">
                                <i class="bi bi-check-circle-fill me-1"></i>Active
                            </span>
                        @elseif($staff->status === 'Inactive')
                            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-1.5 fw-semibold">
                                <i class="bi bi-x-circle-fill me-1"></i>Inactive
                            </span>
                        @elseif($staff->status === 'Suspended')
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-3 py-1.5 fw-semibold">
                                <i class="bi bi-exclamation-octagon-fill me-1"></i>Suspended
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title fw-bold text-dark mb-0">Detailed Information</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0 align-middle">
                        <tbody>
                            <tr>
                                <th class="ps-3 text-secondary small" style="width: 30%;">Full Name</th>
                                <td class="text-dark fw-semibold">{{ $staff->name }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3 text-secondary small">Email Address</th>
                                <td class="text-dark">{{ $staff->email }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3 text-secondary small">Phone Number</th>
                                <td class="text-dark">{{ $staff->phone ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3 text-secondary small">Assigned Role</th>
                                <td class="text-dark">{{ $staff->role ? $staff->role->name : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3 text-secondary small">Last Login Time</th>
                                <td class="text-dark">
                                    {{ $staff->last_login_at ? $staff->last_login_at->format('M d, Y H:i:s') . ' (' . $staff->last_login_at->diffForHumans() . ')' : 'Never logged in' }}
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-3 text-secondary small">Login Count</th>
                                <td class="text-dark">{{ $staff->login_count }} times</td>
                            </tr>
                            <tr>
                                <th class="ps-3 text-secondary small">Created By</th>
                                <td class="text-dark">{{ $staff->creator ? $staff->creator->name : 'System' }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3 text-secondary small">Created Date</th>
                                <td class="text-dark">{{ $staff->created_at ? $staff->created_at->format('M d, Y H:i:s') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3 text-secondary small">Updated By</th>
                                <td class="text-dark">{{ $staff->updater ? $staff->updater->name : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3 text-secondary small">Last Updated Date</th>
                                <td class="text-dark">{{ $staff->updated_at ? $staff->updated_at->format('M d, Y H:i:s') : 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light border-0 d-flex gap-2">
                    <a href="{{ route('admin.staff.edit', $staff) }}" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-pencil me-1"></i>Edit Profile
                    </a>
                    <form action="{{ route('admin.staff.destroy', $staff) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm px-4"
                                onclick="return confirm('Are you sure you want to delete this staff member?')">
                            <i class="bi bi-trash me-1"></i>Delete Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
