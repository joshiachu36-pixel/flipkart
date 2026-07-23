@extends('layout.admin')

@section('content')
<div class="container-fluid py-3">
    {{-- Header --}}
    <div class="mb-3">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i>Back to Roles
        </a>
        <h4 class="mb-1 text-dark fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i>Add New Role</h4>
        <p class="text-muted small">Configure a new system role for internal staff members.</p>
    </div>

    {{-- Form --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold small">Role Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control form-control-sm @error('name') is-invalid @enderror" 
                           placeholder="E.g., Manager, Finance, Support" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold small">Description</label>
                    <textarea name="description" id="description" class="form-control form-control-sm @error('description') is-invalid @enderror" 
                              rows="4" placeholder="Briefly describe the responsibilities associated with this role...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select form-select-sm @error('status') is-invalid @enderror" required>
                        <option value="Active" {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-save me-1"></i>Save Role
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-light btn-sm px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
