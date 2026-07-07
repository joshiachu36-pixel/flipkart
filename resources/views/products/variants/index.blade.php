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

                                <th width="12%">Color</th>

                                <th width="12%">Price (₹)</th>

                                <th width="12%">Stock</th>

                                <th width="20%">Size</th>

                                <th width="20%">Variant Image</th>

                                <th width="12%">Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($colors as $color)

                            @php

                                $variant = $existingVariants[$color->id] ?? null;

                            @endphp

                            <tr>

                                <td>

                                    <div class="d-flex align-items-center gap-2">

                                        <div 
                                            style="width: 20px; height: 20px; background-color: {{ $color->code ?? '#000' }}; border-radius: 50%; border: 2px solid #ddd;">
                                        </div>

                                        <strong>{{ $color->name }}</strong>

                                    </div>

                                    <input type="hidden" name="variants[{{ $loop->index }}][color_id]" value="{{ $color->id }}">

                                </td>

                                <td>

                                    <input
                                        type="number"
                                        step="0.01"
                                        class="form-control"
                                        name="variants[{{ $loop->index }}][price]"
                                        value="{{ $variant->price ?? 0 }}"
                                        min="0"
                                        placeholder="0.00">

                                </td>

                                <td>

                                    <input
                                        type="number"
                                        class="form-control"
                                        name="variants[{{ $loop->index }}][stock]"
                                        value="{{ $variant->stock ?? 0 }}"
                                        min="0"
                                        placeholder="0">

                                </td>

                                <td>

                                    <select
                                        class="form-select"
                                        name="variants[{{ $loop->index }}][size_ids][]"
                                        multiple>

                                        <option value="">-- Select Size --</option>

                                        @foreach($sizes as $size)

                                            <option value="{{ $size->id }}"
                                                {{ $variant && $variant->sizes->contains($size->id) ? 'selected' : '' }}>
                                                {{ $size->name }}
                                            </option>

                                        @endforeach

                                    </select>

                                </td>

                                <td>

                                    <input
                                        type="file"
                                        class="form-control"
                                        name="variants[{{ $loop->index }}][image]"
                                        accept="image/*">

                                    @if($variant && $variant->image)

                                        <small class="text-muted d-block mt-1">Current: Uploaded</small>

                                    @endif

                                </td>

                                <td>

                                    <select class="form-select" name="variants[{{ $loop->index }}][status]">

                                        <option value="1" {{ ($variant->status ?? 1) == 1 ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="0" {{ ($variant->status ?? 1) == 0 ? 'selected' : '' }}>
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
                    <i class="bi bi-save"></i> Save Variants
                </button>

            </form>

        </div>

    </div>

</div>

@endsection