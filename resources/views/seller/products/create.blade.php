@extends('layout.seller')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h4>Add New Product</h4>
        </div>
    </div>
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
            <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Brand is automatically set to the seller's own business --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Selling Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Original Price</label>
                        <input type="number" step="0.01" name="original_price" class="form-control" value="{{ old('original_price') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stock</label>
                        <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Product Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Save Product</button>
                    <a href="{{ route('seller.products.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
