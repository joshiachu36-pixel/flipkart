@extends('layout.admin')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">Product Variants</h2>

            <p class="text-muted mb-0">{{ $product->name }}</p>

        </div>

        <a href="/products" class="btn btn-secondary">Back</a>

    </div>

    <div class="card shadow">

        <div class="card-body">

            <form action="{{ route('products.variants.store', optional($product)->id) }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="table-responsive">

                    <table class="table table-bordered align-middle">

                        <thead class="table-light">

                            <tr>

                                <th width="15%">Color</th>

                                <th width="20%">Variant Image</th>

                                <th width="10%">Size</th>

                                <th width="10%">Select</th>

                                <th width="15%">Price (₹)</th>

                                <th width="15%">Stock</th>

                                <th width="15%">Status</th>

                            </tr>

                        </thead>

                        <tbody>

@foreach($colors as $color)

    @php

        $variant = $existingVariants[$color->id] ?? null;

    @endphp

    @foreach($sizes as $size)

        @php

            $selected = false;
            $stock = 0;
            $price = 0;

            if($variant){

                $pivot = $variant->sizes->firstWhere('id', $size->id);

                if($pivot){

                    $selected = true;
                    $stock = $pivot->pivot->stock;
                    $price = $pivot->pivot->price;

                }

            }

        @endphp

        <tr>

            @if($loop->first)

                <td rowspan="{{ $sizes->count() }}">

                    <div class="d-flex align-items-center gap-2">

                        <div
                            style="width:20px;
                                   height:20px;
                                   border-radius:50%;
                                   background:{{ $color->code ?? '#000' }};
                                   border:1px solid #ccc;">
                        </div>

                        <strong>{{ $color->name }}</strong>

                    </div>

                    <input
                        type="hidden"
                        name="variants[{{ $loop->parent->index }}][color_id]"
                        value="{{ $color->id }}">

                </td>

                <td rowspan="{{ $sizes->count() }}">

                    <input
                        type="file"
                        class="form-control"
                        name="variants[{{ $loop->parent->index }}][image]">

                    @if($variant && $variant->image)

                        <small class="text-success">

                            Image Uploaded

                        </small>

                    @endif

                </td>

            @endif

            <td>

                {{ $size->name }}

            </td>

            <td class="text-center">

                <input
                    type="checkbox"

                    class="form-check-input size-checkbox"

                    name="variants[{{ $loop->parent->index }}][sizes][{{ $size->id }}][selected]"

                    value="1"

                    {{ $selected ? 'checked' : '' }}>

            </td>

            <td>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    class="form-control size-price"
                    name="variants[{{ $loop->parent->index }}][sizes][{{ $size->id }}][price]"
                    value="{{ $price }}"
                    {{ $selected ? '' : 'disabled' }}>

            </td>

            <td>

                <input
                    type="number"

                    min="0"

                    class="form-control size-stock"

                    name="variants[{{ $loop->parent->index }}][sizes][{{ $size->id }}][stock]"

                    value="{{ $stock }}"
                    
                    {{ $selected ? '' : 'disabled' }}>

            </td>

            @if($loop->first)

                <td rowspan="{{ $sizes->count() }}">

                    <select
                        class="form-select"

                        name="variants[{{ $loop->parent->index }}][status]">

                        <option
                            value="1"
                            {{ ($variant->status ?? 1)==1 ? 'selected' : '' }}>

                            Active

                        </option>

                        <option
                            value="0"
                            {{ ($variant->status ?? 1)==0 ? 'selected' : '' }}>

                            Inactive

                        </option>

                    </select>

                </td>

            @endif

        </tr>

    @endforeach

@endforeach

</tbody>

                    </table>

                </div>

                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save"></i> Save Variants
                </button>

            </form>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.size-checkbox').forEach(function (checkbox) {

        function toggleInputs() {

            const row = checkbox.closest('tr');

            const priceInput = row.querySelector('.size-price');
            const stockInput = row.querySelector('.size-stock');

            if (checkbox.checked) {

                priceInput.disabled = false;
                stockInput.disabled = false;

            } else {

                priceInput.disabled = true;
                stockInput.disabled = true;

                priceInput.value = 0;
                stockInput.value = 0;

            }

        }

        toggleInputs();

        checkbox.addEventListener('change', toggleInputs);

    });

});
</script>
@endsection