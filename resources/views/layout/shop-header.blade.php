<style>
/* ── Header premium overrides ───────────────────── */
.shop-topbar {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
    padding: 0;
    position: sticky;
    top: 0;
    z-index: 1050;
    box-shadow: 0 2px 16px rgba(0,0,0,.25);
}
.shop-topbar-inner {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 12px 24px;
    flex-wrap: wrap;
}

/* Logo */
.shop-logo {
    font-size: 1.5rem;
    font-weight: 800;
    color: #f9c13a;
    letter-spacing: -.5px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    flex-shrink: 0;
}
.shop-logo:hover { color: #f9c13a; text-decoration: none; }
.shop-logo-dot { color: #2874f0; }

/* Search */
.shop-search-wrap {
    flex: 1 1 340px;
    max-width: 640px;
}
.shop-search-form {
    display: flex;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,.2);
}
.shop-search-input {
    flex: 1;
    border: none;
    padding: 10px 16px;
    font-size: .9rem;
    font-family: 'Inter', sans-serif;
    background: #fff;
    color: #1a1a2e;
    outline: none;
}
.shop-search-input::placeholder { color: #9aa5b8; }
.shop-search-btn {
    background: #2874f0;
    border: none;
    color: #fff;
    padding: 10px 20px;
    font-size: .9rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: background .18s;
    white-space: nowrap;
}
.shop-search-btn:hover { background: #1558c0; }

/* Nav actions */
.shop-nav-actions {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
    margin-left: auto;
}
.shop-nav-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,.85);
    text-decoration: none;
    border: none;
    background: transparent;
    padding: 6px 10px;
    border-radius: 8px;
    transition: background .18s, color .18s;
    font-size: .7rem;
    font-weight: 600;
    gap: 2px;
    cursor: pointer;
    line-height: 1;
}
.shop-nav-btn i { font-size: 1.2rem; }
.shop-nav-btn:hover { background: rgba(255,255,255,.1); color: #fff; }
.shop-nav-btn.active-user { color: #f9c13a; }

/* Cart button */
.shop-cart-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #2874f0;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 9px 18px;
    font-size: .85rem;
    font-weight: 700;
    text-decoration: none;
    transition: background .18s, transform .18s;
    cursor: pointer;
    white-space: nowrap;
}
.shop-cart-btn:hover { background: #1558c0; color: #fff; transform: translateY(-1px); }
.shop-cart-btn i { font-size: 1.05rem; }

/* User dropdown */
.user-dropdown-toggle {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,.85);
    text-decoration: none;
    border: none;
    background: transparent;
    padding: 6px 10px;
    border-radius: 8px;
    transition: background .18s, color .18s;
    font-size: .7rem;
    font-weight: 600;
    gap: 2px;
    cursor: pointer;
    line-height: 1;
}
.user-dropdown-toggle:hover,
.user-dropdown-toggle:focus { background: rgba(255,255,255,.1); color: #f9c13a; }
.user-dropdown-toggle i { font-size: 1.2rem; }

/* ── Category mega-menu bar ─────────────────────── */
.shop-catbar {
    background: rgba(255,255,255,.04);
    border-top: 1px solid rgba(255,255,255,.08);
    border-bottom: 1px solid rgba(255,255,255,.06);
}
.shop-catbar .nav { flex-wrap: nowrap; overflow-x: auto; scrollbar-width: none; }
.shop-catbar .nav::-webkit-scrollbar { display: none; }
.shop-catbar .nav-link {
    color: rgba(255,255,255,.78);
    font-size: .83rem;
    font-weight: 600;
    padding: 10px 18px;
    white-space: nowrap;
    transition: color .18s, background .18s;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.shop-catbar .nav-link:hover,
.shop-catbar .nav-link:focus { color: #fff; background: rgba(255,255,255,.08); }
.shop-catbar .nav-link .bi-chevron-down { font-size: .65rem; opacity: .7; }

/* Mega-menu dropdown */
.shop-catbar .dropdown-menu {
    border-radius: 14px;
    border: 1px solid rgba(0,0,0,.07);
    box-shadow: 0 16px 48px rgba(0,0,0,.12), 0 4px 16px rgba(0,0,0,.06);
    padding: 20px;
    background: #fff;
}
.shop-catbar .dropdown-menu a { color: #444; font-size: .87rem; }
.shop-catbar .dropdown-menu a:hover { color: #2874f0 !important; }

/* Collections dropdown */
.collections-pill {
    background: linear-gradient(135deg, #ff6161, #ff9500);
    color: #fff !important;
    border-radius: 50px;
    padding: 6px 18px !important;
    font-weight: 700 !important;
    font-size: .8rem;
    margin-left: 6px;
}
.collections-pill:hover { opacity: .9; background: linear-gradient(135deg, #ff6161, #ff9500); color: #fff !important; }

/* Dropdown items in collections */
.shop-catbar .dropdown-item {
    border-radius: 8px;
    font-size: .87rem;
    font-weight: 500;
    padding: 8px 14px;
    transition: background .15s, color .15s;
}
.shop-catbar .dropdown-item:hover { background: #e8f1fe; color: #2874f0; }
.shop-catbar .dropdown-item.active { background: #2874f0; color: #fff; }
</style>

<header class="shop-topbar">
    <div class="shop-topbar-inner container-fluid">

        {{-- ── Logo ── --}}
        <a href="/shop" class="shop-logo">
            <i class="bi bi-bag-heart-fill" style="color:#2874f0;font-size:1.3rem;"></i>
            Flipkart<span class="shop-logo-dot">.</span>
        </a>

        {{-- ── Search ── --}}
        <div class="shop-search-wrap">
            <form method="GET" action="{{ route('product.search') }}" class="shop-search-form">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="shop-search-input"
                    placeholder="Search for Products, Brands and More"
                    autocomplete="off"
                    aria-label="Search products">
                <button type="submit" class="shop-search-btn">
                    <i class="bi bi-search"></i>
                    Search
                </button>
            </form>
        </div>

        {{-- ── Nav actions ── --}}
        <div class="shop-nav-actions">

            {{-- Customer account --}}
            @if(session()->has('customer_id'))
                <div class="dropdown">
                    <a href="#"
                       class="user-dropdown-toggle dropdown-toggle"
                       data-bs-toggle="dropdown"
                       aria-expanded="false"
                       title="My Account">
                        <i class="bi bi-person-circle"></i>
                        <span>{{ Str::limit(session('customer_name'), 8) }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width:180px;">
                        <li>
                            <a class="dropdown-item" href="{{ route('customer.profile') }}">
                                <i class="bi bi-person me-2 text-primary"></i>My Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-bag me-2 text-primary"></i>My Orders
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('wishlist.index') }}">
                                <i class="bi bi-heart me-2 text-danger"></i>Wishlist
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('customer.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('customer.login') }}" class="shop-nav-btn" title="Login">
                    <i class="bi bi-person"></i>
                    <span>Login</span>
                </a>
            @endif

            {{-- Wishlist shortcut --}}
            @if(session()->has('customer_id'))
                <a href="{{ route('wishlist.index') }}" class="shop-nav-btn" title="Wishlist">
                    <i class="bi bi-heart"></i>
                    <span>Wishlist</span>
                </a>
            @endif

            {{-- Cart --}}
            <a href="{{ route('cart.index') }}" class="shop-cart-btn" title="View Cart">
                <i class="bi bi-cart3"></i>
                Cart
            </a>

        </div>
    </div>

    {{-- ── Category mega-menu bar ── --}}
    <div class="shop-catbar">
        <div class="container-fluid px-4">
            <ul class="nav align-items-center py-1">

                @foreach($headerCategories as $category)
                    <li class="nav-item dropdown position-static">
                        <a class="nav-link"
                           href="{{ url('/category/'.$category->id) }}"
                           data-bs-toggle="dropdown">
                            {{ $category->name }}
                            <i class="bi bi-chevron-down"></i>
                        </a>
                        @if($category->children->count())
                            <div class="dropdown-menu w-100 border-0">
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

                {{-- Collections dropdown --}}
                <li class="nav-item dropdown ms-auto">
                    <a class="nav-link collections-pill dropdown-toggle"
                       role="button"
                       data-bs-toggle="dropdown">
                        <i class="bi bi-stars me-1"></i>Collections
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width:200px;">
                        @forelse($headerCollections as $collection)
                            <li>
                                <a class="dropdown-item {{ optional($selectedCollection)->id == $collection->id ? 'active' : '' }}"
                                   href="{{ route('shop.collection', $collection->slug) }}">
                                    <i class="bi bi-collection me-2"></i>{{ $collection->name }}
                                </a>
                            </li>
                        @empty
                            <li><span class="dropdown-item text-muted">No Collections</span></li>
                        @endforelse
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</header>
