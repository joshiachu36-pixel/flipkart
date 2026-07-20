@extends('layout.seller')

@section('content')
<div class="container-fluid py-3" style="max-width: 850px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold mb-1">Add New Product</h2>
            <p class="text-muted mb-0">Enter common details for your product. Prices, stock, images, colors, and sizes are configured in the next step.</p>
        </div>
        <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Products
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Slim Fit Cotton Shirt" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Detailed product description..." required>{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="alert alert-info border-info-subtle mt-4 d-flex align-items-center gap-3">
                    <i class="bi bi-info-circle-fill fs-4 text-info flex-shrink-0"></i>
                    <div>
                        <strong>Product Variants Step:</strong> After clicking <strong>Save & Next</strong>, you will be taken directly to the <strong>Manage Variants</strong> page to add your colors, sizes, prices, priority, stock, and variant images.
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('seller.products.index') }}" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-arrow-right-circle me-1"></i> Save & Manage Variants</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
