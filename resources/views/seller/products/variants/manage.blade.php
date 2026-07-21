@extends('layout.seller')

@section('content')

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Manage Variants & Inventory</h2>
            <p class="text-muted mb-0">Configure colors, SKUs, and independent size-level stock & pricing for: <strong>{{ $product->name }}</strong></p>
        </div>

        <div class="d-flex gap-2">
            <button type="button" id="btn-add-color" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Add Color Variant
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSizeModal">
                <i class="bi bi-plus-circle me-1"></i> Add Size Option
            </button>
            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary">Back to Products</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Validation Error:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <form action="{{ route('seller.products.variants.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="variants-table">
                        <thead class="table-light">
                            <tr>
                                <th width="7%">Priority</th>
                                <th width="15%">Color</th>
                                <th width="15%">SKU</th>
                                <th width="38%">Sizes & Independent Pricing (Stock, Selling Price, Original Price)</th>
                                <th width="13%">Variant Image</th>
                                <th width="7%">Status</th>
                                <th width="5%" class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody id="variants-tbody">
                            @foreach($variants as $index => $variant)
                            <tr data-variant-id="{{ $variant->id }}" data-is-saved="1">

                                {{-- ── Priority ── --}}
                                <td>
                                    <input type="number" class="form-control form-control-sm text-center fw-bold @error("variants.$index.priority") is-invalid @enderror" 
                                           name="variants[{{ $index }}][priority]" value="{{ old("variants.$index.priority", $variant->priority ?? ($index + 1)) }}" min="1">
                                    @error("variants.$index.priority")
                                        <div class="invalid-feedback d-block text-center mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block text-center mt-1">
                                        @if(old("variants.$index.priority", $variant->priority ?? ($index + 1)) == 1)
                                            <span class="badge bg-primary-subtle text-primary border border-primary px-1">Default</span>
                                        @endif
                                    </small>
                                </td>

                                {{-- ── Color ── --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <div style="width:20px;height:20px;background-color:{{ $variant->color->code ?? '#000' }};border-radius:50%;border:2px solid #ddd;"></div>
                                        <strong class="text-dark">{{ $variant->color->name ?? 'N/A' }}</strong>
                                    </div>
                                    <input type="hidden" name="variants[{{ $index }}][color_id]" value="{{ $variant->color_id }}">
                                </td>

                                {{-- ── SKU ── --}}
                                <td>
                                    <input type="text" class="form-control form-control-sm font-monospace" 
                                           name="variants[{{ $index }}][sku]" value="{{ old("variants.$index.sku", $variant->sku) }}" placeholder="Auto SKU">
                                </td>

                                {{-- ── Sizes & Independent Pricing ── --}}
                                <td>
                                    <div class="border rounded p-2 bg-light">
                                        @foreach($sizes as $size)
                                            @php
                                                $selectedSize = $variant->sizes->firstWhere('id', $size->id);
                                                $sizePrice    = $selectedSize ? $selectedSize->pivot->price : '';
                                                $sizeOrig     = $selectedSize ? $selectedSize->pivot->original_price : '';
                                                $sizeStock    = $selectedSize ? $selectedSize->pivot->stock : 0;
                                                $discountPct  = 0;
                                                if ($sizeOrig > 0 && $sizePrice > 0 && $sizePrice < $sizeOrig) {
                                                    $discountPct = round((($sizeOrig - $sizePrice) / $sizeOrig) * 100);
                                                }
                                            @endphp

                                            <div class="size-item-row p-2 bg-white rounded border mb-2">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input size-checkbox" type="checkbox"
                                                            name="variants[{{ $index }}][sizes][{{ $size->id }}][selected]"
                                                            value="1" {{ $selectedSize ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold text-dark me-2">{{ $size->name }}</label>
                                                    </div>
                                                    <div class="discount-badge-container">
                                                        @if($discountPct > 0)
                                                            <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-0.5" style="font-size: 0.7rem;">
                                                                <i class="bi bi-tag-fill me-1"></i>{{ $discountPct }}% OFF
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="row g-2">
                                                    <div class="col-4">
                                                        <label class="form-label mb-0 small text-muted">Stock</label>
                                                        <input type="number" class="form-control form-control-sm size-stock"
                                                            name="variants[{{ $index }}][sizes][{{ $size->id }}][stock]"
                                                            value="{{ $sizeStock }}" min="0" placeholder="Stock" {{ $selectedSize ? '' : 'disabled' }}>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label mb-0 small text-muted">Selling Price (₹)</label>
                                                        <input type="number" step="0.01" class="form-control form-control-sm size-price selling-price-input"
                                                            name="variants[{{ $index }}][sizes][{{ $size->id }}][price]"
                                                            value="{{ $sizePrice }}" min="0" placeholder="Selling ₹" {{ $selectedSize ? '' : 'disabled' }}>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label mb-0 small text-muted">Original Price (₹)</label>
                                                        <input type="number" step="0.01" class="form-control form-control-sm size-original-price original-price-input"
                                                            name="variants[{{ $index }}][sizes][{{ $size->id }}][original_price]"
                                                            value="{{ $sizeOrig }}" min="0" placeholder="MSRP ₹" {{ $selectedSize ? '' : 'disabled' }}>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- ── Variant Image ── --}}
                                <td>
                                    <input type="file" class="form-control form-control-sm" 
                                           name="variants[{{ $index }}][image]" accept="image/*">
                                    @if($variant->image)
                                        <div class="mt-2 text-center">
                                            <img src="{{ asset('storage/'.$variant->image) }}" class="rounded border shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                        </div>
                                    @endif
                                </td>

                                {{-- ── Status ── --}}
                                <td>
                                    <select class="form-select form-select-sm" name="variants[{{ $index }}][status]">
                                        <option value="1" {{ $variant->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $variant->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </td>

                                {{-- ── Action ── --}}
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-delete-variant"
                                        data-variant-id="{{ $variant->id }}"
                                        data-delete-url="{{ route('seller.products.variants.destroy', [$product->id, $variant->id]) }}"
                                        data-color-name="{{ $variant->color->name ?? 'this color' }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>

                            </tr>
                            @endforeach

                            @if(is_array(old('variants')))
                                @foreach(old('variants') as $oldIndex => $oldVariantData)
                                    @if($oldIndex >= count($variants) && !empty($oldVariantData['color_id']))
                                        @php
                                            $oldColor = $colors->firstWhere('id', $oldVariantData['color_id']);
                                        @endphp
                                        <tr data-variant-id="" data-is-saved="0">
                                            <td>
                                                <input type="number" class="form-control form-control-sm text-center fw-bold @error("variants.$oldIndex.priority") is-invalid @enderror" 
                                                       name="variants[{{ $oldIndex }}][priority]" value="{{ old("variants.$oldIndex.priority", $oldIndex + 1) }}" min="1">
                                                @error("variants.$oldIndex.priority")
                                                    <div class="invalid-feedback d-block text-center mt-1">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <div style="width:20px;height:20px;background-color:{{ $oldColor->code ?? '#000' }};border-radius:50%;border:2px solid #ddd;"></div>
                                                    <strong class="text-dark">{{ $oldColor->name ?? 'N/A' }}</strong>
                                                </div>
                                                <input type="hidden" name="variants[{{ $oldIndex }}][color_id]" value="{{ $oldVariantData['color_id'] }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm font-monospace" 
                                                       name="variants[{{ $oldIndex }}][sku]" value="{{ old("variants.$oldIndex.sku") }}" placeholder="Auto SKU">
                                            </td>
                                            <td>
                                                <div class="border rounded p-2 bg-light">
                                                    @foreach($sizes as $size)
                                                        @php
                                                            $sizeData = $oldVariantData['sizes'][$size->id] ?? [];
                                                            $isSelected = isset($sizeData['selected']);
                                                            $sStock = $sizeData['stock'] ?? 0;
                                                            $sPrice = $sizeData['price'] ?? '';
                                                            $sOrig  = $sizeData['original_price'] ?? '';
                                                        @endphp
                                                        <div class="size-item-row p-2 bg-white rounded border mb-2">
                                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input size-checkbox" type="checkbox"
                                                                        name="variants[{{ $oldIndex }}][sizes][{{ $size->id }}][selected]"
                                                                        value="1" {{ $isSelected ? 'checked' : '' }}>
                                                                    <label class="form-check-label fw-bold text-dark me-2">{{ $size->name }}</label>
                                                                </div>
                                                                <div class="discount-badge-container"></div>
                                                            </div>
                                                            <div class="row g-2">
                                                                <div class="col-4">
                                                                    <label class="form-label mb-0 small text-muted">Stock</label>
                                                                    <input type="number" class="form-control form-control-sm size-stock"
                                                                        name="variants[{{ $oldIndex }}][sizes][{{ $size->id }}][stock]"
                                                                        value="{{ $sStock }}" min="0" placeholder="Stock" {{ $isSelected ? '' : 'disabled' }}>
                                                                </div>
                                                                <div class="col-4">
                                                                    <label class="form-label mb-0 small text-muted">Selling Price (₹)</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm size-price selling-price-input"
                                                                        name="variants[{{ $oldIndex }}][sizes][{{ $size->id }}][price]"
                                                                        value="{{ $sPrice }}" min="0" placeholder="Selling ₹" {{ $isSelected ? '' : 'disabled' }}>
                                                                </div>
                                                                <div class="col-4">
                                                                    <label class="form-label mb-0 small text-muted">Original Price (₹)</label>
                                                                    <input type="number" step="0.01" class="form-control form-control-sm size-original-price original-price-input"
                                                                        name="variants[{{ $oldIndex }}][sizes][{{ $size->id }}][original_price]"
                                                                        value="{{ $sOrig }}" min="0" placeholder="MSRP ₹" {{ $isSelected ? '' : 'disabled' }}>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                <input type="file" class="form-control form-control-sm" name="variants[{{ $oldIndex }}][image]" accept="image/*">
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm" name="variants[{{ $oldIndex }}][status]">
                                                    <option value="1" {{ old("variants.$oldIndex.status") == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ old("variants.$oldIndex.status") == 0 ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-delete-new">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i> Every selected size has its own independent stock, selling price, and original price.
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-check-circle me-1"></i> Save & Update Variants
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

{{-- Template for NEW color row --}}
<template id="new-color-row-template">
    <tr data-variant-id="" data-is-saved="0">

        <td>
            <input type="number" class="form-control form-control-sm text-center fw-bold" name="__VARIANT_PRIORITY__" value="1" min="1">
        </td>

        <td>
            <select class="form-select form-select-sm new-color-select" name="__NEW_COLOR_SELECT__">
                <option value="">-- Select Color --</option>
                @foreach($colors as $color)
                    <option value="{{ $color->id }}" data-code="{{ $color->code }}" data-used="{{ $variants->contains('color_id', $color->id) ? '1' : '0' }}">
                        {{ $color->name }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" class="hidden-color-id" name="__COLOR_ID__" value="">
        </td>

        <td>
            <input type="text" class="form-control form-control-sm font-monospace" name="__VARIANT_SKU__" placeholder="Auto SKU">
        </td>

        <td>
            <div class="border rounded p-2 bg-light">
                @foreach($sizes as $size)
                <div class="size-item-row p-2 bg-white rounded border mb-2">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="form-check">
                            <input class="form-check-input size-checkbox" type="checkbox" name="__SIZE_SELECTED_{{ $size->id }}__" value="1">
                            <label class="form-check-label fw-bold text-dark me-2">{{ $size->name }}</label>
                        </div>
                        <div class="discount-badge-container"></div>
                    </div>
                    <div class="row g-2">
                        <div class="col-4">
                            <label class="form-label mb-0 small text-muted">Stock</label>
                            <input type="number" class="form-control form-control-sm size-stock" name="__SIZE_STOCK_{{ $size->id }}__" value="0" min="0" placeholder="Stock" disabled>
                        </div>
                        <div class="col-4">
                            <label class="form-label mb-0 small text-muted">Selling Price (₹)</label>
                            <input type="number" step="0.01" class="form-control form-control-sm size-price selling-price-input" name="__SIZE_PRICE_{{ $size->id }}__" value="" min="0" placeholder="Selling ₹" disabled>
                        </div>
                        <div class="col-4">
                            <label class="form-label mb-0 small text-muted">Original Price (₹)</label>
                            <input type="number" step="0.01" class="form-control form-control-sm size-original-price original-price-input" name="__SIZE_ORIGINAL_PRICE_{{ $size->id }}__" value="" min="0" placeholder="MSRP ₹" disabled>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </td>

        <td>
            <input type="file" class="form-control form-control-sm" name="__VARIANT_IMAGE__" accept="image/*">
        </td>

        <td>
            <select class="form-select form-select-sm" name="__VARIANT_STATUS__">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </td>

        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm btn-delete-new">
                <i class="bi bi-trash"></i>
            </button>
        </td>

    </tr>
</template>

<!-- Add Size Modal -->
<div class="modal fade" id="addSizeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-6">Add New Size Option</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="add-size-form">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Size Name</label>
                        <input type="text" class="form-control" id="sizeName" name="name" placeholder="e.g. S, M, L, XL, 42" required>
                    </div>
                    <input type="hidden" name="status" value="1">
                    <div id="size-error" class="text-danger mb-2 d-none"></div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm" id="btn-save-size">Save Size</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const CSRF_TOKEN    = '{{ csrf_token() }}';
const EXISTING_USED = @json(array_values(array_unique(array_merge($variants->pluck('color_id')->toArray(), is_array(old('variants')) ? array_column(old('variants'), 'color_id') : []))));
const SIZES_DATA    = @json($sizes->map(fn($s) => ['id' => $s->id, 'name' => $s->name]));
let newRowIndex     = {{ is_array(old('variants')) ? max(count($variants), count(old('variants'))) : $variants->count() }};

document.addEventListener('DOMContentLoaded', function () {

    function calculateSizeDiscount(sizeItemRow) {
        const sellingInput   = sizeItemRow.querySelector('.selling-price-input');
        const originalInput  = sizeItemRow.querySelector('.original-price-input');
        const badgeContainer = sizeItemRow.querySelector('.discount-badge-container');
        if (!sellingInput || !originalInput || !badgeContainer) return;

        const selling  = parseFloat(sellingInput.value) || 0;
        const original = parseFloat(originalInput.value) || 0;

        if (original > 0 && selling > 0 && selling < original) {
            const pct = Math.round(((original - selling) / original) * 100);
            badgeContainer.innerHTML = `<span class="badge bg-danger-subtle text-danger border border-danger px-2 py-0.5" style="font-size: 0.7rem;"><i class="bi bi-tag-fill me-1"></i>${pct}% OFF</span>`;
        } else {
            badgeContainer.innerHTML = '';
        }
    }

    function bindSizeItemEvents(container) {
        container.querySelectorAll('.size-item-row').forEach(sizeRow => {
            const sellingInput  = sizeRow.querySelector('.selling-price-input');
            const originalInput = sizeRow.querySelector('.original-price-input');
            if (sellingInput) sellingInput.addEventListener('input', () => calculateSizeDiscount(sizeRow));
            if (originalInput) originalInput.addEventListener('input', () => calculateSizeDiscount(sizeRow));
        });
    }

    bindSizeItemEvents(document.getElementById('variants-tbody'));

    function bindCheckboxes(context) {
        context.querySelectorAll('.size-checkbox').forEach(function (checkbox) {
            function toggleInputs() {
                const sizeRow     = checkbox.closest('.size-item-row');
                const priceInput  = sizeRow.querySelector('.size-price');
                const origInput   = sizeRow.querySelector('.size-original-price');
                const stockInput  = sizeRow.querySelector('.size-stock');
                if (checkbox.checked) {
                    if (priceInput) priceInput.disabled = false;
                    if (origInput) origInput.disabled = false;
                    if (stockInput) stockInput.disabled = false;
                } else {
                    if (priceInput) { priceInput.disabled = true; priceInput.value = ''; }
                    if (origInput) { origInput.disabled = true; origInput.value = ''; }
                    if (stockInput) { stockInput.disabled = true; stockInput.value = 0; }
                    calculateSizeDiscount(sizeRow);
                }
            }
            toggleInputs();
            checkbox.addEventListener('change', toggleInputs);
        });
    }

    bindCheckboxes(document.getElementById('variants-tbody'));

    document.getElementById('variants-tbody').addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-variant');
        if (!btn) return;

        const colorName = btn.dataset.colorName;
        const url       = btn.dataset.deleteUrl;
        const row       = btn.closest('tr');

        if (!confirm(`Delete the "${colorName}" variant? This cannot be undone.`)) {
            return;
        }

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const colorId = parseInt(row.querySelector('input[name*="[color_id]"]').value);
                const pos = EXISTING_USED.indexOf(colorId);
                if (pos > -1) EXISTING_USED.splice(pos, 1);

                row.remove();
                refreshNewDropdowns();
            }
        });
    });

    document.getElementById('variants-tbody').addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-new');
        if (!btn) return;
        const row = btn.closest('tr');
        const select = row.querySelector('.new-color-select');
        if (select && select.value) {
            const selectedId = parseInt(select.value);
            const pos = EXISTING_USED.indexOf(selectedId);
            if (pos > -1) EXISTING_USED.splice(pos, 1);
        }
        row.remove();
        refreshNewDropdowns();
    });

    document.getElementById('btn-add-color').addEventListener('click', function () {
        const template = document.getElementById('new-color-row-template');
        const clone    = template.content.cloneNode(true);
        const tr       = clone.querySelector('tr');
        const idx      = newRowIndex++;

        tr.querySelectorAll('[name]').forEach(function (el) {
            el.name = el.name
                .replace('__VARIANT_PRIORITY__', 'variants[' + idx + '][priority]')
                .replace('__NEW_COLOR_SELECT__', 'new_color_select_' + idx)
                .replace('__COLOR_ID__',         'variants[' + idx + '][color_id]')
                .replace('__VARIANT_SKU__',      'variants[' + idx + '][sku]')
                .replace('__VARIANT_IMAGE__',    'variants[' + idx + '][image]')
                .replace('__VARIANT_STATUS__',   'variants[' + idx + '][status]');

            SIZES_DATA.forEach(function (size) {
                el.name = el.name
                    .replace('__SIZE_SELECTED_' + size.id + '__',       'variants[' + idx + '][sizes][' + size.id + '][selected]')
                    .replace('__SIZE_STOCK_'    + size.id + '__',       'variants[' + idx + '][sizes][' + size.id + '][stock]')
                    .replace('__SIZE_PRICE_'    + size.id + '__',       'variants[' + idx + '][sizes][' + size.id + '][price]')
                    .replace('__SIZE_ORIGINAL_PRICE_' + size.id + '__', 'variants[' + idx + '][sizes][' + size.id + '][original_price]');
            });
        });

        const priorityInput = tr.querySelector('input[name*="[priority]"]');
        if (priorityInput) priorityInput.value = idx + 1;

        const select = tr.querySelector('.new-color-select');
        Array.from(select.options).forEach(function (opt) {
            if (opt.value && EXISTING_USED.includes(parseInt(opt.value))) {
                opt.remove();
            }
        });

        const hiddenColorId = tr.querySelector('.hidden-color-id');
        select.addEventListener('change', function () {
            if (hiddenColorId.value) {
                const prev = parseInt(hiddenColorId.value);
                const pos  = EXISTING_USED.indexOf(prev);
                if (pos > -1) EXISTING_USED.splice(pos, 1);
            }
            hiddenColorId.value = select.value;
            if (select.value) {
                EXISTING_USED.push(parseInt(select.value));
            }
            refreshNewDropdowns();
        });

        document.getElementById('variants-tbody').appendChild(tr);
        bindCheckboxes(tr);
        bindSizeItemEvents(tr);
    });

    function refreshNewDropdowns() {
        document.querySelectorAll('.new-color-select').forEach(function (select) {
            const currentVal = select.value;
            Array.from(select.options).forEach(function (opt) {
                if (!opt.value) return;
                const id = parseInt(opt.value);
                opt.hidden = EXISTING_USED.includes(id) && id !== parseInt(currentVal);
            });
        });
    }

    document.getElementById('add-size-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = document.getElementById('btn-save-size');
        const errorDiv = document.getElementById('size-error');
        
        submitBtn.disabled = true;
        errorDiv.classList.add('d-none');
        errorDiv.innerText = '';

        const formData = new FormData(form);

        fetch('{{ route("sizes.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.size) {
                const newSize = data.size;
                SIZES_DATA.push(newSize);

                document.querySelectorAll('#variants-tbody tr').forEach(function(tr) {
                    const sizesContainer = tr.querySelector('.border.rounded.p-2.bg-light');
                    let prefix = '';
                    const hiddenColorInput = tr.querySelector('input[name*="[color_id]"]');
                    if (hiddenColorInput) {
                        const match = hiddenColorInput.name.match(/variants\[\d+\]/);
                        if (match) prefix = match[0];
                    }
                    if (prefix) appendSizeToContainer(sizesContainer, newSize, prefix, false);
                });

                const modalEl = document.getElementById('addSizeModal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modalInstance.hide();
                form.reset();
            }
        })
        .finally(() => {
            submitBtn.disabled = false;
        });
    });

    function appendSizeToContainer(container, size, prefix, isTemplate = false) {
        const nameCheckbox = `${prefix}[sizes][${size.id}][selected]`;
        const nameStock    = `${prefix}[sizes][${size.id}][stock]`;
        const namePrice    = `${prefix}[sizes][${size.id}][price]`;
        const nameOrig     = `${prefix}[sizes][${size.id}][original_price]`;

        const sizeHtml = `
            <div class="size-item-row p-2 bg-white rounded border mb-2">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="form-check">
                        <input class="form-check-input size-checkbox" type="checkbox" name="${nameCheckbox}" value="1">
                        <label class="form-check-label fw-bold text-dark me-2">${size.name}</label>
                    </div>
                    <div class="discount-badge-container"></div>
                </div>
                <div class="row g-2">
                    <div class="col-4">
                        <label class="form-label mb-0 small text-muted">Stock</label>
                        <input type="number" class="form-control form-control-sm size-stock" name="${nameStock}" value="0" min="0" placeholder="Stock" disabled>
                    </div>
                    <div class="col-4">
                        <label class="form-label mb-0 small text-muted">Selling Price (₹)</label>
                        <input type="number" step="0.01" class="form-control form-control-sm size-price selling-price-input" name="${namePrice}" value="" min="0" placeholder="Selling ₹" disabled>
                    </div>
                    <div class="col-4">
                        <label class="form-label mb-0 small text-muted">Original Price (₹)</label>
                        <input type="number" step="0.01" class="form-control form-control-sm size-original-price original-price-input" name="${nameOrig}" value="" min="0" placeholder="MSRP ₹" disabled>
                    </div>
                </div>
            </div>
        `;
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = sizeHtml.trim();
        const newElement = tempDiv.firstChild;
        container.appendChild(newElement);

        bindCheckboxes(newElement);
        bindSizeItemEvents(newElement);
    }

});
</script>
@endsection
