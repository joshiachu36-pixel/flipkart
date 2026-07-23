@extends('layout.admin')

@section('content')
<div class="container-fluid py-3">

    {{-- ── Page Header ──────────────────────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                    <li class="breadcrumb-item active">Permissions</li>
                </ol>
            </nav>
            <h4 class="mb-1 text-dark fw-bold">
                <i class="bi bi-key-fill me-2 text-warning"></i>
                Permission Management
            </h4>
            <p class="text-muted small mb-0">
                Configure what the
                <span class="badge text-white px-2" style="background-color: {{ $role->badge_color }}">{{ $role->name }}</span>
                role can access. Changes take effect immediately.
            </p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Roles
        </a>
    </div>

    {{-- ── Super Admin Lock Banner ─────────────────────────────────────────── --}}
    @if($isSuperAdminRole)
    <div class="alert alert-warning d-flex align-items-center gap-3 shadow-sm border-0 rounded-3 mb-4" role="alert">
        <i class="bi bi-shield-lock-fill fs-3 text-warning"></i>
        <div>
            <strong>Super Admin Role — Permissions Locked</strong><br>
            <span class="small text-muted">The Super Admin role has unrestricted access to all features. Its permissions cannot be modified.</span>
        </div>
    </div>
    @endif

    {{-- ── Session Alerts ──────────────────────────────────────────────────── --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(!$isSuperAdminRole)
    <form action="{{ route('admin.roles.permissions.update', $role) }}" method="POST" id="permissions-form">
        @csrf

        {{-- ── Toolbar ─────────────────────────────────────────────────────── --}}
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-body d-flex flex-wrap align-items-center gap-3 py-3">
                {{-- Search --}}
                <div class="flex-grow-1" style="max-width:340px">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input
                            type="text"
                            id="permission-search"
                            class="form-control border-start-0 bg-light"
                            placeholder="Search permissions or modules…"
                            autocomplete="off"
                        >
                    </div>
                </div>

                {{-- Global bulk actions --}}
                <div class="ms-auto d-flex gap-2 flex-wrap">
                    <button type="button" id="grant-all-btn" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-check-all me-1"></i>Grant All
                    </button>
                    <button type="button" id="revoke-all-btn" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-x-circle me-1"></i>Revoke All
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary px-4 fw-semibold" id="save-btn">
                        <i class="bi bi-floppy-fill me-1"></i>Save Permissions
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Permission Modules ───────────────────────────────────────────── --}}
        <div class="row g-3" id="permissions-container">

            @php
                $moduleIcons = [
                    'dashboard'   => 'bi-speedometer2',
                    'products'    => 'bi-box2-fill',
                    'categories'  => 'bi-box-seam-fill',
                    'collections' => 'bi-collection-fill',
                    'brands'      => 'bi-tags-fill',
                    'variants'    => 'bi-layers-fill',
                    'sellers'     => 'bi-person-check-fill',
                    'customers'   => 'bi-people-fill',
                    'orders'      => 'bi-cart-check-fill',
                    'reports'     => 'bi-bar-chart-fill',
                    'staff'       => 'bi-person-badge-fill',
                    'roles'       => 'bi-shield-lock-fill',
                    'settings'    => 'bi-gear-fill',
                ];
                $moduleColors = [
                    'dashboard'   => 'primary',
                    'products'    => 'info',
                    'categories'  => 'teal',
                    'collections' => 'indigo',
                    'brands'      => 'orange',
                    'variants'    => 'cyan',
                    'sellers'     => 'success',
                    'customers'   => 'warning',
                    'orders'      => 'danger',
                    'reports'     => 'secondary',
                    'staff'       => 'purple',
                    'roles'       => 'dark',
                    'settings'    => 'secondary',
                ];
            @endphp

            @foreach($allPermissions as $moduleName => $permissions)
            @php
                $icon  = $moduleIcons[$moduleName]  ?? 'bi-circle';
                $color = $moduleColors[$moduleName] ?? 'secondary';
                $moduleDisplayName = ucfirst($moduleName);
                $modulePermissions = is_array($permissions) ? $permissions : $permissions->all();
            @endphp
            <div class="col-12 col-md-6 col-xl-4 permission-module-col" data-module="{{ $moduleName }}">
                <div class="card shadow-sm border-0 h-100 rounded-3 permission-card">
                    <div class="card-header d-flex align-items-center justify-content-between bg-light rounded-top-3 py-2 px-3 border-0">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-{{ $color }} bg-opacity-10 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:36px;height:36px">
                                <i class="bi {{ $icon }} text-{{ $color }} fs-6"></i>
                            </div>
                            <div>
                                <span class="fw-semibold text-dark small module-name">{{ $moduleDisplayName }}</span>
                                <div class="text-muted" style="font-size:11px">{{ count($modulePermissions) }} permissions</div>
                            </div>
                        </div>
                        <div class="d-flex gap-1">
                            <button type="button"
                                class="btn btn-xs btn-outline-success py-0 px-2 module-select-all"
                                style="font-size:11px"
                                data-module="{{ $moduleName }}">
                                All
                            </button>
                            <button type="button"
                                class="btn btn-xs btn-outline-danger py-0 px-2 module-deselect-all"
                                style="font-size:11px"
                                data-module="{{ $moduleName }}">
                                None
                            </button>
                        </div>
                    </div>
                    <div class="card-body px-3 py-2">
                        @foreach($modulePermissions as $permission)
                        @php
                            $permArr = is_array($permission) ? $permission : $permission->toArray();
                            $isAssigned = in_array($permArr['id'], $assignedPermissionIds);
                        @endphp
                        <div class="form-check py-1 permission-item" data-module="{{ $moduleName }}" data-name="{{ strtolower($permArr['name']) }}">
                            <input
                                class="form-check-input permission-checkbox"
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permArr['id'] }}"
                                id="perm_{{ $permArr['id'] }}"
                                data-module="{{ $moduleName }}"
                                {{ $isAssigned ? 'checked' : '' }}
                            >
                            <label class="form-check-label small fw-medium permission-label" for="perm_{{ $permArr['id'] }}">
                                {{ $permArr['name'] }}
                            </label>
                            <div class="text-muted" style="font-size:10px;margin-top:-2px">
                                <code class="bg-transparent p-0 text-muted">{{ $permArr['slug'] }}</code>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

        </div>

        {{-- ── Bottom Save Button ───────────────────────────────────────────── --}}
        <div class="d-flex justify-content-end mt-4 gap-2">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-lg me-1"></i>Cancel
            </a>
            <button type="submit" class="btn btn-primary px-5 fw-semibold">
                <i class="bi bi-floppy-fill me-1"></i>Save Permissions
            </button>
        </div>

    </form>
    @endif

</div>

{{-- ── No results state (hidden by default) ───────────────────────────────── --}}
<div id="no-search-results" class="text-center py-5 d-none">
    <i class="bi bi-search fs-1 text-muted d-block mb-2"></i>
    <p class="text-muted">No permissions match your search.</p>
</div>

@push('scripts')
<script>
(function () {
    'use strict';

    // ── Search ──────────────────────────────────────────────────────────────
    const searchInput = document.getElementById('permission-search');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            let anyVisible = false;

            document.querySelectorAll('.permission-module-col').forEach(col => {
                const moduleName = col.dataset.module.toLowerCase();
                let colVisible = false;

                col.querySelectorAll('.permission-item').forEach(item => {
                    const name = item.dataset.name;
                    const matches = !q || moduleName.includes(q) || name.includes(q);
                    item.style.display = matches ? '' : 'none';
                    if (matches) colVisible = true;
                });

                col.style.display = (!q || colVisible) ? '' : 'none';
                if (colVisible || !q) anyVisible = true;
            });

            const noResults = document.getElementById('no-search-results');
            if (noResults) {
                noResults.classList.toggle('d-none', anyVisible || !q);
            }
        });
    }

    // ── Module Select All / None ────────────────────────────────────────────
    document.querySelectorAll('.module-select-all').forEach(btn => {
        btn.addEventListener('click', function () {
            const module = this.dataset.module;
            document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`)
                .forEach(cb => cb.checked = true);
        });
    });

    document.querySelectorAll('.module-deselect-all').forEach(btn => {
        btn.addEventListener('click', function () {
            const module = this.dataset.module;
            document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`)
                .forEach(cb => cb.checked = false);
        });
    });

    // ── Global Grant All / Revoke All ───────────────────────────────────────
    const grantAll = document.getElementById('grant-all-btn');
    if (grantAll) {
        grantAll.addEventListener('click', function () {
            if (!confirm('Grant ALL permissions to this role?')) return;
            document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = true);
        });
    }

    const revokeAll = document.getElementById('revoke-all-btn');
    if (revokeAll) {
        revokeAll.addEventListener('click', function () {
            if (!confirm('Revoke ALL permissions from this role?')) return;
            document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
        });
    }

    // ── Unsaved changes warning ─────────────────────────────────────────────
    let dirty = false;
    document.querySelectorAll('.permission-checkbox').forEach(cb => {
        cb.addEventListener('change', () => dirty = true);
    });

    const form = document.getElementById('permissions-form');
    if (form) {
        form.addEventListener('submit', () => dirty = false);
    }

    window.addEventListener('beforeunload', function (e) {
        if (dirty) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

})();
</script>
@endpush

@push('styles')
<style>
    .permission-card {
        transition: box-shadow 0.2s ease;
    }
    .permission-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,.08) !important;
    }
    .permission-item:not(:last-child) {
        border-bottom: 1px solid #f0f0f0;
    }
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .btn-xs {
        padding: 0.15rem 0.45rem;
        font-size: 0.7rem;
    }
    code {
        font-size: 10px;
    }
</style>
@endpush
@endsection
