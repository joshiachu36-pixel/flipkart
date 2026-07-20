@extends('layout.seller')

@section('content')
<div class="container-fluid py-3" style="max-width: 650px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold mb-1">Edit Color</h2>
            <p class="text-muted mb-0">Update your custom color details.</p>
        </div>
        <a href="{{ route('seller.colors.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Colors
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('seller.colors.update', $color) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Color Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $color->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Color Code (HEX) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="color" id="colorPicker" class="form-control form-control-color" value="{{ old('code', $color->code) }}" title="Choose color" onchange="document.getElementById('codeText').value = this.value">
                        <input type="text" id="codeText" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $color->code) }}" required onchange="document.getElementById('colorPicker').value = this.value">
                    </div>
                    @error('code')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" {{ old('status', $color->status) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $color->status) == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('seller.colors.index') }}" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Update Color</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
