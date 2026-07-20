@extends('layout.seller')

@section('content')
<div class="container-fluid">

    {{-- ── Rejection Reason Banner ── --}}
    @if($product->approval_status === 'Rejected')
    <div style="
        background: linear-gradient(135deg, #fef2f2, #fff5f5);
        border: 1.5px solid #fca5a5;
        border-left: 5px solid #dc2626;
        border-radius: 10px;
        padding: 18px 22px;
        margin-bottom: 22px;
        display: flex; align-items: flex-start; gap: 14px;
    ">
        <div style="flex-shrink:0;">
            <span style="background:#dc2626;color:#fff;width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">
                <i class="bi bi-x-circle-fill"></i>
            </span>
        </div>
        <div style="flex:1;">
            <p style="font-weight:800;color:#991b1b;font-size:.95rem;margin:0 0 6px;display:flex;align-items:center;gap:6px;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Product Rejected — Admin Feedback
            </p>
            <div style="background:#fff;border:1px solid #fecaca;border-radius:7px;padding:12px 16px;color:#7f1d1d;font-size:.88rem;line-height:1.65;white-space:pre-wrap;word-break:break-word;">{{ $product->rejection_reason }}</div>
            @if($product->rejected_at)
                <p style="font-size:.75rem;color:#dc2626;margin:8px 0 0;">
                    <i class="bi bi-clock me-1"></i>Rejected on {{ $product->rejected_at->format('d M Y, h:i A') }}
                </p>
            @endif
        </div>
    </div>
    @endif

    {{-- ── Re-upload Notice ── --}}
    <div style="
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-left: 4px solid #2563eb;
        border-radius: 8px;
        padding: 12px 18px;
        margin-bottom: 20px;
        display: flex; align-items: center; gap: 10px;
        font-size: .85rem; color: #1e40af;
    ">
        <i class="bi bi-info-circle-fill" style="font-size:1.1rem;flex-shrink:0;"></i>
        <div>
            <strong>Re-upload Workflow:</strong>
            Update the product details below to address the rejection reason, then click
            <strong>Submit for Re-review</strong>. Your product will automatically return to
            <strong>Pending Review</strong> status for admin approval.
        </div>
    </div>

    {{-- ── Page Header ── --}}
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 style="font-weight:700;color:#1e293b;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-arrow-up-circle-fill" style="color:#d97706;"></i>
                Re-upload Product: {{ $product->name }}
            </h4>
        </div>
    </div>

    {{-- ── Form ── --}}
    <div class="card shadow-sm">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('seller.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Product Name</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $product->name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Selling Price</label>
                        <input type="number" step="0.01" name="price" class="form-control"
                               value="{{ old('price', $product->price) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Original Price <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="number" step="0.01" name="original_price" class="form-control"
                               value="{{ old('original_price', $product->original_price) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Stock</label>
                        <input type="number" name="stock" class="form-control"
                               value="{{ old('stock', $product->stock) }}" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Product Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @if($product->image)
                            <div class="mt-2 d-flex align-items-center gap-3">
                                <img src="{{ asset('storage/'.$product->image) }}"
                                     width="80" class="rounded border">
                                <small class="text-muted">Current image — upload a new one to replace it.</small>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="5" required>{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-warning fw-bold" style="display:inline-flex;align-items:center;gap:7px;">
                        <i class="bi bi-arrow-up-circle-fill"></i> Submit for Re-review
                    </button>
                    <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary" style="display:inline-flex;align-items:center;gap:6px;">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
