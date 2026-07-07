@extends('layout.admin')

@section('content')

<div class="container mt-4">

    <h2>Edit Category</h2>

    <a href="/categories"
       class="btn btn-secondary mb-3">
        Back
    </a>

    <form action="/category/update/{{ $category->id }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="mb-3">
            <label>Category Name</label>

            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ $category->name }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Parent Category</label>

            <select name="parent_id"
                    class="form-control">

                <option value="">
                    Main Category
                </option>

                @foreach($categories as $parent)

                    <option value="{{ $parent->id }}"
                        {{ $category->parent_id == $parent->id ? 'selected' : '' }}>

                        {{ $parent->name }}

                    </option>

                @endforeach

            </select>
        </div>

        <div class="mb-3">

            <label>Current Image</label>

            <br>

            @if($category->image)

                <img src="{{ asset('uploads/categories/'.$category->image) }}"
                     width="120"
                     class="img-thumbnail">

            @endif

        </div>

        <div class="mb-3">
            <label>Change Image</label>

            <input type="file"
                   name="image"
                   class="form-control">
        </div>

        <button type="submit"
                class="btn btn-primary">

            Update Category

        </button>

    </form>

</div>

@endsection