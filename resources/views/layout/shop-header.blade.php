

    <style>

.nav-item.dropdown:hover .dropdown-menu{
    display:block;
    margin-top:0;
}

.dropdown-menu{
    border-radius:0;
}

.dropdown-menu .col-md-3{
    min-width:220px;
}

.dropdown-menu a:hover{
    color:#2874f0 !important;
}

/* Category level styles */
.cat-root { font-weight:700; color:#222; }
.cat-child { color:#444; padding-left:8px; }
.cat-grandchild { color:#666; padding-left:16px; font-size:0.95rem; }
.toggle-icon { width:28px; height:24px; }

</style>

<!-- Top Header -->
<div class="bg-primary shadow">

    <div class="container-fluid py-3">

        <div class="row align-items-center">

            <!-- Logo -->
            <div class="col-md-2">

                <h2 class="mb-0 fw-bold text-warning">
                    Flipkart
                </h2>

            </div>

            <!-- Search -->
            <div class="col-md-7">

                <form method="GET" action="{{ route('product.search') }}">

                    <div class="input-group">

                        <span class="input-group-text bg-white">

                            <i class="bi bi-search"></i>

                        </span>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Search for Products, Brands and More">

                        <button class="btn btn-outline-light" type="submit">
                            Search
                        </button>

                    </div>

                </form>

            </div>

            <!-- Customer -->

<div class="col-md-1 text-center">

    @if(session()->has('customer_id'))

        <div class="dropdown">

            <a href="#"
               class="text-white text-decoration-none dropdown-toggle"
               data-bs-toggle="dropdown">

                <i class="bi bi-person-circle"></i>

                Hi, {{ session('customer_name') }}

            </a>

            <ul class="dropdown-menu">

                <li>

                    <a class="dropdown-item"
                    href="{{ route('customer.profile') }}">
                        My Profile
                    </a>

                </li>

                <li>

                    <a class="dropdown-item" href="#">

                        My Orders

                    </a>

                </li>

                <li>

                    <a class="dropdown-item"
                    href="{{ route('wishlist.index') }}">

                        Wishlist

                    </a>

                </li>

                <li><hr class="dropdown-divider"></li>

                <li>

                    <form action="{{ route('customer.logout') }}"
                          method="POST">

                        @csrf

                        <button
                            type="submit"
                            class="dropdown-item">

                            Logout

                        </button>

                    </form>

                </li>

            </ul>

        </div>

    @else

        <a href="{{ route('customer.login') }}"
           class="text-white text-decoration-none">

            <i class="bi bi-person"></i>

            Login

        </a>

    @endif

</div>

            <!-- Cart -->
            <div class="col-md-2 text-center">

                <a href="{{ route('cart.index') }}"
            class="btn btn-warning">

                🛒 Cart

            </a>

            </div>

        </div>

    </div>

</div>

<!-- Category Menu -->
<!-- Category Menu -->
<div class="bg-white border-bottom shadow-sm">

    <div class="container-fluid">

        <ul class="nav justify-content-center">

            @foreach($headerCategories as $category)

                <li class="nav-item dropdown position-static">

                    <a class="nav-link fw-bold text-dark px-4"
                       href="{{ url('/category/'.$category->id) }}"
                       data-bs-toggle="dropdown">

                        {{ $category->name }}
                        <i class="bi bi-chevron-down"></i>

                    </a>

                    @if($category->children->count())

                        <div class="dropdown-menu w-100 border-0 shadow p-4">

                            <div class="row">

                                @foreach($category->children as $child)

                                    <div class="col-md-3">

                                        @include('categories.mega-menu-item', ['category' => $child])

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @endif

                </li>

            @endforeach

            <li class="nav-item dropdown">

            <a class="nav-link fw-bold text-dark px-4 dropdown-toggle"
                role="button"
                data-bs-toggle="dropdown">

                    Collections

            </a>

            <ul class="dropdown-menu">

                @forelse($headerCollections as $collection)

                        <li>

                            <a class="dropdown-item
                                {{ optional($selectedCollection)->id == $collection->id ? 'active' : '' }}"
                            href="{{ route('shop.collection', $collection->slug) }}">

                                {{ $collection->name }}

                            </a>

                        </li>

                @empty

                    <li>

                        <span class="dropdown-item text-muted">

                            No Collections Found

                        </span>

                    </li>

                @endforelse

            </ul>

        </li>

        </ul>

    </div>

</div>

