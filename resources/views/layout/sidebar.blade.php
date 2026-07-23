<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="/" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="{{ asset('adminlte/dist/assets/img/AdminLTELogo.png')}}"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">Flipkart Admin</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->

        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >

              {{-- ── Helper macro: check permission for sidebar ─────────────────────── --}}
              @php
                  /**
                   * $sidebarIsSuperAdmin  – injected by SidebarComposer
                   * $sidebarPermissions   – Collection of slugs injected by SidebarComposer
                   * $sidebarRoleName      – Role name injected by SidebarComposer
                   */
                  $can = function(string $slug) use ($sidebarIsSuperAdmin, $sidebarPermissions): bool {
                      if ($sidebarIsSuperAdmin) return true;
                      return $sidebarPermissions->contains($slug);
                  };
              @endphp

              {{-- ── Dashboard ──────────────────────────────────────────────────────── --}}
              @if($can('dashboard.view'))
              <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-speedometer2"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              @endif

              {{-- ── Seller Approval ────────────────────────────────────────────────── --}}
              @if($can('sellers.view'))
              <li class="nav-item">
                <a href="{{ route('admin.sellers.index') }}" class="nav-link {{ request()->routeIs('admin.sellers.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-person-check-fill"></i>
                  <p>Seller Approval</p>
                </a>
              </li>
              @endif

              {{-- ── Product Approval ───────────────────────────────────────────────── --}}
              @if($can('products.view'))
              <li class="nav-item">
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-patch-check-fill"></i>
                  <p>
                    Product Approval
                    @php
                        $pendingProductCount = \App\Models\Product::whereNotNull('seller_id')->where('approval_status','Pending')->count();
                    @endphp
                    @if($pendingProductCount > 0)
                        <span class="badge bg-warning text-dark float-end me-3">{{ $pendingProductCount }}</span>
                    @endif
                  </p>
                </a>
              </li>
              @endif

              {{-- ── Reports ────────────────────────────────────────────────────────── --}}
              @if($can('reports.view'))
              <li class="nav-item">
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-bar-chart-fill"></i>
                  <p>Reports</p>
                </a>
              </li>
              @endif

              {{-- ── Brands (Parent menu disappears if user has no child permissions) ── --}}
              @if($can('brands.view') || $can('brands.create'))
              <li class="nav-item {{ request()->is('brands*') || request()->is('brand*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('brands*') || request()->is('brand*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-tags-fill"></i>
                  <p>
                    Brands
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @if($can('brands.view'))
                  <li class="nav-item">
                    <a href="/brands" class="nav-link {{ request()->is('brands') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Brand List</p>
                    </a>
                  </li>
                  @endif
                  @if($can('brands.create'))
                  <li class="nav-item">
                    <a href="/brand/create" class="nav-link {{ request()->is('brand/create') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Add Brand</p>
                    </a>
                  </li>
                  @endif
                </ul>
              </li>
              @endif

              {{-- ── Categories (Parent menu disappears if user has no child permissions) --}}
              @if($can('categories.view') || $can('categories.create'))
              <li class="nav-item {{ request()->is('categor*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('categor*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-box-seam-fill"></i>
                  <p>
                    Categories
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @if($can('categories.view'))
                  <li class="nav-item">
                    <a href="/categories" class="nav-link {{ request()->is('categories') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>See Categories</p>
                    </a>
                  </li>
                  @endif
                  @if($can('categories.create'))
                  <li class="nav-item">
                    <a href="/category/create" class="nav-link {{ request()->is('category/create') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Add Category</p>
                    </a>
                  </li>
                  @endif
                </ul>
              </li>
              @endif

              {{-- ── Collections (Parent menu disappears if user has no child permissions) --}}
              @if($can('collections.view') || $can('collections.create'))
              <li class="nav-item {{ request()->is('collections*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('collections*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-collection-fill"></i>
                  <p>
                    Collections
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @if($can('collections.view'))
                  <li class="nav-item">
                    <a href="{{ route('collections.index') }}" class="nav-link {{ request()->routeIs('collections.index') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>All Collections</p>
                    </a>
                  </li>
                  @endif
                  @if($can('collections.create'))
                  <li class="nav-item">
                    <a href="{{ route('collections.create') }}" class="nav-link {{ request()->routeIs('collections.create') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Add Collection</p>
                    </a>
                  </li>
                  @endif
                </ul>
              </li>
              @endif

              {{-- ── Products (Parent menu disappears if user has no child permissions) --}}
              @if($can('products.view') || $can('products.create'))
              <li class="nav-item {{ request()->is('products*') || request()->is('product*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('products*') || request()->is('product*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-box2-fill"></i>
                  <p>
                    Products
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @if($can('products.create'))
                  <li class="nav-item">
                    <a href="/product/create" class="nav-link {{ request()->is('product/create') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Add Product</p>
                    </a>
                  </li>
                  @endif
                  @if($can('products.view'))
                  <li class="nav-item">
                    <a href="/products" class="nav-link {{ request()->is('products') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Products List</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="/product/view" class="nav-link {{ request()->is('product/view') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Product View</p>
                    </a>
                  </li>
                  @endif
                </ul>
              </li>
              @endif

              {{-- ── Variants Setup ────────────────────────────────────────────────── --}}
              @if($sidebarIsSuperAdmin || $can('variants.view') || $can('variants.create'))
              <li class="nav-item {{ request()->is('colors*') || request()->is('sizes*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('colors*') || request()->is('sizes*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-palette-fill"></i>
                  <p>
                    Variants Setup
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('colors.index') }}" class="nav-link {{ request()->routeIs('colors.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Colors</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('sizes.index') }}" class="nav-link {{ request()->routeIs('sizes.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Sizes</p>
                    </a>
                  </li>
                </ul>
              </li>
              @endif

              {{-- ── Settings (Staff & Roles) — Disappears if neither is accessible ── --}}
              @if(
                  $sidebarIsSuperAdmin ||
                  $can('staff.view') || $can('roles.view')
              )
              <li class="nav-item {{ request()->routeIs('admin.roles.*') || request()->routeIs('admin.staff.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('admin.roles.*') || request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-gear-fill"></i>
                  <p>
                    Settings
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @if($sidebarIsSuperAdmin || $can('roles.view'))
                  <li class="nav-item">
                    <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Roles</p>
                    </a>
                  </li>
                  @endif
                  @if($sidebarIsSuperAdmin || $can('staff.view'))
                  <li class="nav-item">
                    <a href="{{ route('admin.staff.index') }}" class="nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Staff</p>
                    </a>
                  </li>
                  @endif
                </ul>
              </li>
              @endif

              {{-- ── Shop Frontend ─────────────────────────────────────────────────── --}}
              @if($sidebarIsSuperAdmin || $can('dashboard.view'))
              <li class="nav-item">
                <a href="/shop" class="nav-link">
                  <i class="nav-icon bi bi-shop"></i>
                  <p>Shop Frontend</p>
                </a>
              </li>
              @endif

            </ul>
            <!--end::Sidebar Menu-->

            <!-- Dynamic Sidebar Footer Panel Title -->
            <div class="p-3 mt-3 border-top border-secondary border-opacity-25">
              <a
                href="#"
                class="btn btn-sm btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2"
              >
                <i class="bi bi-shield-lock" aria-hidden="true"></i>
                <span>{{ $sidebarRoleName ?? 'Admin' }} Panel</span>
              </a>
            </div>
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>