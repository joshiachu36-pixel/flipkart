@extends('layout.admin')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">Manage Variants</h2>

            <p class="text-muted mb-0">{{ $product->name }}</p>

        </div>

        <a href="/products" class="btn btn-secondary">Back to Products</a>

    </div>

    @if($variants->count() > 0)

    <div class="card shadow">

        <div class="card-body">

            <form action="{{ route('products.variants.store', $product->id) }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="table-responsive">

                    <table class="table table-bordered align-middle">

                        <thead class="table-light">

                            <tr>

                                <th width="12%">Color</th>

                                <th width="12%">Price (₹)</th>

                                <th width="20%">Size</th>

                                <th width="20%">Variant Image</th>

                                <th width="12%">Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($variants as $index => $variant)

                            <tr>

                                <td>

                                    <div class="d-flex align-items-center gap-2">

                                        <div 
                                            style="width: 20px; height: 20px; background-color: {{ $variant->color->code ?? '#000' }}; border-radius: 50%; border: 2px solid #ddd;">
                                        </div>

                                        <strong>{{ $variant->color->name ?? 'N/A' }}</strong>

                                    </div>

                                    <input type="hidden" name="variants[{{ $index }}][color_id]" value="{{ $variant->color_id }}">

                                </td>

                                <td>

                                    <input
                                        type="number"
                                        step="0.01"
                                        class="form-control"
                                        name="variants[{{ $index }}][price]"
                                        value="{{ $variant->price ?? 0 }}"
                                        min="0"
                                        placeholder="0.00">

                                </td>

                                <td>

                                    <div class="border rounded p-2">

                                    @foreach($sizes as $size)

                                        @php
                                            $selectedSize = $variant->sizes->firstWhere('id', $size->id);
                                        @endphp

                                        <div class="d-flex align-items-center mb-2">

                                            <div class="form-check me-2">

                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="variants[{{ $index }}][sizes][{{ $size->id }}][selected]"
                                                    value="1"
                                                    {{ $selectedSize ? 'checked' : '' }}>

                                            </div>

                                            <div style="width:60px;">
                                                {{ $size->name }}
                                            </div>

                                            <input
                                                type="number"
                                                class="form-control"
                                                style="width:120px;"
                                                name="variants[{{ $index }}][sizes][{{ $size->id }}][stock]"
                                                value="{{ $selectedSize ? $selectedSize->pivot->stock : 0 }}"
                                                min="0"
                                                placeholder="Stock">

                                        </div>

                                    @endforeach

                                    </div>
                                </td>

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

                                <td>

                                    <select class="form-select" name="variants[{{ $index }}][status]">

                                        <option value="1" {{ $variant->status == 1 ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="0" {{ $variant->status == 0 ? 'selected' : '' }}>
                                            Inactive
                                        </option>

                                    </select>

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

    @else

    <div class="alert alert-info" role="alert">

        <h4 class="alert-heading">No Variants Found</h4>

        <p>This product doesn't have any variants yet. You can create variants by clicking the button below.</p>

        <hr>

        <p class="mb-0">

            <a href="{{ route('products.variants', $product->id) }}?colors[]={{ $colors->first()?->id ?? 1 }}" class="btn btn-success">
                Create Variants
            </a>

        </p>

    </div>

    @endif

</div>

@endsection
