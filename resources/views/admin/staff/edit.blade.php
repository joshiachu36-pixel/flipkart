@extends('layout.admin')

@section('content')
<div class="container-fluid py-3">
    {{-- Header --}}
    <div class="mb-3">
        <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i>Back to Staff
        </a>
        <h4 class="mb-1 text-dark fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Staff: {{ $staff->name }}</h4>
        <p class="text-muted small">Update staff credentials, assign roles, or change status fields.</p>
    </div>

    {{-- Form --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.staff.update', $staff) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control form-control-sm @error('name') is-invalid @enderror" 
                               placeholder="E.g., John Doe" value="{{ old('name', $staff->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control form-control-sm @error('email') is-invalid @enderror" 
                               placeholder="E.g., john@example.com" value="{{ old('email', $staff->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label fw-semibold small">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control form-control-sm @error('phone') is-invalid @enderror" 
                               placeholder="E.g., +919876543210" value="{{ old('phone', $staff->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="role_id" class="form-label fw-semibold small">Assign Role <span class="text-danger">*</span></label>
                        @php
                            $isSelf = ($staff->id === auth()->id() && auth()->getDefaultDriver() === 'staff');
                        @endphp
                        <select name="role_id" id="role_id" class="form-select form-select-sm @error('role_id') is-invalid @enderror" required {{ $isSelf ? 'disabled' : '' }}>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $staff->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @if($isSelf)
                            <input type="hidden" name="role_id" value="{{ $staff->role_id }}">
                            <div class="text-muted small mt-1"><i class="bi bi-info-circle me-1"></i>You cannot change your own role.</div>
                        @endif
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label fw-semibold small">Password</label>
                        <input type="password" name="password" id="password" class="form-control form-control-sm @error('password') is-invalid @enderror" 
                               placeholder="Leave blank to keep current password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label fw-semibold small">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-sm" 
                               placeholder="Confirm Password">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select form-select-sm @error('status') is-invalid @enderror" required {{ $isSelf ? 'disabled' : '' }}>
                            <option value="Active" {{ old('status', $staff->status) === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status', $staff->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="Suspended" {{ old('status', $staff->status) === 'Suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @if($isSelf)
                            <input type="hidden" name="status" value="{{ $staff->status }}">
                            <div class="text-muted small mt-1"><i class="bi bi-info-circle me-1"></i>You cannot change your own status.</div>
                        @endif
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="profile_photo" class="form-label fw-semibold small">Profile Photo</label>
                        <input type="file" name="profile_photo" id="profile_photo" class="form-control form-control-sm @error('profile_photo') is-invalid @enderror">
                        @if($staff->profile_photo)
                            <div class="mt-2">
                                <img src="{{ asset($staff->profile_photo) }}" alt="Avatar" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;">
                                <div class="text-muted small mt-1">Current Photo</div>
                            </div>
                        @endif
                        @error('profile_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-save me-1"></i>Update Staff
                    </button>
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-light btn-sm px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
