@extends('layout.admin')

@section('content')
<div class="container-fluid py-3">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 text-dark fw-bold"><i class="bi bi-shop me-2 text-primary"></i>Seller Management</h4>
            <p class="text-muted small mb-0">Review seller applications, manage approvals, and track verification status.</p>
        </div>
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
            <div class="row align-items-center g-3">
                {{-- Status Filters --}}
                <div class="col-lg-8">
                    <div class="nav nav-pills gap-2" id="seller-status-tabs">
                        <a href="{{ route('admin.sellers.index') }}" 
                           class="nav-link px-3 py-1-5 rounded-pill {{ !request('status') ? 'active fw-semibold' : 'bg-light text-dark' }}">
                            All <span class="badge bg-secondary ms-1">{{ $counts['all'] ?? 0 }}</span>
                        </a>
                        <a href="{{ route('admin.sellers.index', ['status' => 'Pending']) }}" 
                           class="nav-link px-3 py-1-5 rounded-pill {{ request('status') === 'Pending' ? 'active bg-warning text-dark fw-semibold' : 'bg-light text-dark' }}">
                            Pending <span class="badge bg-warning text-dark ms-1">{{ $counts['pending'] ?? 0 }}</span>
                        </a>
                        <a href="{{ route('admin.sellers.index', ['status' => 'Approved']) }}" 
                           class="nav-link px-3 py-1-5 rounded-pill {{ request('status') === 'Approved' ? 'active bg-success text-white fw-semibold' : 'bg-light text-dark' }}">
                            Approved <span class="badge bg-success ms-1">{{ $counts['approved'] ?? 0 }}</span>
                        </a>
                        <a href="{{ route('admin.sellers.index', ['status' => 'Rejected']) }}" 
                           class="nav-link px-3 py-1-5 rounded-pill {{ request('status') === 'Rejected' ? 'active bg-danger text-white fw-semibold' : 'bg-light text-dark' }}">
                            Rejected <span class="badge bg-danger ms-1">{{ $counts['rejected'] ?? 0 }}</span>
                        </a>
                        <a href="{{ route('admin.sellers.index', ['status' => 'Suspended']) }}" 
                           class="nav-link px-3 py-1-5 rounded-pill {{ request('status') === 'Suspended' ? 'active bg-secondary text-white fw-semibold' : 'bg-light text-dark' }}">
                            Suspended <span class="badge bg-dark ms-1">{{ $counts['suspended'] ?? 0 }}</span>
                        </a>
                    </div>
                </div>

                {{-- Search Box --}}
                <div class="col-lg-4">
                    <form action="{{ route('admin.sellers.index') }}" method="GET">
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" 
                                   placeholder="Search business, owner, email..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-search"></i>
                            </button>
                            @if(request('search'))
                                <a href="{{ route('admin.sellers.index', request('status') ? ['status' => request('status')] : []) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Sellers Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Seller / Business</th>
                            <th>Owner Name</th>
                            <th>Contact Info</th>
                            <th>GST / PAN</th>
                            <th>Status</th>
                            <th>Applied Date</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sellers as $seller)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    @if($seller->business_logo)
                                        <img src="{{ asset('storage/'.$seller->business_logo) }}" alt="Logo" 
                                             class="rounded border" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted fw-bold" 
                                             style="width: 40px; height: 40px; font-size: 1.1rem;">
                                            {{ strtoupper(substr($seller->business_name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold text-dark">{{ $seller->business_name }}</div>
                                        <div class="small text-muted">ID: #{{ $seller->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-secondary">{{ $seller->owner_name }}</span>
                            </td>
                            <td>
                                <div class="small"><i class="bi bi-envelope me-1 text-muted"></i>{{ $seller->email }}</div>
                                <div class="small"><i class="bi bi-telephone me-1 text-muted"></i>{{ $seller->phone }}</div>
                            </td>
                            <td>
                                <div class="small"><strong>GST:</strong> {{ $seller->gst_number ?: 'N/A' }}</div>
                                <div class="small"><strong>PAN:</strong> {{ $seller->pan_number ?: 'N/A' }}</div>
                            </td>
                            <td>
                                @if($seller->status == 'Approved')
                                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1">
                                        <i class="bi bi-check-circle-fill me-1"></i>Approved
                                    </span>
                                @elseif($seller->status == 'Pending')
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-2 py-1">
                                        <i class="bi bi-clock-history me-1"></i>Pending
                                    </span>
                                @elseif($seller->status == 'Rejected')
                                    <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1">
                                        <i class="bi bi-x-circle-fill me-1"></i>Rejected
                                    </span>
                                @elseif($seller->status == 'Suspended')
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary px-2 py-1">
                                        <i class="bi bi-slash-circle-fill me-1"></i>Suspended
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="small text-muted" title="{{ $seller->created_at }}">
                                    {{ $seller->created_at->format('M d, Y') }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.sellers.show', $seller) }}" class="btn btn-sm btn-outline-primary" title="Review Details">
                                        <i class="bi bi-eye-fill me-1"></i>Review
                                    </a>

                                    @if($seller->status !== 'Approved' && app('App\Services\PermissionService')->hasPermission('sellers.approve'))
                                        <form action="{{ route('admin.sellers.approve', $seller) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Approve Seller"
                                                    onclick="return confirm('Are you sure you want to approve this seller?')">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($seller->status === 'Pending' && app('App\Services\PermissionService')->hasPermission('sellers.reject'))
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $seller->id }}" title="Reject Seller">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @endif

                                    @if($seller->status === 'Approved' && app('App\Services\PermissionService')->hasPermission('sellers.suspend'))
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#suspendModal{{ $seller->id }}" title="Suspend Seller">
                                            <i class="bi bi-pause-circle"></i>
                                        </button>
                                    @endif

                                    @if($seller->status === 'Suspended' && app('App\Services\PermissionService')->hasPermission('sellers.approve'))
                                        <form action="{{ route('admin.sellers.restore', $seller) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Restore Seller"
                                                    onclick="return confirm('Restore this seller to Approved status?')">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                {{-- Reject Modal --}}
                                @if($seller->status === 'Pending')
                                <div class="modal fade text-start" id="rejectModal{{ $seller->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.sellers.reject', $seller) }}" method="POST">
                                                @csrf
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title fs-6"><i class="bi bi-x-circle me-2"></i>Reject Seller Application</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="small text-muted mb-2">You are rejecting <strong>{{ $seller->business_name }}</strong>. Please specify a clear rejection reason so the seller can correct their information.</p>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold small">Rejection Reason <span class="text-danger">*</span></label>
                                                        <textarea name="rejection_reason" class="form-control form-control-sm" rows="4" 
                                                                  placeholder="E.g. Invalid GST number provided, PAN card image is unclear, business address incomplete..." required minlength="10"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-danger">Confirm Rejection</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                {{-- Suspend Modal --}}
                                @if($seller->status === 'Approved')
                                <div class="modal fade text-start" id="suspendModal{{ $seller->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.sellers.suspend', $seller) }}" method="POST">
                                                @csrf
                                                <div class="modal-header bg-warning text-dark">
                                                    <h5 class="modal-title fs-6"><i class="bi bi-exclamation-triangle me-2"></i>Suspend Seller Account</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="small text-muted mb-2">Suspending <strong>{{ $seller->business_name }}</strong> will temporarily revoke their access to seller dashboard and product management features.</p>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold small">Suspension Reason (Optional)</label>
                                                        <textarea name="suspension_reason" class="form-control form-control-sm" rows="3" 
                                                                  placeholder="Reason for suspending this account..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-warning">Suspend Account</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                No sellers found matching the selected criteria.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($sellers->hasPages())
        <div class="card-footer bg-light border-0 py-2">
            {{ $sellers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
