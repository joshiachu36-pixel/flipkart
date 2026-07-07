<div class="mb-3">
    <a href="{{ url('/category/'.$category->id) }}"
       class="fw-semibold text-dark text-decoration-none d-block cat-root">
        {{ $category->name }}
    </a>

    @if($category->children->count())
        <ul class="list-unstyled mt-2 ps-3">
            @foreach($category->children as $child)
                <li class="mb-1 cat-child">
                    <a href="{{ url('/category/'.$child->id) }}" class="text-decoration-none text-muted">{{ $child->name }}</a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
