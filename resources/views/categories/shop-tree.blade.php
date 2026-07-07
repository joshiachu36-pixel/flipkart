@foreach($categories as $category)
    <li>
        <a href="{{ url('/category/'.$category->id) }}"
   class="text-decoration-none text-dark">
            {{ $category->name }}
        </a>
        @if($category->children->count())
            <ul class="ms-3 mt-1">
                @include('categories.shop-tree', ['categories' => $category->children])
            </ul>
        @endif
    </li>
@endforeach
