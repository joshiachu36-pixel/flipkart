@extends('layout.admin')

@section('content')
<div class="container-fluid py-3" style="max-width: 1250px;">
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
                        <span><i class="bi bi-calendar3 me-1"></i>Registered: {{ $seller->created_at ? $seller->created_at->format('M d, Y (h:i A)') : 'N/A' }}</span>
                        <span class="mx-2">•</span>
                        <span><i class="bi bi-box-seam me-1"></i>Total Products: <strong>{{ $stats['total_products'] }}</strong></span>
                    </div>
                </div>

                {{-- Action Bar --}}
                <div class="col-12 col-md-auto">
                    <div class="d-flex flex-wrap gap-2">
                        @if($seller->status !== 'Approved' && can_do('sellers.approve'))
                            <form action="{{ route('admin.sellers.approve', $seller) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirm('Approve this seller application?')">
                                    <i class="bi bi-check-lg me-1"></i>Approve
                                </button>
                            </form>
                        @endif

                        @if($seller->status === 'Pending' && can_do('sellers.reject'))
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-lg me-1"></i>Reject Application
                            </button>
                        @endif

                        @if($seller->status === 'Approved' && can_do('sellers.suspend'))
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#suspendModal">
                                <i class="bi bi-pause-circle me-1"></i>Suspend Account
                            </button>
                        @endif

                        @if($seller->status === 'Suspended' && can_do('sellers.approve'))
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

    {{-- ════════════════════════════════════════════════
         BOOTSTRAP TABS NAVIGATION
    ════════════════════════════════════════════════ --}}
    <ul class="nav nav-tabs nav-fill mb-4 bg-white rounded shadow-sm p-2 border-0" id="sellerDetailTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview-pane" type="button" role="tab">
                <i class="bi bi-grid-fill me-1"></i> Overview
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="products-tab" data-bs-toggle="tab" data-bs-target="#products-pane" type="button" role="tab">
                <i class="bi bi-box-seam me-1"></i> Products ({{ $stats['total_products'] }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="variants-tab" data-bs-toggle="tab" data-bs-target="#variants-pane" type="button" role="tab">
                <i class="bi bi-layers me-1"></i> Variants ({{ $stats['total_variants'] }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="colors-tab" data-bs-toggle="tab" data-bs-target="#colors-pane" type="button" role="tab">
                <i class="bi bi-palette me-1"></i> Colors ({{ $stats['total_colors'] }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="sizes-tab" data-bs-toggle="tab" data-bs-target="#sizes-pane" type="button" role="tab">
                <i class="bi bi-ruler me-1"></i> Sizes ({{ $stats['total_sizes'] }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports-pane" type="button" role="tab">
                <i class="bi bi-bar-chart-fill me-1"></i> Reports
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents-pane" type="button" role="tab">
                <i class="bi bi-file-earmark-text me-1"></i> Documents
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity-pane" type="button" role="tab">
                <i class="bi bi-clock-history me-1"></i> Activity Log
            </button>
        </li>
    </ul>

    {{-- ════════════════════════════════════════════════
         TAB CONTENT PANES
    ════════════════════════════════════════════════ --}}
    <div class="tab-content" id="sellerDetailTabsContent">

        {{-- ── TAB 1: OVERVIEW ── --}}
        <div class="tab-pane fade show active" id="overview-pane" role="tabpanel">
            {{-- Statistics Summary Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4 col-lg-2">
                    <div class="card shadow-sm border-0 text-center p-3 bg-white">
                        <div class="fs-4 fw-bold text-primary">{{ $stats['total_products'] }}</div>
                        <div class="small text-muted fw-semibold">Products</div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card shadow-sm border-0 text-center p-3 bg-white">
                        <div class="fs-4 fw-bold text-info">{{ $stats['total_variants'] }}</div>
                        <div class="small text-muted fw-semibold">Variants</div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card shadow-sm border-0 text-center p-3 bg-white">
                        <div class="fs-4 fw-bold text-purple" style="color: #8b5cf6;">{{ $stats['total_colors'] }}</div>
                        <div class="small text-muted fw-semibold">Colors</div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card shadow-sm border-0 text-center p-3 bg-white">
                        <div class="fs-4 fw-bold text-teal" style="color: #0d9488;">{{ $stats['total_sizes'] }}</div>
                        <div class="small text-muted fw-semibold">Sizes</div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card shadow-sm border-0 text-center p-3 bg-white">
                        <div class="fs-4 fw-bold text-warning">{{ $stats['total_orders'] }}</div>
                        <div class="small text-muted fw-semibold">Orders</div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card shadow-sm border-0 text-center p-3 bg-white">
                        <div class="fs-4 fw-bold text-success">₹{{ number_format($stats['total_revenue'], 2) }}</div>
                        <div class="small text-muted fw-semibold">Revenue</div>
                    </div>
                </div>
            </div>

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

        {{-- ── TAB 2: PRODUCTS ── --}}
        <div class="tab-pane fade" id="products-pane" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold py-3 border-0">
                    <i class="bi bi-box-seam me-2 text-primary"></i>Seller Products ({{ $products->count() }})
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Variant Count</th>
                                    <th>Created Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $prod)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $prod->effective_image_url }}" class="rounded border shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                                <span class="fw-bold text-dark">{{ $prod->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $prod->category->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($prod->approval_status === 'Approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($prod->approval_status === 'Pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1">{{ $prod->variants->count() }} Variants</span></td>
                                        <td class="small text-muted">{{ $prod->created_at ? $prod->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.products.show', $prod) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-eye me-1"></i>Review</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No products created by this seller yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TAB 3: VARIANTS ── --}}
        <div class="tab-pane fade" id="variants-pane" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold py-3 border-0">
                    <i class="bi bi-layers me-2 text-primary"></i>Seller Product Variants ({{ $variants->count() }})
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Priority</th>
                                    <th>Product</th>
                                    <th>Color</th>
                                    <th>Size(s)</th>
                                    <th>Selling Price</th>
                                    <th>Original Price</th>
                                    <th>Stock</th>
                                    <th>SKU</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($variants as $var)
                                    <tr>
                                        <td><span class="badge bg-light text-dark border font-monospace">Priority {{ $var->priority }}</span></td>
                                        <td class="fw-bold text-dark">{{ $var->product->name ?? 'N/A' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div style="width:16px;height:16px;background-color:{{ $var->color->code ?? '#000' }};border-radius:50%;border:1px solid #ccc;"></div>
                                                <span>{{ $var->color->name ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($var->sizes->count())
                                                @foreach($var->sizes as $s)
                                                    <span class="badge bg-secondary me-1">{{ $s->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted small">No size specified</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-success">₹{{ number_format($var->price, 2) }}</td>
                                        <td class="text-muted">₹{{ number_format($var->original_price, 2) }}</td>
                                        <td><span class="fw-semibold">{{ $var->stock }}</span></td>
                                        <td><code class="text-dark fs-6">{{ $var->sku ?: 'N/A' }}</code></td>
                                        <td>
                                            @if($var->status)
                                                <span class="badge bg-success-subtle text-success border border-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">No product variants created by this seller yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TAB 4: COLORS ── --}}
        <div class="tab-pane fade" id="colors-pane" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold py-3 border-0">
                    <i class="bi bi-palette me-2 text-primary"></i>Seller Color Attributes ({{ $colors->count() }})
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="10%">Preview</th>
                                    <th>Color Name</th>
                                    <th>HEX Code</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($colors as $color)
                                    <tr>
                                        <td>
                                            <div style="width: 26px; height: 26px; background-color: {{ $color->code }}; border-radius: 50%; border: 2px solid #ddd;"></div>
                                        </td>
                                        <td class="fw-bold text-dark">{{ $color->name }}</td>
                                        <td><code>{{ $color->code }}</code></td>
                                        <td>
                                            @if($color->status)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">{{ $color->created_at ? $color->created_at->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No custom colors created by this seller.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TAB 5: SIZES ── --}}
        <div class="tab-pane fade" id="sizes-pane" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold py-3 border-0">
                    <i class="bi bi-ruler me-2 text-primary"></i>Seller Size Attributes ({{ $sizes->count() }})
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Size Name</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sizes as $size)
                                    <tr>
                                        <td class="fw-bold text-dark">{{ $size->name }}</td>
                                        <td>
                                            @if($size->status)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">{{ $size->created_at ? $size->created_at->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">No custom sizes created by this seller.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TAB 6: REPORTS ── --}}
        <div class="tab-pane fade" id="reports-pane" role="tabpanel">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light fw-bold">Product Catalog Performance</div>
                        <div class="card-body">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <th class="text-muted fw-normal">Total Products:</th>
                                    <td class="fw-bold">{{ $stats['total_products'] }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal">Approved Products:</th>
                                    <td class="text-success fw-bold">{{ $stats['approved_products'] }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal">Pending Approval:</th>
                                    <td class="text-warning fw-bold">{{ $stats['pending_products'] }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal">Rejected Products:</th>
                                    <td class="text-danger fw-bold">{{ $stats['rejected_products'] }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal">Total Variants:</th>
                                    <td class="fw-bold">{{ $stats['total_variants'] }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light fw-bold">Inventory & Sales Summary</div>
                        <div class="card-body">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <th class="text-muted fw-normal">Total Revenue:</th>
                                    <td class="text-success fw-bold fs-5">₹{{ number_format($stats['total_revenue'], 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal">Total Orders:</th>
                                    <td class="fw-bold">{{ $stats['total_orders'] }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal">Best Selling Product:</th>
                                    <td class="fw-bold text-primary">{{ $stats['best_selling_product'] }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal">Low Stock Products:</th>
                                    <td class="text-warning fw-bold">{{ $stats['low_stock_count'] }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal">Out of Stock Products:</th>
                                    <td class="text-danger fw-bold">{{ $stats['out_of_stock_count'] }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TAB 7: DOCUMENTS ── --}}
        <div class="tab-pane fade" id="documents-pane" role="tabpanel">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold py-3 border-0 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-file-earmark-text me-2 text-primary"></i>Verification Documents</span>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocModal">
                        <i class="bi bi-upload me-1"></i> Upload Additional Document
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Document Type</th>
                                    <th>Document Name</th>
                                    <th>Status</th>
                                    <th>Upload Date</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Core Verification Docs --}}
                                @if($seller->gst_number)
                                    <tr>
                                        <td class="fw-semibold">GST Registration Certificate</td>
                                        <td><code>{{ $seller->gst_number }}</code></td>
                                        <td><span class="badge bg-success">Verified</span></td>
                                        <td class="small text-muted">{{ $seller->created_at->format('M d, Y') }}</td>
                                        <td class="text-end"><span class="text-muted small">Registered Info</span></td>
                                    </tr>
                                @endif
                                @if($seller->pan_number)
                                    <tr>
                                        <td class="fw-semibold">PAN Card Verification</td>
                                        <td><code>{{ $seller->pan_number }}</code></td>
                                        <td><span class="badge bg-success">Verified</span></td>
                                        <td class="small text-muted">{{ $seller->created_at->format('M d, Y') }}</td>
                                        <td class="text-end"><span class="text-muted small">Registered Info</span></td>
                                    </tr>
                                @endif
                                @if($seller->business_logo)
                                    <tr>
                                        <td class="fw-semibold">Business Logo</td>
                                        <td>Store Brand Logo</td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td class="small text-muted">{{ $seller->created_at->format('M d, Y') }}</td>
                                        <td class="text-end">
                                            <a href="{{ asset('storage/'.$seller->business_logo) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye me-1"></i>View
                                            </a>
                                        </td>
                                    </tr>
                                @endif

                                {{-- Additional Documents --}}
                                @foreach($documents as $doc)
                                    <tr>
                                        <td class="fw-semibold">{{ $doc->document_type }}</td>
                                        <td>{{ $doc->document_name }}</td>
                                        <td><span class="badge bg-success">{{ $doc->status }}</span></td>
                                        <td class="small text-muted">{{ $doc->created_at->format('M d, Y') }}</td>
                                        <td class="text-end">
                                            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-download me-1"></i>Download
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TAB 8: ACTIVITY LOG ── --}}
        <div class="tab-pane fade" id="activity-pane" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold py-3 border-0">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Seller Activity Audit Trail
                </div>
                <div class="card-body">
                    <div class="timeline p-2">
                        @forelse($activities as $act)
                            <div class="d-flex gap-3 mb-3 border-bottom pb-3">
                                <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;">
                                    <i class="bi bi-journal-text fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $act->action }}</div>
                                    <div class="text-secondary small mt-1">{{ $act->description }}</div>
                                    <div class="text-muted small mt-1">
                                        <i class="bi bi-clock me-1"></i>{{ $act->created_at->format('M d, Y h:i A') }}
                                        @if($act->ip_address)
                                            <span class="mx-2">•</span>IP: {{ $act->ip_address }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">No activity history recorded yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Document Upload Modal --}}
<div class="modal fade" id="uploadDocModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.sellers.documents.store', $seller) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fs-6"><i class="bi bi-file-earmark-arrow-up me-2"></i>Upload Additional Verification Document</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Document Type <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-select" required>
                            <option value="Business License">Business License</option>
                            <option value="Address Proof">Address Proof</option>
                            <option value="Verification Document">Verification Document</option>
                            <option value="Tax Certificate">Tax Certificate</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Document Name / Title <span class="text-danger">*</span></label>
                        <input type="text" name="document_name" class="form-control" placeholder="e.g. Trade License 2026" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">File Upload (PDF / Image) <span class="text-danger">*</span></label>
                        <input type="file" name="document_file" class="form-control" accept="image/*,.pdf" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Notes / Internal Remarks</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Upload Document</button>
                </div>
            </form>
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
                    <p class="small text-muted mb-2">Please state the rejection reason clearly.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="4" 
                                  placeholder="E.g. GST number is invalid. Business logo is unclear." required minlength="10"></textarea>
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
                    <p class="small text-muted mb-2">Suspending this seller will restrict access to their seller dashboard.</p>
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
