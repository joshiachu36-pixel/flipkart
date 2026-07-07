@php $openIds = $openIds ?? []; $level = $level ?? 0; @endphp
<ul class="list-unstyled mb-0">
    @foreach($categories as $category)
        @php $isOpen = in_array($category->id, $openIds); @endphp
        <li class="mb-2">
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ url('/category/'.$category->id) }}"
                   class="text-decoration-none text-dark fw-semibold @if($level==0) cat-root @elseif($level==1) cat-child @else cat-grandchild @endif">
                    {{ $category->name }}
                </a>

                @if($category->childrenRecursive->count())
                    <button class="btn btn-link btn-sm p-0 toggle-icon"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#cat-{{ $category->id }}"
                            aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                            aria-controls="cat-{{ $category->id }}">
                        {{ $isOpen ? '-' : '+' }}
                    </button>
                @endif
            </div>

            @if($category->childrenRecursive->count())
                <div class="collapse mt-2 @if($isOpen) show @endif" id="cat-{{ $category->id }}">
                    @include('categories.sidebar-tree', [
                        'categories' => $category->childrenRecursive,
                        'selectedCategoryId' => $selectedCategoryId ?? null,
                        'openIds' => $openIds,
                        'level' => $level + 1,
                    ])
                </div>
            @endif
        </li>
    @endforeach
</ul>
