<li>

    <a href="{{ url('/category/'.$category->id) }}"
       class="text-decoration-none">

        {{ $category->name }}

    </a>

    @if($category->childrenRecursive->count())

        <ul class="ms-3">

            @foreach($category->childrenRecursive as $child)

                @include('categories.menu-item', [
                    'category' => $child
                ])

            @endforeach

        </ul>

    @endif

</li>