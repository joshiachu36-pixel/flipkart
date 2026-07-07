@extends('layout.admin')

@section('content')

<div class="content-header">
    <div class="container-fluid">

        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Add Category</h1>
            </div>
        </div>

    </div>
</div>

<section class="content">

    <div class="container-fluid">

    @if(session('error'))

            <div class="alert alert-danger">

                {{ session('error') }}

            </div>

        @endif

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif

    <div class="row">

        <!-- Left Side -->
        <div class="col-md-4">

            <div class="card card-primary">

                <div class="card-header">
                    <h3 class="card-title">
                        Create New Category
                    </h3>
                </div>

                <form action="/category/store"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <div class="card-body">

                        <div class="form-group">
                            <label>Category Name</label>

                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   placeholder="Enter Category Name">
                        </div>

                        <div class="form-group">
                            <label>Parent Category</label>

                            <select name="parent_id"
                                    class="form-control">

                                <option value="">
                                    Main Category
                                </option>

                            @foreach($allCategories as $category)

                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>

                            @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Category Image</label>

                            <input type="file"
                                   name="image"
                                   class="form-control">
                        </div>

                    </div>

                    <div class="card-footer">

                        <button type="submit"
                                class="btn btn-primary">
                            Save Category
                        </button>

                    </div>

                </form>

            </div>

        </div>

        <!-- Right Side -->
        <div class="col-md-8">

            <div class="card card-success">

                <div class="card-header">

    <h3 class="card-title">
        Category Tree
    </h3>

    

</div>

                <div class="card-body">

                    @include('categories.tree', [
                    'categories' => $treeCategories
                ])
            </div>

            </div>

        </div>

    </div>

</div>

</section>

<!-- Category Modal -->

<div class="modal fade"
     id="categoryModal"
     tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Edit Category
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal">
                    &times;
                </button>

            </div>

            <form id="categoryForm"
                  method="POST">

                @csrf

            <div class="modal-body">

                <div class="form-group">

                    <label>
                        Category Name
                    </label>

                    <input type="text"
                        name="name"
                        id="modalCategoryName"
                        class="form-control">

                </div>

                <div class="form-group mt-3">

                    <label>
                        Parent Category
                    </label>

                    <select name="parent_id"
                            id="modalParentCategory"
                            class="form-control">

                        <option value="">
                            Main Category
                        </option>

                        @foreach($allCategories as $cat)

                            <option value="{{ $cat->id }}">
                                {{ $cat->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

                <div class="modal-footer">

                    <button type="submit"
                            class="btn btn-primary">
                        Update
                    </button>

                    <a href="#"
                       id="deleteCategoryBtn"
                       class="btn btn-danger">
                        Delete
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>
<script>

document.querySelectorAll('.category-name')
    .forEach(function(item){

        item.addEventListener('click', function(){

            let id = this.dataset.id;
            let name = this.dataset.name;
            let parent = this.dataset.parent;

            document.getElementById('modalCategoryName')
                .value = name;

            document.getElementById('modalParentCategory')
                .value = parent;

            document.getElementById('categoryForm')
                .action = '/category/update/' + id;

            document.getElementById('deleteCategoryBtn')
                .href = '/category/delete/' + id;

            let modal =
                new bootstrap.Modal(
                    document.getElementById('categoryModal')
                );

            modal.show();

        });

    });

</script>
<style>

.category-tree ul
{
    margin-left:20px;
}

.category-tree li
{
    list-style:none;
    margin:6px 0;
}

.toggle-btn
{
    cursor:pointer;
    color:#007bff;
    font-weight:bold;
    margin-right:5px;
}

</style>
<script>

document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.toggle-btn').forEach(function(btn){

        btn.addEventListener('click', function(){

            let children = this.parentElement.querySelector('.children');

            if (!children)
                return;

            if (children.style.display === 'none')
            {
                children.style.display = 'block';
                this.innerHTML = '[-]';
            }
            else
            {
                children.style.display = 'none';
                this.innerHTML = '[+]';
            }

        });

    });

});

</script>

<script>

document.getElementById('expandAll')
    .addEventListener('click', function(){

        document.querySelectorAll('.children')
            .forEach(function(child){

                child.style.display = 'block';

            });

        document.querySelectorAll('.toggle-btn')
            .forEach(function(btn){

                btn.innerHTML = '[-]';

            });

    });

document.getElementById('collapseAll')
    .addEventListener('click', function(){

        document.querySelectorAll('.children')
            .forEach(function(child){

                child.style.display = 'none';

            });

        document.querySelectorAll('.toggle-btn')
            .forEach(function(btn){

                btn.innerHTML = '[+]';

            });

    });

</script>

<script>

setTimeout(function(){

    document.querySelectorAll('.alert')
        .forEach(function(alert){

            alert.style.display = 'none';

        });

}, 3000);

</script>



@endsection