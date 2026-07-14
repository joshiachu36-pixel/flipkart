@extends('layout.admin')

@section('content')
<div class="container-fluid py-3">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-patch-check-fill text-primary me-2"></i>Product Approval
            </h4>
            <small class="text-muted">Review and approve seller-submitted products before they go live.</small>
        </div>
    </div>

    {{-- Flash Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Status Filter Tabs --}}
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('admin.products.index') }}"
           class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-secondary' }}">
            All <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span>
        </a>
        <a href="{{ route('admin.products.index', ['status' => 'Pending', 'search' => request('search')]) }}"
           class="btn btn-sm {{ request('status') === 'Pending' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
            Pending <span class="badge bg-dark ms-1">{{ $counts['pending'] }}</span>
        </a>
        <a href="{{ route('admin.products.index', ['status' => 'Approved', 'search' => request('search')]) }}"
           class="btn btn-sm {{ request('status') === 'Approved' ? 'btn-success' : 'btn-outline-success' }}">
            Approved <span class="badge bg-dark ms-1">{{ $counts['approved'] }}</span>
        </a>
        <a href="{{ route('admin.products.index', ['status' => 'Rejected', 'search' => request('search')]) }}"
           class="btn btn-sm {{ request('status') === 'Rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
            Rejected <span class="badge bg-dark ms-1">{{ $counts['rejected'] }}</span>
        </a>
    </div>

    {{-- Search Bar --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('admin.products.index') }}" class="d-flex gap-2 align-items-center">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="text"
                       name="search"
                       class="form-control form-control-sm"
                       placeholder="Search by product name, seller name, or business name…"
                       value="{{ request('search') }}"
                       style="max-width: 420px;">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-search"></i> Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.products.index', array_filter(['status' => request('status')])) }}"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- Products Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width:70px;">Image</th>
                            <th>Product Name</th>
                            <th>Business Name</th>
                            <th>Seller Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th style="width:160px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            {{-- Image --}}
                            <td class="text-center">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="rounded"
                                         style="width:54px;height:54px;object-fit:cover;">
                                @elseif($product->image && !str_starts_with($product->image, 'product'))
                                    <img src="{{ asset('uploads/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="rounded"
                                         style="width:54px;height:54px;object-fit:cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="width:54px;height:54px;">
                                        <i class="bi bi-image text-muted fs-5"></i>
                                    </div>
                                @endif
                            </td>

                            {{-- Product Name --}}
                            <td>
                                <span class="fw-semibold">{{ $product->name }}</span>
                            </td>

                            {{-- Business Name --}}
                            <td>{{ $product->seller->business_name ?? '—' }}</td>

                            {{-- Seller Owner Name --}}
                            <td>{{ $product->seller->owner_name ?? '—' }}</td>

                            {{-- Category --}}
                            <td>{{ $product->category->name ?? '—' }}</td>

                            {{-- Price --}}
                            <td>₹{{ number_format($product->price, 2) }}</td>

                            {{-- Stock --}}
                            <td>{{ $product->stock }}</td>

                            {{-- Submitted Date --}}
                            <td>
                                <span title="{{ $product->created_at->format('d M Y, h:i A') }}">
                                    {{ $product->created_at->format('d M Y') }}
                                </span>
                            </td>

                            {{-- Approval Status Badge --}}
                            <td>
                                @if($product->approval_status === 'Approved')
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>Approved
                                    </span>
                                    @if($product->approved_at)
                                        <br><small class="text-muted">{{ $product->approved_at->format('d M Y') }}</small>
                                    @endif
                                @elseif($product->approval_status === 'Rejected')
                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="bi bi-x-circle-fill me-1"></i>Rejected
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        <i class="bi bi-hourglass-split me-1"></i>Pending
                                    </span>
                                @endif
                            </td>

                            {{-- Action Dropdown --}}
                            <td>
                                <form action="{{ route('admin.products.update', $product) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    <select name="approval_status"
                                            class="form-select form-select-sm d-inline w-auto"
                                            onchange="this.form.submit()"
                                            style="min-width:120px;">
                                        <option value="Pending"
                                                {{ $product->approval_status === 'Pending'  ? 'selected' : '' }}>
                                            ⏳ Pending
                                        </option>
                                        <option value="Approved"
                                                {{ $product->approval_status === 'Approved' ? 'selected' : '' }}>
                                            ✅ Approve
                                        </option>
                                        <option value="Rejected"
                                                {{ $product->approval_status === 'Rejected' ? 'selected' : '' }}>
                                            ❌ Reject
                                        </option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No products found matching your criteria.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
        <div class="card-footer bg-white">
            {{ $products->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
