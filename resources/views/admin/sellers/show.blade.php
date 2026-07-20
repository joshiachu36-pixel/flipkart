@extends('layout.admin')

@section('content')
<div class="container-fluid py-3" style="max-width: 1200px;">
    {{-- Breadcrumb & Back --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('admin.sellers.index') }}">Seller Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $seller->business_name }}</li>
            </ol>
        </nav>
        <a href="{{ route('admin.sellers.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Sellers
        </a>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Main Header Card --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-auto">
                    @if($seller->business_logo)
                        <img src="{{ asset('storage/'.$seller->business_logo) }}" alt="Business Logo" 
                             class="rounded-3 border shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="rounded-3 border bg-light d-flex align-items-center justify-content-center text-primary fw-bold fs-2 shadow-sm" 
                             style="width: 80px; height: 80px;">
                            {{ strtoupper(substr($seller->business_name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="col">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h3 class="fw-bold mb-0 text-dark">{{ $seller->business_name }}</h3>
                        @if($seller->status === 'Approved')
                            <span class="badge bg-success-subtle text-success border border-success px-3 py-1 rounded-pill">
                                <i class="bi bi-check-circle-fill me-1"></i>Approved
                            </span>
                        @elseif($seller->status === 'Pending')
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-3 py-1 rounded-pill">
                                <i class="bi bi-clock-history me-1"></i>Pending Review
                            </span>
                        @elseif($seller->status === 'Rejected')
                            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-1 rounded-pill">
                                <i class="bi bi-x-circle-fill me-1"></i>Rejected
                            </span>
                        @elseif($seller->status === 'Suspended')
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary px-3 py-1 rounded-pill">
                                <i class="bi bi-slash-circle-fill me-1"></i>Suspended
                            </span>
                        @endif
                    </div>
                    <div class="text-muted small">
                        <span><i class="bi bi-person-fill me-1"></i>Owner: <strong>{{ $seller->owner_name }}</strong></span>
                        <span class="mx-2">•</span>
                        <span><i class="bi bi-calendar3 me-1"></i>Registered: {{ $seller->created_at->format('M d, Y (h:i A)') }}</span>
                        <span class="mx-2">•</span>
                        <span><i class="bi bi-box-seam me-1"></i>Total Products: <strong>{{ $seller->products_count }}</strong></span>
                    </div>
                </div>

                {{-- Action Bar --}}
                <div class="col-12 col-md-auto">
                    <div class="d-flex flex-wrap gap-2">
                        @if($seller->status !== 'Approved')
                            <form action="{{ route('admin.sellers.approve', $seller) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirm('Approve this seller application?')">
                                    <i class="bi bi-check-lg me-1"></i>Approve
                                </button>
                            </form>
                        @endif

                        @if($seller->status === 'Pending')
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-lg me-1"></i>Reject Application
                            </button>
                        @endif

                        @if($seller->status === 'Approved')
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#suspendModal">
                                <i class="bi bi-pause-circle me-1"></i>Suspend Account
                            </button>
                        @endif

                        @if($seller->status === 'Suspended')
                            <form action="{{ route('admin.sellers.restore', $seller) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-success" onclick="return confirm('Restore this seller account?')">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Restore Account
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Rejection / Suspension Notice if applicable --}}
    @if($seller->status === 'Rejected' && $seller->rejection_reason)
        <div class="card border-danger mb-4 shadow-sm">
            <div class="card-header bg-danger text-white fw-bold">
                <i class="bi bi-exclamation-octagon-fill me-2"></i>Rejection History & Reason
            </div>
            <div class="card-body text-danger-emphasis">
                <p class="mb-1"><strong>Reason:</strong> {{ $seller->rejection_reason }}</p>
                @if($seller->rejected_at)
                    <small class="text-muted"><i class="bi bi-clock me-1"></i>Rejected on {{ $seller->rejected_at->format('M d, Y h:i A') }}</small>
                @endif
            </div>
        </div>
    @endif

    @if($seller->status === 'Suspended' && $seller->suspension_reason)
        <div class="card border-secondary mb-4 shadow-sm">
            <div class="card-header bg-secondary text-white fw-bold">
                <i class="bi bi-pause-circle-fill me-2"></i>Suspension History & Reason
            </div>
            <div class="card-body text-secondary-emphasis">
                <p class="mb-1"><strong>Reason:</strong> {{ $seller->suspension_reason }}</p>
                @if($seller->suspended_at)
                    <small class="text-muted"><i class="bi bi-clock me-1"></i>Suspended on {{ $seller->suspended_at->format('M d, Y h:i A') }}</small>
                @endif
            </div>
        </div>
    @endif

    {{-- Details Sections --}}
    <div class="row g-4">
        {{-- Business & Contact Info --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light fw-bold text-dark border-0">
                    <i class="bi bi-building me-2 text-primary"></i>Business & Contact Details
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <th style="width: 35%;" class="text-muted fw-normal">Business Name:</th>
                            <td class="fw-semibold">{{ $seller->business_name }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Owner Name:</th>
                            <td class="fw-semibold">{{ $seller->owner_name }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Email Address:</th>
                            <td><a href="mailto:{{ $seller->email }}" class="text-decoration-none">{{ $seller->email }}</a></td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Phone Number:</th>
                            <td><a href="tel:{{ $seller->phone }}" class="text-decoration-none">{{ $seller->phone }}</a></td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Business Address:</th>
                            <td class="text-wrap">{{ $seller->business_address }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Legal Documents & Tax Details --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light fw-bold text-dark border-0">
                    <i class="bi bi-file-earmark-text me-2 text-primary"></i>Tax & Legal Verification
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <th style="width: 35%;" class="text-muted fw-normal">GST Number:</th>
                            <td>
                                @if($seller->gst_number)
                                    <span class="badge bg-light text-dark border fw-bold px-2 py-1">{{ $seller->gst_number }}</span>
                                @else
                                    <span class="text-muted italic">Not Provided</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">PAN Number:</th>
                            <td>
                                @if($seller->pan_number)
                                    <span class="badge bg-light text-dark border fw-bold px-2 py-1">{{ $seller->pan_number }}</span>
                                @else
                                    <span class="text-muted italic">Not Provided</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Business Logo:</th>
                            <td>
                                @if($seller->business_logo)
                                    <a href="{{ asset('storage/'.$seller->business_logo) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-card-image me-1"></i>View Uploaded Logo
                                    </a>
                                @else
                                    <span class="text-muted italic">No logo uploaded</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Banking Information --}}
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-bold text-dark border-0">
                    <i class="bi bi-bank me-2 text-primary"></i>Banking & Settlement Details
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <div class="small text-muted mb-1">Bank Name</div>
                                <div class="fw-bold">{{ $seller->bank_name ?: 'Not Provided' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <div class="small text-muted mb-1">Account Number</div>
                                <div class="fw-bold fs-6 font-monospace">{{ $seller->bank_account_number ?: 'Not Provided' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <div class="small text-muted mb-1">IFSC Code</div>
                                <div class="fw-bold font-monospace">{{ $seller->ifsc_code ?: 'Not Provided' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.sellers.reject', $seller) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fs-6"><i class="bi bi-x-circle me-2"></i>Reject Seller Application</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-2">Please state the rejection reason clearly. The seller will see this exact message when logging into their dashboard and can update their details accordingly.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="4" 
                                  placeholder="E.g. GST number is invalid. Business logo is unclear. Please provide valid documents." required minlength="10"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Suspend Modal --}}
<div class="modal fade" id="suspendModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.sellers.suspend', $seller) }}" method="POST">
                @csrf
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fs-6"><i class="bi bi-exclamation-triangle me-2"></i>Suspend Seller Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-2">Suspending this seller will restrict access to their seller dashboard and halt product management functionality until restored.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Suspension Reason (Optional)</label>
                        <textarea name="suspension_reason" class="form-control" rows="3" 
                                  placeholder="Reason for suspension..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm">Suspend Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
