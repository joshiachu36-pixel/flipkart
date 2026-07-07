@extends('layout.admin')

@section('content')

<div class="container mt-4">

    <h2>Product List</h2>

    <a href="/products"
   class="btn btn-success mb-3">
    Back to Product
</a>

    <h2>Add Product</h2>

    <form action="/product/store"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="mb-3">
            <label>Product Name</label>
            <input type="text"
                   class="form-control"
                   name="name">
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea class="form-control"
                      name="description"></textarea>
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="text"
                   class="form-control"
                   name="price">
        </div>

        <div class="mb-3">

            <label class="form-label">

                Original Price

            </label>

            <input
                type="number"
                step="0.01"
                name="original_price"
                class="form-control">

        </div>

        <div class="mb-3">

            <label class="form-label">

                Stock

            </label>

            <input
                type="number"
                name="stock"
                class="form-control"
                value="0">

        </div>

        <div class="mb-3">

            <label class="form-label">

                Status

            </label>

            <select
                name="status"
                class="form-select">

                <option value="1">

                    Active

                </option>

                <option value="0">

                    Inactive

                </option>

            </select>

        </div>

        <div class="mb-3">
            <label>Image</label>
            <input type="file"
                   class="form-control"
                   name="image">
        </div>

        <div class="mb-3">
            <label>Category</label>

            <select name="category_id" class="form-control">
                <option value="">Select Category</option>

                @include('categories.options', [
                    'categories' => $categories,
                    'prefix' => ''
                ])
            </select>
        </div>

        <div class="mb-3">

        

    <label class="form-label">
        Colors
    </label>

    <div class="dropdown">

        <button
            class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
            type="button"
            data-bs-toggle="dropdown"
            id="colorButton">

            Select Colors

        </button>

        <div class="dropdown-menu p-3 w-100">

            <input
                type="text"
                class="form-control mb-2"
                id="colorSearch"
                placeholder="Search Colors">

            <div
                id="colorList"
                style="max-height:250px; overflow-y:auto;">

                @foreach($colors as $color)

                    <div class="form-check color-item">

                        <input
                            class="form-check-input color-checkbox"
                            type="checkbox"
                            name="colors[]"
                            value="{{ $color->id }}"
                            id="color{{ $color->id }}">

                        <label
                            class="form-check-label"
                            for="color{{ $color->id }}">

                            {{ $color->name }}

                        </label>

                    </div>

                @endforeach

            </div>

        </div>

    </div>

</div>        <div class="mb-3">

                <label class="form-label">

                    Brand

                </label>

                <select
                    name="brand_id"
                    class="form-control">

                    <option value="">

                        Select Brand

                    </option>

                    @foreach($brands as $brand)

                        <option value="{{ $brand->id }}">

                            {{ $brand->name }}

                        </option>

                    @endforeach

                </select>

        </div>

       <div class="mb-3">

    <label class="form-label">
        Collections
    </label>

    <div class="dropdown">

        <button
            class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
            type="button"
            data-bs-toggle="dropdown"
            id="collectionButton">

            Select Collections

        </button>

        <div class="dropdown-menu p-3 w-100">

            <input
                type="text"
                class="form-control mb-2"
                id="collectionSearch"
                placeholder="Search Collections">

            <div style="max-height:250px; overflow-y:auto;"
                 id="collectionList">

                @foreach($collections as $collection)

                    <div class="form-check collection-item">

                        <input
                            class="form-check-input collection-checkbox"
                            type="checkbox"
                            name="collections[]"
                            value="{{ $collection->id }}"
                            id="collection{{ $collection->id }}">

                        <label
                            class="form-check-label"
                            for="collection{{ $collection->id }}">

                            {{ $collection->name }}

                        </label>

                    </div>

                @endforeach

            </div>

        </div>

    </div>

</div>

        <button class="btn btn-primary">
            Save Product
        </button>

    </form>

</div>

<!-- Color Variant Modal -->


@endsection

<script>

document.addEventListener('DOMContentLoaded', function () {

    const search = document.getElementById('colorSearch');
    const button = document.getElementById('colorButton');

    const checkboxes =
        document.querySelectorAll('.color-checkbox');

    // Search
    search.addEventListener('keyup', function () {

        let value = this.value.toLowerCase();

        document.querySelectorAll('.color-item')
            .forEach(function(item){

                item.style.display =
                    item.innerText
                        .toLowerCase()
                        .includes(value)
                    ? ''
                    : 'none';

            });

    });

    // Update Button Text
    function updateButton(){

        let selected =
            document.querySelectorAll(
                '.color-checkbox:checked'
            ).length;

        if(selected > 0){

            button.innerHTML =
                selected + ' Color(s) Selected';

        }else{

            button.innerHTML =
                'Select Colors';

        }

    }

    checkboxes.forEach(function(cb){

        cb.addEventListener(
            'change',
            updateButton
        );

    });

});

</script>

