<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE v4 | Dashboard</title>

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant"
    />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="{{ asset('adminlte/dist/css/adminlte.css')}}" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media = 'all'"
    />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}" />
    <!--end::Required Plugin(AdminLTE)-->

    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />

    <!-- jsvectormap -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    />


    @stack('styles')
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      @include('layout.header')
      <!--end::Header-->
      <!--begin::Sidebar-->
      @include('layout.sidebar')
      <!--end::Sidebar-->
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">The admin</h3>
              </div>

            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <div class="content-wrapper">
            @hasSection('content')
                @yield('content')
            @else
                {{-- ── Default Dashboard for Root URL / ────────────────────────────────── --}}
                <div class="container-fluid py-3">
                    {{-- Welcome Banner --}}
                    @php
                        $userName = 'Admin';
                        $roleName = 'Super Admin';
                        if (auth()->guard('staff')->check()) {
                            $staffUser = auth()->guard('staff')->user();
                            $userName = $staffUser->name;
                            $roleName = $staffUser->role ? $staffUser->role->name : 'Staff';
                        } elseif (auth()->guard('web')->check()) {
                            $userName = auth()->guard('web')->user()->name ?? 'Super Admin';
                            $roleName = 'Super Admin';
                        }
                    @endphp
                    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-primary text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div>
                                <h4 class="fw-bold mb-1">
                                    <i class="bi bi-speedometer2 me-2"></i>Welcome back, {{ $userName }}!
                                </h4>
                                <p class="mb-0 text-white-50 small">
                                    Role: <strong>{{ $roleName }}</strong> —
                                    @if(is_super_admin())
                                        Full unrestricted access across all marketplace modules.
                                    @else
                                        Dashboard & menu widgets tailored to your assigned role permissions.
                                    @endif
                                </p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-white text-primary px-3 py-2 fw-semibold fs-6 shadow-sm">
                                    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('l, F j, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Permission-Aware Stat Cards --}}
                    <div class="row g-3">

                        {{-- 1. Seller Management Widget --}}
                        @if(can_do('sellers.view'))
                        @php
                            $totalSellers   = \App\Models\Seller::count();
                            $pendingSellers = \App\Models\Seller::where('status', 'Pending')->count();
                        @endphp
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small fw-semibold text-uppercase">Sellers</div>
                                        <div class="fs-3 fw-bold text-dark mt-1">{{ $totalSellers }}</div>
                                        @if($pendingSellers > 0)
                                            <span class="badge bg-warning text-dark mt-1"><i class="bi bi-clock-history me-1"></i>{{ $pendingSellers }} Pending Approval</span>
                                        @else
                                            <span class="small text-success mt-1"><i class="bi bi-check-circle me-1"></i>All clear</span>
                                        @endif
                                    </div>
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:52px;height:52px">
                                        <i class="bi bi-shop fs-4"></i>
                                    </div>
                                </div>
                                <div class="card-footer bg-light border-0 py-2 text-end">
                                    <a href="{{ route('admin.sellers.index') }}" class="small fw-semibold text-decoration-none">Manage Sellers <i class="bi bi-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- 2. Product Management Widget --}}
                        @if(can_do('products.view'))
                        @php
                            $totalProducts   = \App\Models\Product::count();
                            $pendingProducts = \App\Models\Product::whereNotNull('seller_id')->where('approval_status', 'Pending')->count();
                        @endphp
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small fw-semibold text-uppercase">Products</div>
                                        <div class="fs-3 fw-bold text-dark mt-1">{{ $totalProducts }}</div>
                                        @if($pendingProducts > 0)
                                            <span class="badge bg-warning text-dark mt-1"><i class="bi bi-hourglass-split me-1"></i>{{ $pendingProducts }} Pending Approval</span>
                                        @else
                                            <span class="small text-success mt-1"><i class="bi bi-check-circle me-1"></i>All approved</span>
                                        @endif
                                    </div>
                                    <div class="bg-info bg-opacity-10 text-info rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:52px;height:52px">
                                        <i class="bi bi-box2-fill fs-4"></i>
                                    </div>
                                </div>
                                <div class="card-footer bg-light border-0 py-2 text-end">
                                    <a href="{{ route('admin.products.index') }}" class="small fw-semibold text-decoration-none text-info">Manage Products <i class="bi bi-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- 3. Reports & Analytics Widget --}}
                        @if(can_do('reports.view'))
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small fw-semibold text-uppercase">Analytics</div>
                                        <div class="fs-3 fw-bold text-dark mt-1">Reports</div>
                                        <span class="small text-muted mt-1"><i class="bi bi-bar-chart-fill me-1"></i>Marketplace Intelligence</span>
                                    </div>
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:52px;height:52px">
                                        <i class="bi bi-graph-up-arrow fs-4"></i>
                                    </div>
                                </div>
                                <div class="card-footer bg-light border-0 py-2 text-end">
                                    <a href="{{ route('admin.reports.index') }}" class="small fw-semibold text-decoration-none text-success">View Reports <i class="bi bi-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- 4. Staff Members Widget --}}
                        @if(can_do('staff.view'))
                        @php
                            $totalStaff = \App\Models\Staff::where('status', 'Active')->count();
                        @endphp
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small fw-semibold text-uppercase">Active Staff</div>
                                        <div class="fs-3 fw-bold text-dark mt-1">{{ $totalStaff }}</div>
                                        <span class="small text-muted mt-1"><i class="bi bi-people-fill me-1"></i>Team Members</span>
                                    </div>
                                    <div class="bg-purple bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:52px;height:52px">
                                        <i class="bi bi-person-badge-fill fs-4"></i>
                                    </div>
                                </div>
                                <div class="card-footer bg-light border-0 py-2 text-end">
                                    <a href="{{ route('admin.staff.index') }}" class="small fw-semibold text-decoration-none">Manage Staff <i class="bi bi-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- 5. Roles & RBAC Widget --}}
                        @if(can_do('roles.view'))
                        @php
                            $totalRoles = \App\Models\Role::count();
                        @endphp
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small fw-semibold text-uppercase">Roles & RBAC</div>
                                        <div class="fs-3 fw-bold text-dark mt-1">{{ $totalRoles }}</div>
                                        <span class="small text-muted mt-1"><i class="bi bi-shield-lock-fill me-1"></i>Access Profiles</span>
                                    </div>
                                    <div class="bg-dark bg-opacity-10 text-dark rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:52px;height:52px">
                                        <i class="bi bi-key-fill fs-4"></i>
                                    </div>
                                </div>
                                <div class="card-footer bg-light border-0 py-2 text-end">
                                    <a href="{{ route('admin.roles.index') }}" class="small fw-semibold text-decoration-none text-dark">Manage Roles <i class="bi bi-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- 6. Categories Widget --}}
                        @if(can_do('categories.view'))
                        @php
                            $totalCategories = \App\Models\Category::count();
                        @endphp
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small fw-semibold text-uppercase">Categories</div>
                                        <div class="fs-3 fw-bold text-dark mt-1">{{ $totalCategories }}</div>
                                        <span class="small text-muted mt-1"><i class="bi bi-box-seam me-1"></i>Catalog Taxonomy</span>
                                    </div>
                                    <div class="bg-teal bg-opacity-10 text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:52px;height:52px">
                                        <i class="bi bi-box-seam-fill fs-4"></i>
                                    </div>
                                </div>
                                <div class="card-footer bg-light border-0 py-2 text-end">
                                    <a href="/categories" class="small fw-semibold text-decoration-none text-success">View Categories <i class="bi bi-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- 7. Brands Widget --}}
                        @if(can_do('brands.view'))
                        @php
                            $totalBrands = \App\Models\Brand::count();
                        @endphp
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small fw-semibold text-uppercase">Brands</div>
                                        <div class="fs-3 fw-bold text-dark mt-1">{{ $totalBrands }}</div>
                                        <span class="small text-muted mt-1"><i class="bi bi-tags-fill me-1"></i>Brand Directory</span>
                                    </div>
                                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:52px;height:52px">
                                        <i class="bi bi-tags-fill fs-4"></i>
                                    </div>
                                </div>
                                <div class="card-footer bg-light border-0 py-2 text-end">
                                    <a href="/brands" class="small fw-semibold text-decoration-none text-warning">View Brands <i class="bi bi-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- 8. Collections Widget --}}
                        @if(can_do('collections.view'))
                        @php
                            $totalCollections = \App\Models\Collection::count();
                        @endphp
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-muted small fw-semibold text-uppercase">Collections</div>
                                        <div class="fs-3 fw-bold text-dark mt-1">{{ $totalCollections }}</div>
                                        <span class="small text-muted mt-1"><i class="bi bi-collection-fill me-1"></i>Featured Collections</span>
                                    </div>
                                    <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:52px;height:52px">
                                        <i class="bi bi-collection-fill fs-4"></i>
                                    </div>
                                </div>
                                <div class="card-footer bg-light border-0 py-2 text-end">
                                    <a href="{{ route('collections.index') }}" class="small fw-semibold text-decoration-none text-secondary">View Collections <i class="bi bi-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            @endif
          </div>
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
      @include('layout.footer')
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

        // Disable OverlayScrollbars on mobile devices to prevent touch interference
        const isMobile = window.innerWidth <= 992;

        if (
          sidebarWrapper &&
          OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
          !isMobile
        ) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure--><!--begin::Color Mode Toggle (#6010)-->
    <script>
      (() => {
        'use strict';

        const STORAGE_KEY = 'lte-theme';

        const getStoredTheme = () => localStorage.getItem(STORAGE_KEY);
        const setStoredTheme = (theme) => localStorage.setItem(STORAGE_KEY, theme);

        const prefersDark = () => globalThis.matchMedia('(prefers-color-scheme: dark)').matches;

        const getPreferredTheme = () => {
          const stored = getStoredTheme();
          if (stored) return stored;
          return prefersDark() ? 'dark' : 'light';
        };

        const setTheme = (theme) => {
          const resolved = theme === 'auto' ? (prefersDark() ? 'dark' : 'light') : theme;
          document.documentElement.setAttribute('data-bs-theme', resolved);
        };

        setTheme(getPreferredTheme());

        const showActiveTheme = (theme) => {
          // Highlight the active dropdown option
          document.querySelectorAll('[data-bs-theme-value]').forEach((el) => {
            el.classList.remove('active');
            el.setAttribute('aria-pressed', 'false');
            const check = el.querySelector('.bi-check-lg');
            if (check) check.classList.add('d-none');
          });
          const active = document.querySelector(`[data-bs-theme-value="${theme}"]`);
          if (active) {
            active.classList.add('active');
            active.setAttribute('aria-pressed', 'true');
            const check = active.querySelector('.bi-check-lg');
            if (check) check.classList.remove('d-none');
          }
          // Sync the topbar trigger icon
          document.querySelectorAll('[data-lte-theme-icon]').forEach((icon) => {
            icon.classList.toggle('d-none', icon.dataset.lteThemeIcon !== theme);
          });
        };

        globalThis.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
          const stored = getStoredTheme();
          if (!stored || stored === 'auto') setTheme(getPreferredTheme());
        });

        document.addEventListener('DOMContentLoaded', () => {
          showActiveTheme(getPreferredTheme());
          document.querySelectorAll('[data-bs-theme-value]').forEach((toggle) => {
            toggle.addEventListener('click', () => {
              const theme = toggle.getAttribute('data-bs-theme-value');
              setStoredTheme(theme);
              setTheme(theme);
              showActiveTheme(theme);
            });
          });
        });
      })();
    </script>
    <!--end::Color Mode Toggle-->

    <!-- OPTIONAL SCRIPTS -->

    <!-- sortablejs -->
    <script
      src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
      crossorigin="anonymous"
    ></script>

    <!-- sortablejs -->
    <script>
      new Sortable(document.querySelector('.connectedSortable'), {
        group: 'shared',
        handle: '.card-header',
      });

      const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
      cardHeaders.forEach((cardHeader) => {
        cardHeader.style.cursor = 'move';
      });
    </script>

    <!-- apexcharts -->
    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
      crossorigin="anonymous"
    ></script>

    <!-- ChartJS -->
    <script>
      // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
      // IT'S ALL JUST JUNK FOR DEMO
      // ++++++++++++++++++++++++++++++++++++++++++

      const sales_chart_options = {
        series: [
          {
            name: 'Digital Goods',
            data: [28, 48, 40, 19, 86, 27, 90],
          },
          {
            name: 'Electronics',
            data: [65, 59, 80, 81, 56, 55, 40],
          },
        ],
        chart: {
          height: 300,
          type: 'area',
          toolbar: {
            show: false,
          },
        },
        legend: {
          show: false,
        },
        colors: ['#0d6efd', '#20c997'],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: 'smooth',
        },
        xaxis: {
          type: 'datetime',
          categories: [
            '2023-01-01',
            '2023-02-01',
            '2023-03-01',
            '2023-04-01',
            '2023-05-01',
            '2023-06-01',
            '2023-07-01',
          ],
        },
        tooltip: {
          x: {
            format: 'MMMM yyyy',
          },
        },
      };

      const sales_chart = new ApexCharts(
        document.querySelector('#revenue-chart'),
        sales_chart_options,
      );
      sales_chart.render();
    </script>

    <!-- jsvectormap -->
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
      integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
      integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY="
      crossorigin="anonymous"
    ></script>

    <!-- jsvectormap -->
    <script>
      // World map by jsVectorMap
      new jsVectorMap({
        selector: '#world-map',
        map: 'world',
      });

      // Sparkline charts
      const option_sparkline1 = {
        series: [
          {
            data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
          },
        ],
        chart: {
          type: 'area',
          height: 50,
          sparkline: {
            enabled: true,
          },
        },
        stroke: {
          curve: 'straight',
        },
        fill: {
          opacity: 0.3,
        },
        yaxis: {
          min: 0,
        },
        colors: ['#DCE6EC'],
      };

      const sparkline1 = new ApexCharts(document.querySelector('#sparkline-1'), option_sparkline1);
      sparkline1.render();

      const option_sparkline2 = {
        series: [
          {
            data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
          },
        ],
        chart: {
          type: 'area',
          height: 50,
          sparkline: {
            enabled: true,
          },
        },
        stroke: {
          curve: 'straight',
        },
        fill: {
          opacity: 0.3,
        },
        yaxis: {
          min: 0,
        },
        colors: ['#DCE6EC'],
      };

      const sparkline2 = new ApexCharts(document.querySelector('#sparkline-2'), option_sparkline2);
      sparkline2.render();

      const option_sparkline3 = {
        series: [
          {
            data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
          },
        ],
        chart: {
          type: 'area',
          height: 50,
          sparkline: {
            enabled: true,
          },
        },
        stroke: {
          curve: 'straight',
        },
        fill: {
          opacity: 0.3,
        },
        yaxis: {
          min: 0,
        },
        colors: ['#DCE6EC'],
      };

      const sparkline3 = new ApexCharts(document.querySelector('#sparkline-3'), option_sparkline3);
      sparkline3.render();
    </script>

    <!--Collection for Scripts-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @stack('scripts')
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>