<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>{{ auth()->guard('seller')->user()->business_name ?? 'Seller Dashboard' }} | Marketplace</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
      /* ── Seller Sidebar ────────────────────────────────── */
      .seller-sidebar {
        min-height: 100vh;
        width: 260px;
        background: linear-gradient(180deg, #1a1f2e 0%, #2d3545 100%);
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
      }

      /* Store identity block */
      .seller-brand-block {
        padding: 20px 16px 16px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        display: flex;
        align-items: center;
        gap: 12px;
      }
      .seller-logo-wrap {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        border: 2px solid rgba(255,255,255,0.15);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .seller-logo-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
      .seller-logo-placeholder {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        background: linear-gradient(135deg, #2874f0, #0f4fc8);
        border: 2px solid rgba(255,255,255,0.15);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #fff;
      }
      .seller-brand-info {
        flex: 1;
        min-width: 0;
      }
      .seller-brand-name {
        font-size: 0.92rem;
        font-weight: 700;
        color: #ffffff;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 2px;
      }
      .seller-brand-badge {
        font-size: 0.68rem;
        color: #a0b0c8;
        letter-spacing: 0.5px;
        text-transform: uppercase;
      }

      /* Nav links */
      .seller-nav {
        padding: 12px 0;
        flex: 1;
      }
      .seller-nav a {
        color: #a0b0c8;
        text-decoration: none;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
        transition: all 0.2s;
        border-left: 3px solid transparent;
      }
      .seller-nav a:hover,
      .seller-nav a.active {
        color: #fff;
        background-color: rgba(255,255,255,0.06);
        border-left-color: #2874f0;
      }
      .seller-nav a i {
        font-size: 1rem;
        width: 18px;
        text-align: center;
      }
      .seller-nav-footer {
        padding: 16px;
        border-top: 1px solid rgba(255,255,255,0.08);
      }

      /* Content */
      .content-wrapper { padding: 24px; }

      /* Top navbar */
      .seller-topbar {
        background: #ffffff;
        border-bottom: 1px solid #e8ecf0;
        padding: 12px 24px;
        display: flex;
        align-items: center;
        gap: 12px;
      }
      .seller-topbar-logo {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #dee2e6;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
      }
      .seller-topbar-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
      .seller-topbar-placeholder {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background: linear-gradient(135deg, #2874f0, #0f4fc8);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: #fff;
        flex-shrink: 0;
      }
      .seller-topbar-text {
        font-size: 0.88rem;
        color: #6c757d;
      }
      .seller-topbar-text strong {
        color: #212529;
        font-weight: 600;
      }
    </style>
  </head>
  <body>
    @php $seller = auth()->guard('seller')->user(); @endphp

    <div class="d-flex">

      {{-- ── Sidebar ── --}}
      <div class="seller-sidebar">

        {{-- Store Identity --}}
        <div class="seller-brand-block">
          @if($seller->business_logo)
            <div class="seller-logo-wrap">
              <img src="{{ asset('storage/' . $seller->business_logo) }}"
                   alt="{{ $seller->business_name }}">
            </div>
          @else
            <div class="seller-logo-placeholder">
              <i class="bi bi-shop"></i>
            </div>
          @endif

          <div class="seller-brand-info">
            <div class="seller-brand-name" title="{{ $seller->business_name }}">
              {{ $seller->business_name }}
            </div>
            <div class="seller-brand-badge">Seller Store</div>
          </div>
        </div>

        {{-- Navigation --}}
        <nav class="seller-nav">
          <a href="{{ route('seller.dashboard') }}"
             class="{{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
          </a>
          <a href="{{ route('seller.products.index') }}"
             class="{{ request()->routeIs('seller.products.*') ? 'active' : '' }}">
            <i class="bi bi-box"></i> My Products
          </a>
          <a href="{{ route('seller.reports.index') }}"
             class="{{ request()->routeIs('seller.reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i> Reports
          </a>
        </nav>

        {{-- Logout --}}
        <div class="seller-nav-footer">
          <form action="{{ route('seller.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger w-100 btn-sm">
              <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
          </form>
        </div>
      </div>

      {{-- ── Main Content ── --}}
      <div class="flex-grow-1 bg-light">

        {{-- Top Navbar --}}
        <div class="seller-topbar">
          @if($seller->business_logo)
            <div class="seller-topbar-logo">
              <img src="{{ asset('storage/' . $seller->business_logo) }}"
                   alt="{{ $seller->business_name }}">
            </div>
          @else
            <div class="seller-topbar-placeholder">
              <i class="bi bi-shop"></i>
            </div>
          @endif
          <div class="seller-topbar-text">
            <strong>{{ $seller->business_name }}</strong>
            &nbsp;·&nbsp; Seller Dashboard
          </div>
        </div>

        {{-- Content --}}
        <div class="content-wrapper">
          @yield('content')
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
