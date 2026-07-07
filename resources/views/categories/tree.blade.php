<ul class="category-tree">

    @foreach($categories as $category)
    
        <li>

            @if($category->children->count())

                <span class="toggle-btn"
      style="cursor:pointer;color:blue;font-weight:bold;">
    [+]
</span>

            @else

                <span style="display:inline-block;width:25px;">
                </span>

            @endif

                        <strong class="category-name"
                    style="cursor:pointer;"
                    data-id="{{ $category->id }}"
                    data-name="{{ $category->name }}"
                    data-parent="{{ $category->parent_id }}">
                {{ $category->name }}
            </strong>
            @if($category->children->count())

                <div class="children" style="display:none;">

                    @include('categories.tree', [
                        'categories' => $category->children
                    ])

                </div>

            @endif

        </li>

    @endforeach

</ul>