@extends('layout.admin')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">Manage Variants</h2>

            <p class="text-muted mb-0">{{ $product->name }}</p>

        </div>

        <div class="d-flex gap-2">

            <button type="button" id="btn-add-color" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Add Color
            </button>

            <a href="/products" class="btn btn-secondary">Back to Products</a>

        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($variants->count() > 0 || true)

    <div class="card shadow">

        <div class="card-body">

            <form action="{{ route('products.variants.store', $product->id) }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="table-responsive">

                    <table class="table table-bordered align-middle" id="variants-table">

                        <thead class="table-light">

                            <tr>

                                <th width="15%">Color</th>

                                <th width="40%">Sizes & Stock and Price</th>

                                <th width="20%">Variant Image</th>

                                <th width="12%">Status</th>

                                <th width="13%">Action</th>

                            </tr>

                        </thead>

                        <tbody id="variants-tbody">

                            @foreach($variants as $index => $variant)

                            <tr data-variant-id="{{ $variant->id }}" data-is-saved="1">

                                {{-- ── Color ── --}}
                                <td>

                                    <div class="d-flex align-items-center gap-2">

                                        <div
                                            style="width:20px;height:20px;background-color:{{ $variant->color->code ?? '#000' }};border-radius:50%;border:2px solid #ddd;">
                                        </div>

                                        <strong>{{ $variant->color->name ?? 'N/A' }}</strong>

                                    </div>

                                    <input type="hidden" name="variants[{{ $index }}][color_id]" value="{{ $variant->color_id }}">

                                </td>

                                {{-- ── Sizes (checkbox + stock + price per size) ── --}}
                                <td>

                                    <div class="border rounded p-2">

                                        @foreach($sizes as $size)

                                            @php
                                                $selectedSize = $variant->sizes->firstWhere('id', $size->id);
                                                $sizePrice    = $selectedSize ? $selectedSize->pivot->price : '';
                                                $sizeStock    = $selectedSize ? $selectedSize->pivot->stock : 0;
                                            @endphp

                                            <div class="d-flex align-items-start mb-3">

                                                {{-- Checkbox --}}
                                                <div class="form-check me-2 mt-1">
                                                    <input
                                                        class="form-check-input size-checkbox"
                                                        type="checkbox"
                                                        name="variants[{{ $index }}][sizes][{{ $size->id }}][selected]"
                                                        value="1"
                                                        {{ $selectedSize ? 'checked' : '' }}>
                                                </div>

                                                {{-- Size label + inputs --}}
                                                <div class="flex-grow-1">

                                                    <div class="fw-semibold mb-1">{{ $size->name }}</div>

                                                    <div class="d-flex gap-2">

                                                        <input
                                                            type="number"
                                                            class="form-control form-control-sm size-stock"
                                                            name="variants[{{ $index }}][sizes][{{ $size->id }}][stock]"
                                                            value="{{ $sizeStock }}"
                                                            min="0"
                                                            placeholder="Stock"
                                                            {{ $selectedSize ? '' : 'disabled' }}>

                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            class="form-control form-control-sm size-price"
                                                            name="variants[{{ $index }}][sizes][{{ $size->id }}][price]"
                                                            value="{{ $sizePrice }}"
                                                            min="0"
                                                            placeholder="Price (₹)"
                                                            {{ $selectedSize ? '' : 'disabled' }}>

                                                    </div>

                                                </div>

                                            </div>

                                        @endforeach

                                    </div>

                                </td>

                                {{-- ── Variant Image ── --}}
                                <td>

                                    <input
                                        type="file"
                                        class="form-control"
                                        name="variants[{{ $index }}][image]"
                                        accept="image/*">

                                    @if($variant->image)
                                        <small class="text-muted d-block mt-1">Current: Uploaded</small>
                                    @endif

                                </td>

                                {{-- ── Status ── --}}
                                <td>

                                    <select class="form-select" name="variants[{{ $index }}][status]">

                                        <option value="1" {{ $variant->status == 1 ? 'selected' : '' }}>Active</option>

                                        <option value="0" {{ $variant->status == 0 ? 'selected' : '' }}>Inactive</option>

                                    </select>

                                </td>

                                {{-- ── Action ── --}}
                                <td class="text-center">

                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm btn-delete-variant"
                                        data-variant-id="{{ $variant->id }}"
                                        data-delete-url="{{ route('products.variants.destroy', [$product->id, $variant->id]) }}"
                                        data-color-name="{{ $variant->color->name ?? 'this color' }}">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save"></i> Update Variants
                </button>

            </form>

        </div>

    </div>

    @endif

</div>

{{-- ============================================================
     Hidden template for a NEW (unsaved) color row — cloned by JS
     ============================================================ --}}
<template id="new-color-row-template">
    <tr data-variant-id="" data-is-saved="0">

        {{-- Color dropdown --}}
        <td>
            <select class="form-select new-color-select" name="__NEW_COLOR_SELECT__">
                <option value="">-- Select Color --</option>
                @foreach($colors as $color)
                    <option
                        value="{{ $color->id }}"
                        data-code="{{ $color->code }}"
                        data-used="{{ $variants->contains('color_id', $color->id) ? '1' : '0' }}">
                        {{ $color->name }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" class="hidden-color-id" name="__COLOR_ID__" value="">
        </td>

        {{-- Sizes --}}
        <td>
            <div class="border rounded p-2">
                @foreach($sizes as $size)
                <div class="d-flex align-items-start mb-3">

                    <div class="form-check me-2 mt-1">
                        <input
                            class="form-check-input size-checkbox"
                            type="checkbox"
                            name="__SIZE_SELECTED_{{ $size->id }}__"
                            value="1">
                    </div>

                    <div class="flex-grow-1">

                        <div class="fw-semibold mb-1">{{ $size->name }}</div>

                        <div class="d-flex gap-2">

                            <input
                                type="number"
                                class="form-control form-control-sm size-stock"
                                name="__SIZE_STOCK_{{ $size->id }}__"
                                value="0"
                                min="0"
                                placeholder="Stock"
                                disabled>

                            <input
                                type="number"
                                step="0.01"
                                class="form-control form-control-sm size-price"
                                name="__SIZE_PRICE_{{ $size->id }}__"
                                value=""
                                min="0"
                                placeholder="Price (₹)"
                                disabled>

                        </div>

                    </div>

                </div>
                @endforeach
            </div>
        </td>

        {{-- Variant Image --}}
        <td>
            <input
                type="file"
                class="form-control"
                name="__VARIANT_IMAGE__"
                accept="image/*">
        </td>

        {{-- Status --}}
        <td>
            <select class="form-select" name="__VARIANT_STATUS__">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </td>

        {{-- Action --}}
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm btn-delete-new">
                <i class="bi bi-trash"></i> Delete
            </button>
        </td>

    </tr>
</template>

{{-- Pass PHP data to JS --}}
<script>
const CSRF_TOKEN    = '{{ csrf_token() }}';
const EXISTING_USED = @json($variants->pluck('color_id')->toArray());
// sizes array for name-replacement
const SIZES_DATA    = @json($sizes->map(fn($s) => ['id' => $s->id, 'name' => $s->name]));
// Next row index starts after existing variants
let newRowIndex     = {{ $variants->count() }};

document.addEventListener('DOMContentLoaded', function () {

    // ── 1. Checkbox toggle (price/stock enable/disable) ──────────────────
    function bindCheckboxes(context) {
        context.querySelectorAll('.size-checkbox').forEach(function (checkbox) {
            function toggleInputs() {
                const row        = checkbox.closest('div.d-flex');
                const priceInput = row.querySelector('.size-price');
                const stockInput = row.querySelector('.size-stock');
                if (checkbox.checked) {
                    priceInput.disabled = false;
                    stockInput.disabled = false;
                } else {
                    priceInput.disabled = true;
                    stockInput.disabled = true;
                    priceInput.value    = '';
                    stockInput.value    = 0;
                }
            }
            toggleInputs();
            checkbox.addEventListener('change', toggleInputs);
        });
    }

    // Bind on existing rows
    bindCheckboxes(document.getElementById('variants-tbody'));

    // ── 2. Delete existing (saved) variant ────────────────────────────────
    document.getElementById('variants-tbody').addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-variant');
        if (!btn) return;

        const colorName = btn.dataset.colorName;
        const url       = btn.dataset.deleteUrl;
        const row       = btn.closest('tr');

        if (!confirm(`Delete the "${colorName}" variant? This will also remove all its size/price/stock data. This cannot be undone.`)) {
            return;
        }

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
        })
        .then(function (res) {
            if (!res.ok) throw new Error('Server error: ' + res.status);
            return res.json();
        })
        .then(function (data) {
            if (data.success) {
                // Remove the color_id from the "used" list so it appears in new dropdowns
                const colorId = parseInt(row.querySelector('input[type=hidden]').value);
                const pos = EXISTING_USED.indexOf(colorId);
                if (pos > -1) EXISTING_USED.splice(pos, 1);

                row.remove();
                refreshNewDropdowns();
            }
        })
        .catch(function (err) {
            alert('Failed to delete variant: ' + err.message);
        });
    });

    // ── 3. Delete unsaved (new) variant row ──────────────────────────────
    document.getElementById('variants-tbody').addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-new');
        if (!btn) return;
        const row = btn.closest('tr');
        // Free the selected color_id back to available
        const select = row.querySelector('.new-color-select');
        if (select && select.value) {
            const selectedId = parseInt(select.value);
            const pos = EXISTING_USED.indexOf(selectedId);
            if (pos > -1) EXISTING_USED.splice(pos, 1);
        }
        row.remove();
        refreshNewDropdowns();
    });

    // ── 4. Add Color button ───────────────────────────────────────────────
    document.getElementById('btn-add-color').addEventListener('click', function () {
        const template = document.getElementById('new-color-row-template');
        const clone    = template.content.cloneNode(true);
        const tr       = clone.querySelector('tr');
        const idx      = newRowIndex++;

        // Replace placeholder names with real indexed names
        tr.querySelectorAll('[name]').forEach(function (el) {
            el.name = el.name
                .replace('__NEW_COLOR_SELECT__', 'new_color_select_' + idx)      // not submitted
                .replace('__COLOR_ID__',         'variants[' + idx + '][color_id]')
                .replace('__VARIANT_IMAGE__',    'variants[' + idx + '][image]')
                .replace('__VARIANT_STATUS__',   'variants[' + idx + '][status]');

            SIZES_DATA.forEach(function (size) {
                el.name = el.name
                    .replace('__SIZE_SELECTED_' + size.id + '__', 'variants[' + idx + '][sizes][' + size.id + '][selected]')
                    .replace('__SIZE_STOCK_'    + size.id + '__', 'variants[' + idx + '][sizes][' + size.id + '][stock]')
                    .replace('__SIZE_PRICE_'    + size.id + '__', 'variants[' + idx + '][sizes][' + size.id + '][price]');
            });
        });

        // Remove already-used colors from the dropdown
        const select = tr.querySelector('.new-color-select');
        Array.from(select.options).forEach(function (opt) {
            if (opt.value && EXISTING_USED.includes(parseInt(opt.value))) {
                opt.remove();
            }
        });

        // When a color is chosen, mark it as used and sync the hidden input
        const hiddenColorId = tr.querySelector('.hidden-color-id');
        select.addEventListener('change', function () {
            // Un-mark previous selection from used list
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

        // Bind checkbox toggles for new row
        bindCheckboxes(tr);
    });

    // ── 5. Refresh all new-row dropdowns to hide newly-used colors ────────
    function refreshNewDropdowns() {
        document.querySelectorAll('.new-color-select').forEach(function (select) {
            const currentVal = select.value;
            Array.from(select.options).forEach(function (opt) {
                if (!opt.value) return; // keep placeholder
                const id = parseInt(opt.value);
                // Show if: not in used list, OR it's the currently selected value of THIS dropdown
                opt.hidden = EXISTING_USED.includes(id) && id !== parseInt(currentVal);
            });
        });
    }

});
</script>

@endsection
