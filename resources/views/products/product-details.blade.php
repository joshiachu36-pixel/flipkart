@extends('layouts.shop')

@section('content')

@if(session('success'))

    <div class="container mt-3">

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    </div>

@endif

<div class="container mt-4">

    @php

    $breadcrumbs = [];

    $category = $product->category;

    while($category)
    {
        array_unshift($breadcrumbs, $category);

        $category = $category->parent;
    }

    @endphp

    <nav aria-label="breadcrumb">

        <ol class="breadcrumb">

            <li class="breadcrumb-item">

                <a href="/shop">

                    Home

                </a>

            </li>

            @foreach($breadcrumbs as $crumb)

                <li class="breadcrumb-item">

                    <a href="/category/{{ $crumb->id }}">

                        {{ $crumb->name }}

                    </a>

                </li>

            @endforeach

            <li class="breadcrumb-item active">

                {{ $product->name }}

            </li>

        </ol>

    </nav>

</div>

<div class="container mt-5">

    <div class="row">

        <!-- Product Image -->
        <div class="col-md-5">

            <div class="card shadow-sm">

                <img
                    id="product-detail-image"
                    src="{{ asset('uploads/' . $product->image) }}"
                    class="img-fluid"
                    alt="{{ $product->name }}"
                    style="height:500px; object-fit:cover;">

            </div>

        </div>

        <!-- Product Information -->
        <div class="col-md-7">

            <h2 class="fw-bold">
                {{ $product->name }}
            </h2>

            <hr>
            
            @if($product->collections->count())

            <p class="mb-3">

                <strong>Collections :</strong>

                @foreach($product->collections as $productCollection)

                    <span class="badge bg-primary">

                       {{ $productCollection->name }}

                    </span>

                @endforeach

            </p>

            @endif

            @php

    $finalPrice = $product->price;

    if(isset($collection) && $collection)
    {
        if($collection->discount_type == 'percentage')
        {
            $finalPrice = $product->price -
                (($product->price * $collection->discount_value) / 100);
        }
        elseif($collection->discount_type == 'fixed')
        {
            $finalPrice = max(
                0,
                $product->price - $collection->discount_value
            );
        }
    }

    $variants = $product->variants()->where('status', 1)->with('color', 'sizes')->get();
    $colors = $variants->map(function ($variant) {
    return [
        'variant_id' => $variant->id,
        'color_id'   => $variant->color->id,
        'name'       => $variant->color->name,
        'image'      => $variant->image,

        // ADD THIS
        'price'      => $variant->price,

        'stock'      => $variant->stock,

        'sizes' => $variant->sizes->map(function ($size) {
            return [
                'id'    => $size->id,
                'name'  => $size->name,
                'stock' => $size->pivot->stock,
            ];
        })->toArray(),
    ];
});


@endphp

<div class="mb-4">

    @if(isset($collection) && $collection && $collection->discount_value)

        <h2 class="text-success fw-bold mb-1">

            ₹{{ number_format($finalPrice, 2) }}

        </h2>

        <div class="d-flex align-items-center gap-3">

            <span class="fs-5 text-muted text-decoration-line-through">

                ₹{{ number_format($product->price, 2) }}

            </span>

            <span class="badge bg-danger fs-6">

                @if($collection->discount_type == 'percentage')

                    {{ $collection->discount_value }}% OFF

                @else

                    ₹{{ $collection->discount_value }} OFF

                @endif

            </span>

        </div>

    @else

        <h2
            class="text-success fw-bold mb-1"
            id="product-price">

            ₹{{ number_format($colors->first()['price'] ?? $product->price, 2) }}

        </h2>

        @if($product->discount_percentage > 0)

            <div class="d-flex align-items-center gap-3">

                <span class="fs-5 text-muted text-decoration-line-through">

                    ₹{{ number_format($product->original_price, 2) }}

                </span>

                <span class="badge bg-danger fs-6">

                    {{ $product->discount_percentage }}% OFF

                </span>

            </div>

        @endif

    @endif

</div>
            @if($product->stock > 0)

                <h5 class="text-success mb-3">
                    ✔ In Stock
                </h5>

            @else

                <h5 class="text-danger mb-3">
                    ✖ Out of Stock
                </h5>

            @endif

            @if($colors->count())

                <div class="mb-4">
                    <h5 class="mb-2">Select Color</h5>
                    <div class="d-flex gap-2 flex-wrap" id="product-colors">
                        @foreach($colors as $index => $color)
                            <button
                                type="button"
                                class="btn btn-outline-secondary product-color-button {{ $index === 0 ? 'active' : '' }}"
                                data-variant-id="{{ $color['variant_id'] }}"
                                data-color-index="{{ $index }}"
                                data-color-image="{{ $color['image'] }}"
                                data-color-price="{{ $color['price'] }}"
                                data-color-stock="{{ $color['stock'] }}"
                                data-color-sizes='@json($color['sizes'])'>
                                {{ $color['name'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4" id="product-sizes-container">
                    <h5 class="mb-2">Select Size</h5>
                    <div id="product-sizes" class="d-flex gap-2 flex-wrap"></div>
                    <div id="size-required-message" class="text-danger small mt-2 d-none">
                        Please select a size to add this color to cart.
                    </div>
                </div>

                <div class="mb-3">
                    <p class="mb-1"><strong>Stock:</strong> <span id="product-variant-stock"></span></p>
                </div>

            @endif

            <form method="POST"
                action="{{ route('cart.add', $product) }}"
                id="add-to-cart-form">

                @csrf

                @if(isset($collection) && $collection)

                    <input
                        type="hidden"
                        name="collection_slug"
                        value="{{ $collection->slug }}">

                @endif

                <input type="hidden" name="product_variant_id" id="selected-product-variant-id" value="">
                <input type="hidden" name="size_id" id="selected-size-id" value="">

                <div class="d-flex align-items-center mb-4">

                    <label class="me-3 fw-bold">
                        Quantity
                    </label>

                    <input
                        type="number"
                        id="product-quantity"
                        name="quantity"
                        value="1"
                        min="1"
                        class="form-control"
                        style="width:90px;">

                </div>

                <div class="d-flex gap-3">

                    <button
                        type="submit"
                        id="add-to-cart-button"
                        class="btn btn-warning btn-lg px-5"
                        {{ $colors->count() ? 'disabled' : '' }}>

                        🛒 Add to Cart

                    </button>

                </div>

            </form>

            <button type="button" class="btn btn-success btn-lg px-5">

                ⚡ Buy Now

            </button>

        <div class="card mt-4">

            <div class="card-body">

                <h5>Delivery</h5>

                <p class="mb-2">
                    🚚 Free Delivery in 3–5 Days
                </p>

                <p class="mb-2">
                    🔄 7 Days Easy Return
                </p>

                <p class="mb-0">
                    💳 Cash on Delivery Available
                </p>

            </div>

        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const colorButtons = document.querySelectorAll('.product-color-button');
                const sizesContainer = document.getElementById('product-sizes');
                const sizesContainerWrapper = document.getElementById('product-sizes-container');
                const sizeRequiredMessage = document.getElementById('size-required-message');
                const priceElement = document.getElementById('product-price');
                const stockElement = document.getElementById('product-variant-stock');
                const selectedSizeIdInput = document.getElementById('selected-size-id');
                const selectedVariantInput = document.getElementById('selected-product-variant-id');
                const addToCartButton = document.getElementById('add-to-cart-button');
                const addToCartForm = document.getElementById('add-to-cart-form');
                const imageElement = document.getElementById('product-detail-image');
                let variants = [];

                // If no color variants exist, enable the button
                if (!colorButtons.length) {
                    addToCartButton.disabled = false;
                    // Form will submit normally for products without variants
                    return;
                }

                colorButtons.forEach((button) => {
                    variants.push({
                        variant_id: button.dataset.variantId,
                        image: button.dataset.colorImage,
                        price: Number(button.dataset.colorPrice),
                        stock: Number(button.dataset.colorStock),
                        sizes: JSON.parse(button.dataset.colorSizes || '[]'),
                    });
                });

                function updateVariant(index) {
                    colorButtons.forEach(btn => btn.classList.remove('active'));
                    colorButtons[index].classList.add('active');

                    const variant = variants[index];
                    priceElement.textContent = '₹' + Number(variant.price).toFixed(2);
                    const sizeButtons = variant.sizes.map((size, idx) => {

                        return `
                            <button
                                type="button"
                                class="btn btn-outline-secondary size-option ${idx === 0 ? 'active' : ''}"
                                data-size-id="${size.id}"
                                data-stock="${size.stock}">
                                ${size.name}
                            </button>
                        `;

                    }).join('');

                    sizesContainer.innerHTML = sizeButtons;
                    if (variant.sizes.length) {
                            stockElement.textContent = variant.sizes[0].stock;
                        } else {
                            stockElement.textContent = 0;
                        }
                    selectedVariantInput.value = variant.variant_id;
                    selectedSizeIdInput.value = variant.sizes.length ? variant.sizes[0].id : '';

                    if (variant.sizes.length) {
                        sizesContainerWrapper.classList.remove('d-none');
                        sizeRequiredMessage.classList.add('d-none');
                    } else {
                        sizesContainerWrapper.classList.add('d-none');
                        selectedSizeIdInput.value = '';
                    }

                    if (imageElement) {
                        if (variant.image) {
                            console.log("Variant object:", variant);
                                console.log("Variant image:", variant.image);
                            imageElement.src = `{{ asset('storage') }}/${variant.image}`;
                        } else {
                            imageElement.src = "{{ asset('uploads/' . $product->image) }}";
                        }
                    }

                    document.querySelectorAll('.size-option').forEach(button => {
                        button.addEventListener('click', function(e) {
                            e.preventDefault();
                            document.querySelectorAll('.size-option').forEach(b => b.classList.remove('active'));
                            this.classList.add('active');
                            selectedSizeIdInput.value = this.dataset.sizeId;

                            stockElement.textContent = this.dataset.stock;

                            updateAddToCartState();
                        });
                    });

                    updateAddToCartState();
                }

                function updateAddToCartState() {
                    const activeVariant = variants.find(v => v.variant_id === selectedVariantInput.value);
                    const requiresSize = activeVariant && activeVariant.sizes.length;
                    const hasSize = selectedSizeIdInput.value !== '';
                    const inStock = Number(stockElement.textContent || 0) > 0;

                    if (!inStock) {
                        addToCartButton.disabled = true;
                        sizeRequiredMessage.classList.add('d-none');
                    } else if (requiresSize && !hasSize) {
                        addToCartButton.disabled = true;
                        sizeRequiredMessage.classList.remove('d-none');
                    } else {
                        addToCartButton.disabled = false;
                        sizeRequiredMessage.classList.add('d-none');
                    }
                }

                // Add form submission validation
                if (addToCartForm) {
                    addToCartForm.addEventListener('submit', function(e) {
                        if (addToCartButton.disabled) {
                            e.preventDefault();
                            alert('Please select all required options before adding to cart.');
                            return false;
                        }
                    });
                }

                if (colorButtons.length) {
                    updateVariant(0);
                }

                colorButtons.forEach((button, index) => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        updateVariant(index);
                    });
                });
            });
        </script>

        <h5>Description</h5>

        <p class="text-muted">
            {{ $product->description }}
        </p>

        <hr class="my-5">


<hr class="my-5">

<h3 class="mb-4">

    Related Products

</h3>

<div class="row mb-5">

    @foreach($relatedProducts as $item)

        <div class="col-md-3 mb-4">

            <div class="card h-100 shadow-sm">

                <a href="/product-details/{{ $item->id }}">

                    <img
                        src="{{ asset('uploads/'.$item->image) }}"
                        class="card-img-top"
                        style="height:220px;object-fit:cover;"
                    >

                </a>

                <div class="card-body text-center">

                    <h6>

                        {{ $item->name }}

                    </h6>

                    <h5 class="text-success">

                        ₹{{ number_format($item->price,2) }}

                    </h5>

                    <a
                        href="/product-details/{{ $item->id }}"
                        class="btn btn-primary w-100"
                    >

                        View Details

                    </a>

                </div>

            </div>

        </div>

    @endforeach

</div>

@endsection

