@foreach($categories as $category)
    <option value="{{ $category->id }}">{{ $prefix }}{{ $category->name }}</option>
    @if($category->children->count())
        @include('categories.options', [
            'categories' => $category->children,
            'prefix' => $prefix . '-- '
        ])
    @endif
@endforeach
