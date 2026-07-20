<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>{{ auth()->guard('seller')->user()->business_name ?? 'Seller Dashboard' }} | Marketplace</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
      /* ── Design Tokens ──────────────────────────────────────── */
      :root {
        --clr-navy:       #0f1f3d;
        --clr-navy-mid:   #162447;
        --clr-navy-light: #1e3a5f;
        --clr-blue:       #2563eb;
        --clr-blue-light: #3b82f6;
        --clr-blue-dim:   rgba(37,99,235,0.12);
        --clr-slate:      #1e293b;
        --clr-text:       #334155;
        --clr-muted:      #64748b;
        --clr-border:     #e2e8f0;
        --clr-bg:         #f1f5f9;
        --clr-surface:    #ffffff;
        --sidebar-w:      260px;
        --radius:         10px;
        --radius-sm:      6px;
        --font:           'Inter', system-ui, sans-serif;
        --shadow-sm:      0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.05);
        --shadow:         0 4px 16px rgba(0,0,0,0.08);
        --shadow-md:      0 8px 32px rgba(0,0,0,0.10);
        --transition:     all 0.2s cubic-bezier(0.4,0,0.2,1);
      }

      * { box-sizing: border-box; margin: 0; padding: 0; }
      body {
        font-family: var(--font);
        background: var(--clr-bg);
        color: var(--clr-text);
        font-size: 14px;
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
      }

      /* ── Layout Wrapper ──────────────────────────────────────── */
      .seller-layout {
        display: flex;
        min-height: 100vh;
      }

      /* ── Sidebar ─────────────────────────────────────────────── */
      .seller-sidebar {
        width: var(--sidebar-w);
        background: var(--clr-navy);
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0; left: 0; bottom: 0;
        z-index: 100;
        overflow: hidden;
      }

      /* Brand block */
      .sb-brand {
        padding: 24px 20px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.07);
        display: flex;
        align-items: center;
        gap: 12px;
      }
      .sb-logo {
        width: 44px; height: 44px;
        border-radius: var(--radius-sm);
        overflow: hidden;
        background: var(--clr-blue-dim);
        border: 1.5px solid rgba(255,255,255,0.12);
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
      }
      .sb-logo img { width: 100%; height: 100%; object-fit: cover; }
      .sb-logo-icon {
        width: 44px; height: 44px;
        border-radius: var(--radius-sm);
        background: linear-gradient(135deg, var(--clr-blue), #1d4ed8);
        border: 1.5px solid rgba(255,255,255,0.12);
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; color: #fff;
      }
      .sb-brand-info { flex: 1; min-width: 0; }
      .sb-brand-name {
        font-size: 0.88rem; font-weight: 700;
        color: #f1f5f9;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        letter-spacing: -0.01em;
      }
      .sb-brand-label {
        font-size: 0.68rem; color: #64748b;
        text-transform: uppercase; letter-spacing: 0.8px;
        margin-top: 2px;
      }

      /* Nav section */
      .sb-nav { padding: 16px 0; flex: 1; overflow-y: auto; }
      .sb-nav-section-label {
        font-size: 0.62rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 1.2px;
        color: #475569; padding: 0 20px 8px;
      }
      .sb-nav a {
        display: flex; align-items: center; gap: 11px;
        padding: 10px 20px;
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.875rem; font-weight: 500;
        border-left: 2px solid transparent;
        transition: var(--transition);
        position: relative;
      }
      .sb-nav a .nav-icon {
        width: 32px; height: 32px;
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.95rem;
        transition: var(--transition);
        flex-shrink: 0;
      }
      .sb-nav a:hover {
        color: #f1f5f9;
        background: rgba(255,255,255,0.04);
        border-left-color: rgba(37,99,235,0.4);
      }
      .sb-nav a:hover .nav-icon { background: rgba(37,99,235,0.2); color: var(--clr-blue-light); }
      .sb-nav a.active {
        color: #fff;
        background: linear-gradient(90deg, rgba(37,99,235,0.18) 0%, rgba(37,99,235,0.05) 100%);
        border-left-color: var(--clr-blue);
      }
      .sb-nav a.active .nav-icon {
        background: var(--clr-blue);
        color: #fff;
        box-shadow: 0 4px 12px rgba(37,99,235,0.35);
      }

      /* Sidebar footer */
      .sb-footer {
        padding: 16px 20px;
        border-top: 1px solid rgba(255,255,255,0.06);
      }
      .sb-logout {
        display: flex; align-items: center; gap: 10px;
        width: 100%; padding: 10px 14px;
        background: rgba(239,68,68,0.08);
        border: 1px solid rgba(239,68,68,0.2);
        border-radius: var(--radius-sm);
        color: #f87171; font-size: 0.84rem; font-weight: 500;
        cursor: pointer; text-decoration: none;
        transition: var(--transition);
      }
      .sb-logout:hover {
        background: rgba(239,68,68,0.15);
        color: #fca5a5;
        border-color: rgba(239,68,68,0.35);
      }

      /* ── Main Column ─────────────────────────────────────────── */
      .seller-main {
        flex: 1;
        margin-left: var(--sidebar-w);
        display: flex;
        flex-direction: column;
        min-height: 100vh;
      }

      /* ── Top Bar ─────────────────────────────────────────────── */
      .seller-topbar {
        background: var(--clr-surface);
        border-bottom: 1px solid var(--clr-border);
        padding: 0 28px;
        height: 60px;
        display: flex;
        align-items: center;
        gap: 14px;
        position: sticky; top: 0; z-index: 90;
        box-shadow: var(--shadow-sm);
      }
      .topbar-logo-wrap {
        width: 30px; height: 30px;
        border-radius: var(--radius-sm);
        overflow: hidden;
        border: 1px solid var(--clr-border);
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: var(--clr-blue-dim);
      }
      .topbar-logo-wrap img { width: 100%; height: 100%; object-fit: cover; }
      .topbar-logo-icon {
        width: 30px; height: 30px;
        border-radius: var(--radius-sm);
        background: linear-gradient(135deg, var(--clr-blue), #1d4ed8);
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; color: #fff; flex-shrink: 0;
      }
      .topbar-divider { width: 1px; height: 20px; background: var(--clr-border); }
      .topbar-title { font-size: 0.875rem; color: var(--clr-muted); font-weight: 400; }
      .topbar-title strong { color: var(--clr-slate); font-weight: 600; }
      .topbar-breadcrumb {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.8rem; color: var(--clr-muted);
      }
      .topbar-breadcrumb .sep { opacity: 0.4; }

      /* ── Content Wrapper ─────────────────────────────────────── */
      .seller-content { padding: 28px; flex: 1; }
    </style>
  </head>
  <body>
    @php $seller = auth()->guard('seller')->user(); @endphp

    <div class="seller-layout">

      {{-- ── Sidebar ── --}}
      <aside class="seller-sidebar">

        {{-- Brand --}}
        <div class="sb-brand">
          @if($seller->business_logo)
            <div class="sb-logo">
              <img src="{{ asset('storage/' . $seller->business_logo) }}" alt="{{ $seller->business_name }}">
            </div>
          @else
            <div class="sb-logo-icon"><i class="bi bi-shop"></i></div>
          @endif
          <div class="sb-brand-info">
            <div class="sb-brand-name" title="{{ $seller->business_name }}">{{ $seller->business_name }}</div>
            <div class="sb-brand-label">Seller Store</div>
          </div>
        </div>

        {{-- Navigation --}}
        <nav class="sb-nav">
          <div class="sb-nav-section-label">Main Menu</div>
          <a href="{{ route('seller.dashboard') }}"
             class="{{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
            Dashboard
          </a>
          <a href="{{ route('seller.products.index') }}"
             class="{{ request()->routeIs('seller.products.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-box-seam"></i></span>
            My Products
          </a>
          <a href="{{ route('seller.colors.index') }}"
             class="{{ request()->routeIs('seller.colors.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-palette"></i></span>
            My Colors
          </a>
          <a href="{{ route('seller.sizes.index') }}"
             class="{{ request()->routeIs('seller.sizes.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-ruler"></i></span>
            My Sizes
          </a>
          <a href="{{ route('seller.reports.index') }}"
             class="{{ request()->routeIs('seller.reports.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-bar-chart-line-fill"></i></span>
            Analytics & Reports
          </a>
        </nav>

        {{-- Footer / Logout --}}
        <div class="sb-footer">
          <form action="{{ route('seller.logout') }}" method="POST">
            @csrf
            <button type="submit" class="sb-logout">
              <i class="bi bi-box-arrow-right"></i> Sign Out
            </button>
          </form>
        </div>
      </aside>

      {{-- ── Main Content ── --}}
      <div class="seller-main">

        {{-- Top Bar --}}
        <header class="seller-topbar">
          @if($seller->business_logo)
            <div class="topbar-logo-wrap">
              <img src="{{ asset('storage/' . $seller->business_logo) }}" alt="{{ $seller->business_name }}">
            </div>
          @else
            <div class="topbar-logo-icon"><i class="bi bi-shop"></i></div>
          @endif
          <div class="topbar-divider"></div>
          <div class="topbar-title">
            <strong>{{ $seller->business_name }}</strong>
            <span>&nbsp;·&nbsp; Seller Dashboard</span>
          </div>
        </header>

        {{-- Page Content --}}
        <main class="seller-content">
          @yield('content')
        </main>
      </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
