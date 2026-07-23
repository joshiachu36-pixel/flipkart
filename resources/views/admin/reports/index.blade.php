@extends('layout.admin')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════════════
     ADMIN MARKETPLACE REPORTS — Professional Dashboard
═══════════════════════════════════════════════════════════════════════════════ --}}

<style>
:root {
  --rp: #2874f0; --rs: #1a9e5f; --rd: #e02020; --rw: #f09820;
  --ri: #6366f1; --rt: #0d9488; --rg: #6c757d;
}

/* Header */
.ar-header {
  background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #2874f0 100%);
  color: #fff; padding: 28px 32px 22px; border-radius: 14px;
  margin-bottom: 26px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;
}
.ar-header h1 { font-size: 1.45rem; font-weight: 800; margin: 0; }
.ar-header p  { font-size: 0.8rem; color: #93c5fd; margin: 4px 0 0; }
.ar-header .ar-badge { background: rgba(255,255,255,0.12); padding: 4px 12px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.5px; }

/* Filter Card */
.ar-filter {
  background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,0.07);
  padding: 22px 26px; margin-bottom: 26px;
}
.ar-filter .flt { display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; }
.ar-filter .fg  { display: flex; flex-direction: column; gap: 4px; flex: 1; min-width: 150px; }
.ar-filter label { font-size: 0.74rem; font-weight: 700; color: #495057; text-transform: uppercase; letter-spacing: 0.4px; }
.ar-filter select, .ar-filter input {
  border: 1.5px solid #dee2e6; border-radius: 8px; padding: 7px 11px;
  font-size: 0.82rem; background: #f8f9fa; color: #212529; transition: border-color .2s;
}
.ar-filter select:focus, .ar-filter input:focus { border-color: var(--rp); outline: none; background: #fff; }

.qd-row { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 12px; align-items: center; }
.qd-btn {
  font-size: 0.73rem; padding: 4px 12px; border-radius: 20px;
  border: 1.5px solid #dee2e6; background: #fff; cursor: pointer;
  transition: all .2s; color: #495057;
}
.qd-btn:hover, .qd-btn.active { background: var(--rp); color: #fff; border-color: var(--rp); }

.fa-row { display: flex; gap: 8px; margin-top: 16px; flex-wrap: wrap; }
.btn-f { background: var(--rp); color: #fff; border: none; border-radius: 8px; padding: 8px 20px; font-size: 0.84rem; font-weight: 600; cursor: pointer; }
.btn-r { background: var(--rg); color: #fff; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.84rem; cursor: pointer; text-decoration: none; display: inline-block; }
.btn-rf{ background: #198754; color: #fff; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.84rem; cursor: pointer; }

/* Summary Cards Grid */
.sc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(170px,1fr)); gap: 14px; margin-bottom: 28px; }
.sc {
  background: #fff; border-radius: 12px; padding: 18px 16px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.06);
  transition: transform .2s, box-shadow .2s;
  position: relative; overflow: hidden;
}
.sc:hover { transform: translateY(-3px); box-shadow: 0 6px 22px rgba(0,0,0,0.10); }
.sc .ico { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 12px; }
.sc .val { font-size: 1.75rem; font-weight: 800; line-height: 1; margin-bottom: 4px; }
.sc .lbl { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6c757d; }
.sc .sub { font-size: 0.68rem; color: #adb5bd; margin-top: 3px; }

/* Color themes */
.sc-b .ico{background:#dbeafe;color:#2874f0;} .sc-b .val{color:#2874f0;}
.sc-g .ico{background:#d1fae5;color:#1a9e5f;} .sc-g .val{color:#1a9e5f;}
.sc-r .ico{background:#fee2e2;color:#e02020;} .sc-r .val{color:#e02020;}
.sc-o .ico{background:#fef3c7;color:#f09820;} .sc-o .val{color:#f09820;}
.sc-p .ico{background:#ede9fe;color:#6366f1;} .sc-p .val{color:#6366f1;}
.sc-t .ico{background:#d1faf0;color:#0d9488;} .sc-t .val{color:#0d9488;}
.sc-gr .ico{background:#f1f3f5;color:#6c757d;} .sc-gr .val{color:#374151;font-size:1.1rem;line-height:1.3;}

/* Charts grid */
.cg { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 28px; }
@media (max-width: 900px) { .cg { grid-template-columns: 1fr; } }
.cc {
  background: #fff; border-radius: 14px; padding: 18px 18px 12px;
  box-shadow: 0 2px 14px rgba(0,0,0,0.07);
}
.ct { font-size: 0.86rem; font-weight: 700; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
.ct i { color: var(--rp); }

/* Table card */
.tc { background: #fff; border-radius: 14px; box-shadow: 0 2px 14px rgba(0,0,0,0.07); overflow: hidden; margin-bottom: 22px; }
.th { padding: 15px 20px; font-size: 0.86rem; font-weight: 700; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid #f0f0f0; }
.th i { font-size: 1rem; }
.th-b   { background: linear-gradient(90deg,#dbeafe,#eff6ff); color: #1d4ed8; }
.th-g   { background: linear-gradient(90deg,#d1fae5,#ecfdf5); color: #065f46; }
.th-r   { background: linear-gradient(90deg,#fee2e2,#fff1f2); color: #b91c1c; }
.th-o   { background: linear-gradient(90deg,#fef3c7,#fffbeb); color: #92400e; }
.th-p   { background: linear-gradient(90deg,#ede9fe,#f5f3ff); color: #4c1d95; }
.th-t   { background: linear-gradient(90deg,#d1faf0,#f0fdf4); color: #065f46; }
.th-gr  { background: linear-gradient(90deg,#f1f3f5,#f8f9fa); color: #374151; }
.th-dk  { background: linear-gradient(90deg,#1a1f2e,#2d3545); color: #fff; }

.rt { width: 100%; border-collapse: collapse; font-size: 0.81rem; }
.rt th { background: #f8f9fa; font-size: 0.73rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; padding: 9px 14px; color: #6c757d; border-bottom: 1.5px solid #f0f0f0; text-align: left; }
.rt td { padding: 10px 14px; border-bottom: 1px solid #f6f7f9; color: #374151; vertical-align: middle; }
.rt tbody tr:hover { background: #f8f9fb; }
.rt tbody tr:last-child td { border-bottom: none; }

.bw { background:#fee2e2;color:#b91c1c;border-radius:20px;padding:2px 10px;font-size:.71rem;font-weight:700; }
.bc { background:#d1fae5;color:#065f46;border-radius:20px;padding:2px 10px;font-size:.71rem;font-weight:700; }
.bi { background:#dbeafe;color:#1d4ed8;border-radius:20px;padding:2px 10px;font-size:.71rem;font-weight:700; }
.bz { background:#f1f3f5;color:#6c757d;border-radius:20px;padding:2px 10px;font-size:.71rem; }
.bp { background:#fef3c7;color:#92400e;border-radius:20px;padding:2px 10px;font-size:.71rem; }
.br { background:#fee2e2;color:#b91c1c;border-radius:20px;padding:2px 10px;font-size:.71rem; }

.es { text-align:center;padding:36px 20px;color:#adb5bd; }
.es i { font-size:2rem;display:block;margin-bottom:8px; }

.product-thumb { width: 36px; height: 36px; object-fit: cover; border-radius: 7px; border: 1px solid #f0f0f0; margin-right: 8px; }
.pnc { display: flex; align-items: center; }
.cs { display:inline-block;width:12px;height:12px;border-radius:3px;border:1px solid rgba(0,0,0,.1);vertical-align:middle;margin-right:5px; }

/* Tabs */
.nav-tabs-report { display: flex; gap: 0; border-bottom: 2px solid #f0f0f0; margin-bottom: 20px; flex-wrap: wrap; }
.nav-tab { padding: 8px 18px; cursor: pointer; font-size: 0.82rem; font-weight: 600; color: #6c757d; border-bottom: 2.5px solid transparent; margin-bottom: -2px; transition: all .2s; background: none; border-top: none; border-left: none; border-right: none; }
.nav-tab.active { color: var(--rp); border-bottom-color: var(--rp); }
.tab-panel { display: none; }
.tab-panel.active { display: block; }

/* Print */
@media print {
  .sidebar-wrapper, .app-sidebar, .navbar, .main-header,
  .ar-filter, .no-print { display: none !important; }
  body { background: #fff !important; }
  .sc-grid { grid-template-columns: repeat(4, 1fr); }
  .cg { grid-template-columns: 1fr 1fr; }
}

/* ── Design Tokens for Export Modal ── */
#adminExportModal .modal-content { border-radius: 14px; border: none; overflow: hidden; box-shadow: 0 24px 80px rgba(0,0,0,0.2); }
#adminExportModal .modal-header {
  background: linear-gradient(135deg, #0f1f3d, #162447);
  color: #fff; padding: 18px 24px; border-bottom: none;
}
#adminExportModal .modal-header .modal-title { font-size: 0.95rem; font-weight: 700; display: flex; align-items: center; gap: 8px; color: #ffffff !important; }
#adminExportModal .modal-header .btn-close { filter: invert(1); opacity: .7; }
#adminExportModal .modal-footer { background: #f1f5f9; border-top: 1px solid #e2e8f0; gap: 8px; }

/* Report Preview */
#reportPreview {
  font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
  font-size: 12px; color: #1e293b; background: #fff; padding: 0;
}
.rp-cover {
  background: linear-gradient(135deg, #0f1f3d 0%, #162447 100%);
  color: #fff; padding: 32px 36px;
  display: flex; justify-content: space-between; align-items: flex-start;
  flex-wrap: wrap; gap: 14px;
}
.rp-cover-logo { font-size: 1.1rem; font-weight: 800; letter-spacing: -0.3px; }
.rp-cover-logo span { color: #3b82f6; }
.rp-cover-title { font-size: 1rem; font-weight: 700; margin-bottom: 4px; }
.rp-cover-sub { font-size: 0.77rem; color: #94a3b8; }
.rp-cover-right { text-align: right; }
.rp-cover-meta { font-size: 0.67rem; color: #64748b; margin-top: 8px; }
.rp-body { padding: 24px 28px; }
.rp-section { margin-bottom: 24px; }
.rp-section-title {
  font-size: 0.72rem; font-weight: 800;
  text-transform: uppercase; letter-spacing: 1px;
  color: #2563eb;
  border-bottom: 2px solid rgba(37,99,235,0.10);
  padding-bottom: 6px; margin-bottom: 14px;
  display: flex; align-items: center; gap: 6px;
}
.rp-filters-block {
  background: rgba(37,99,235,0.10); border-left: 3px solid #2563eb;
  padding: 10px 14px; border-radius: 6px; font-size: 0.78rem;
  margin-bottom: 15px;
}
.rp-cards { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; margin-bottom: 20px; }
.rp-card {
  background: #f1f5f9; border-radius: 8px; padding: 12px;
  text-align: center; border: 1px solid #e2e8f0;
}
.rp-card-val { font-size: 1.25rem; font-weight: 800; margin-bottom: 2px; }
.rp-card-lbl { font-size: 0.64rem; text-transform: uppercase; letter-spacing: 0.4px; color: #64748b; }
.rp-table { width: 100%; border-collapse: collapse; font-size: 0.78rem; }
.rp-table th {
  background: #0f1f3d; color: #fff;
  padding: 7px 10px; font-size: 0.67rem;
  text-transform: uppercase; letter-spacing: 0.5px; text-align: left;
}
.rp-table td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; }
.rp-table tbody tr:nth-child(even) td { background: #f1f5f9; }
.rp-footer {
  background: #f1f5f9; border-top: 2px solid #e2e8f0;
  padding: 14px 28px; display: flex; justify-content: space-between;
  font-size: 0.7rem; color: #64748b; flex-wrap: wrap; gap: 8px;
}
</style>

<div>

  {{-- ── HEADER ──────────────────────────────────────────────────────────── --}}
  <div class="ar-header">
    <div>
      <h1><i class="bi bi-globe2 me-2"></i>Marketplace Analytics</h1>
      <p>Super Admin · Full marketplace visibility · {{ now()->format('d M Y, h:i A') }}</p>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;" class="no-print">
      <span class="ar-badge"><i class="bi bi-shield-fill-check me-1"></i>Admin</span>
      @if(can_do('reports.export'))
      <button type="button" class="btn btn-sm btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#adminExportModal" id="openAdminExportBtn">
        📄 Export Report
      </button>
      <a href="{{ route('admin.reports.download') }}" class="btn btn-sm btn-success">
        <i class="bi bi-download me-1"></i>Download Center
      </a>
      @endif
    </div>
  </div>

  {{-- ── FILTERS ──────────────────────────────────────────────────────────── --}}
  <div class="ar-filter no-print">
    <div style="font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#6c757d;margin-bottom:14px;">
      <i class="bi bi-funnel-fill me-1"></i>Marketplace Filters
    </div>
    <form method="GET" action="{{ route('admin.reports.index') }}" id="adminFilterForm">
      <div class="flt">
        <div class="fg" style="min-width:200px;">
          <label>Search Product</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Type product name…">
        </div>
        <div class="fg">
          <label>Seller</label>
          <select name="seller_id">
            <option value="">All Sellers</option>
            @foreach($sellers as $s)
              <option value="{{ $s->id }}" {{ request('seller_id') == $s->id ? 'selected' : '' }}>
                {{ $s->business_name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="fg">
          <label>Category</label>
          <select name="category_id">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="fg">
          <label>Brand</label>
          <select name="brand_id">
            <option value="">All Brands</option>
            @foreach($brands as $b)
              <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>
                {{ $b->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="fg" style="min-width:130px;">
          <label>Status</label>
          <select name="status">
            <option value="all" {{ request('status','all')==='all'?'selected':'' }}>All</option>
            <option value="active"   {{ request('status')==='active'?'selected':'' }}>Active</option>
            <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Inactive</option>
          </select>
        </div>
        <div class="fg" style="min-width:150px;">
          <label>Approval</label>
          <select name="approval_status">
            <option value="all"      {{ request('approval_status','all')==='all'?'selected':'' }}>All</option>
            <option value="approved" {{ request('approval_status')==='approved'?'selected':'' }}>Approved</option>
            <option value="pending"  {{ request('approval_status')==='pending'?'selected':'' }}>Pending</option>
            <option value="rejected" {{ request('approval_status')==='rejected'?'selected':'' }}>Rejected</option>
          </select>
        </div>
        <div class="fg" style="min-width:135px;">
          <label>From Date</label>
          <input type="date" name="date_from" id="adf" value="{{ request('date_from') }}">
        </div>
        <div class="fg" style="min-width:135px;">
          <label>To Date</label>
          <input type="date" name="date_to" id="adt" value="{{ request('date_to') }}">
        </div>
        <input type="hidden" name="quick_date" id="aqdInput" value="{{ request('quick_date','') }}">
      </div>

      <div class="qd-row">
        <span style="font-size:.73rem;color:#6c757d;font-weight:600;">Quick:</span>
        @foreach(['today'=>'Today','yesterday'=>'Yesterday','last7'=>'Last 7 Days','last30'=>'Last 30 Days','this_month'=>'This Month','last_month'=>'Last Month','custom'=>'Custom'] as $v=>$l)
          <button type="button" class="qd-btn {{ request('quick_date')===$v?'active':'' }}"
                  onclick="aSetQD('{{ $v }}')">{{ $l }}</button>
        @endforeach
      </div>

      <div class="fa-row">
        <button type="submit" class="btn-f"><i class="bi bi-funnel me-1"></i>Filter</button>
        <a href="{{ route('admin.reports.index') }}" class="btn-r"><i class="bi bi-x-circle me-1"></i>Reset</a>
        <button type="button" class="btn-rf" onclick="location.reload()"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button>
      </div>
    </form>
  </div>

  {{-- ── SUMMARY CARDS ────────────────────────────────────────────────────── --}}
  <div class="sc-grid">
    {{-- Sellers --}}
    <div class="sc sc-b">
      <div class="ico"><i class="bi bi-shop"></i></div>
      <div class="val">{{ $totalSellers }}</div>
      <div class="lbl">Total Sellers</div>
    </div>
    <div class="sc sc-g">
      <div class="ico"><i class="bi bi-patch-check-fill"></i></div>
      <div class="val">{{ $approvedSellers }}</div>
      <div class="lbl">Approved Sellers</div>
    </div>
    <div class="sc sc-o">
      <div class="ico"><i class="bi bi-hourglass-split"></i></div>
      <div class="val">{{ $pendingSellers }}</div>
      <div class="lbl">Pending Sellers</div>
    </div>
    <div class="sc sc-r">
      <div class="ico"><i class="bi bi-x-circle-fill"></i></div>
      <div class="val">{{ $rejectedSellers }}</div>
      <div class="lbl">Rejected Sellers</div>
    </div>
    {{-- Products --}}
    <div class="sc sc-b">
      <div class="ico"><i class="bi bi-box-seam"></i></div>
      <div class="val">{{ $totalProducts }}</div>
      <div class="lbl">Total Products</div>
    </div>
    <div class="sc sc-o">
      <div class="ico"><i class="bi bi-clock-fill"></i></div>
      <div class="val">{{ $pendingProducts }}</div>
      <div class="lbl">Pending Products</div>
    </div>
    <div class="sc sc-g">
      <div class="ico"><i class="bi bi-check-circle-fill"></i></div>
      <div class="val">{{ $approvedProducts }}</div>
      <div class="lbl">Approved Products</div>
    </div>
    {{-- Interest --}}
    <div class="sc sc-r">
      <div class="ico"><i class="bi bi-heart-fill"></i></div>
      <div class="val">{{ $totalWishlist }}</div>
      <div class="lbl">Wishlist Count</div>
    </div>
    <div class="sc sc-g">
      <div class="ico"><i class="bi bi-cart-fill"></i></div>
      <div class="val">{{ $totalCart }}</div>
      <div class="lbl">Cart Count</div>
    </div>
    <div class="sc sc-p">
      <div class="ico"><i class="bi bi-lightning-fill"></i></div>
      <div class="val">{{ $totalInterest }}</div>
      <div class="lbl">Total Interest</div>
    </div>
    {{-- Top Seller --}}
    <div class="sc sc-gr">
      <div class="ico"><i class="bi bi-trophy-fill"></i></div>
      <div class="val" style="color:#f09820;">{{ Str::limit($mostPopularSeller?->business_name ?? 'N/A', 18) }}</div>
      <div class="lbl">Top Seller</div>
      @if($topSellerData)
        <div class="sub">⚡ {{ $topSellerData['total_interest'] ?? 0 }} interest</div>
      @endif
    </div>
    {{-- Most Popular Product --}}
    <div class="sc sc-gr">
      <div class="ico"><i class="bi bi-star-fill"></i></div>
      <div class="val" style="color:#6366f1;">{{ Str::limit($mostPopular?->product?->name ?? 'N/A', 18) }}</div>
      <div class="lbl">Most Popular Product</div>
      @if($mostPopular)
        <div class="sub">♥ {{ $mostPopular->wishlist_count }} · 🛒 {{ $mostPopular->cart_count }}</div>
      @endif
    </div>
  </div>

  {{-- ── CHARTS ───────────────────────────────────────────────────────────── --}}
  @if($popularProducts->isNotEmpty())
  <div class="cg">
    <div class="cc">
      <div class="ct"><i class="bi bi-people-fill"></i>Top Sellers — Wishlist vs Cart</div>
      <div id="chSellerBar" style="min-height:280px;"></div>
    </div>
    <div class="cc">
      <div class="ct"><i class="bi bi-bar-chart-fill"></i>Top Products — Interest</div>
      <div id="chProductBar" style="min-height:280px;"></div>
    </div>
    <div class="cc">
      <div class="ct"><i class="bi bi-pie-chart-fill"></i>Category Distribution</div>
      <div id="chCategoryDonut" style="min-height:280px;"></div>
    </div>
    <div class="cc">
      <div class="ct"><i class="bi bi-graph-up-arrow"></i>Marketplace Wishlist vs Cart</div>
      <div id="chMarketWishCart" style="min-height:280px;"></div>
    </div>
  </div>
  @endif

  {{-- ── TABS FOR TABLES ─────────────────────────────────────────────────── --}}
  <div class="nav-tabs-report no-print">
    <button class="nav-tab active" onclick="showTab('tabTopSellers',this)">Top Sellers</button>
    <button class="nav-tab" onclick="showTab('tabTopProducts',this)">Top Products</button>
    <button class="nav-tab" onclick="showTab('tabCategory',this)">Category</button>
    <button class="nav-tab" onclick="showTab('tabVariant',this)">Variant Analytics</button>
    <button class="nav-tab" onclick="showTab('tabPending',this)">Pending</button>
    <button class="nav-tab" onclick="showTab('tabRejected',this)">Rejected</button>
    <button class="nav-tab" onclick="showTab('tabZero',this)">Zero Interest</button>
  </div>

  {{-- TAB: Top Sellers --}}
  <div id="tabTopSellers" class="tab-panel active">
    <div class="tc">
      <div class="th th-b"><i class="bi bi-trophy-fill"></i>Top Sellers by Customer Interest</div>
      <div style="overflow-x:auto;max-height:420px;overflow-y:auto;">
        <table class="rt">
          <thead>
            <tr>
              <th>#</th><th>Seller / Business</th><th>Products</th>
              <th>Wishlist</th><th>Cart</th><th>Total Interest</th>
            </tr>
          </thead>
          <tbody>
            @forelse($sellerPerformance as $idx => $sp)
              <tr>
                <td><strong>#{{ $idx + 1 }}</strong></td>
                <td><strong>{{ $sp['seller']?->business_name ?? 'Unknown' }}</strong></td>
                <td>{{ $sp['product_count'] }}</td>
                <td><span class="bw">♥ {{ $sp['wishlist_count'] }}</span></td>
                <td><span class="bc">🛒 {{ $sp['cart_count'] }}</span></td>
                <td><span class="bi">⚡ {{ $sp['total_interest'] }}</span></td>
              </tr>
            @empty
              <tr><td colspan="6" class="es"><i class="bi bi-inbox"></i>No seller data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- TAB: Top Products --}}
  <div id="tabTopProducts" class="tab-panel">
    <div class="tc">
      <div class="th th-dk"><i class="bi bi-star-fill"></i>Most Popular Products</div>
      <div style="overflow-x:auto;max-height:420px;overflow-y:auto;">
        <table class="rt">
          <thead>
            <tr>
              <th>#</th><th>Product</th><th>Seller</th><th>Category</th>
              <th>Wishlist</th><th>Cart</th><th>Total Interest</th>
            </tr>
          </thead>
          <tbody>
            @forelse($popularProducts as $idx => $a)
              <tr>
                <td>{{ $idx + 1 }}</td>
                <td>
                  <div class="pnc">
                    @if($a->product?->image)
                      <img src="{{ asset('storage/' . $a->product->image) }}" class="product-thumb" alt="">
                    @endif
                    {{ $a->product?->name ?? 'Unknown' }}
                  </div>
                </td>
                <td>{{ $a->product?->seller?->business_name ?? '—' }}</td>
                <td>{{ $a->product?->category?->name ?? '—' }}</td>
                <td><span class="bw">♥ {{ $a->wishlist_count }}</span></td>
                <td><span class="bc">🛒 {{ $a->cart_count }}</span></td>
                <td><span class="bi">⚡ {{ $a->total_interest }}</span></td>
              </tr>
            @empty
              <tr><td colspan="7" class="es"><i class="bi bi-inbox"></i>No product data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- TAB: Category --}}
  <div id="tabCategory" class="tab-panel">
    <div class="tc">
      <div class="th th-p"><i class="bi bi-grid-fill"></i>Category Performance</div>
      <div style="overflow-x:auto;max-height:420px;overflow-y:auto;">
        <table class="rt">
          <thead>
            <tr><th>Category</th><th>Wishlist</th><th>Cart</th><th>Total Interest</th></tr>
          </thead>
          <tbody>
            @forelse($categoryPerformance as $cp)
              <tr>
                <td><strong>{{ $cp['category']?->name ?? 'Unknown' }}</strong></td>
                <td><span class="bw">♥ {{ $cp['wishlist_count'] }}</span></td>
                <td><span class="bc">🛒 {{ $cp['cart_count'] }}</span></td>
                <td><span class="bi">⚡ {{ $cp['total_interest'] }}</span></td>
              </tr>
            @empty
              <tr><td colspan="4" class="es"><i class="bi bi-inbox"></i>No category data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- TAB: Variant Analytics (FIXED) --}}
  <div id="tabVariant" class="tab-panel">
    <div class="tc">
      <div class="th th-t">
        <i class="bi bi-layers-fill"></i>Variant Analytics — Per Variant (Accurate)
        <span style="margin-left:auto;font-size:.71rem;font-weight:400;color:#065f46;">✅ Per product_variant_id</span>
      </div>
      <div style="overflow-x:auto;max-height:420px;overflow-y:auto;">
        <table class="rt">
          <thead>
            <tr>
              <th>Product</th><th>Seller</th><th>Color</th><th>Sizes</th>
              <th>Wishlist</th><th>Cart</th><th>Total Interest</th>
            </tr>
          </thead>
          <tbody>
            @forelse($variantAnalytics as $va)
              <tr>
                <td>{{ $va['product']?->name ?? 'Unknown' }}</td>
                <td>{{ $va['product']?->seller?->business_name ?? '—' }}</td>
                <td>
                  @if($va['color'])
                    <span class="cs" style="background:{{ $va['color']->code ?? '#ccc' }};"></span>
                    {{ $va['color']->name }}
                  @else <span class="bz">N/A</span> @endif
                </td>
                <td>
                  @forelse($va['sizes'] as $sz)
                    <span class="bz">{{ $sz->name }} ({{ $sz->pivot->stock }})</span>
                  @empty <span class="bz">—</span>
                  @endforelse
                </td>
                <td><span class="{{ $va['wishlist']>0?'bw':'bz' }}">♥ {{ $va['wishlist'] }}</span></td>
                <td><span class="{{ $va['cart']>0?'bc':'bz' }}">🛒 {{ $va['cart'] }}</span></td>
                <td><span class="{{ $va['total_interest']>0?'bi':'bz' }}">⚡ {{ $va['total_interest'] }}</span></td>
              </tr>
            @empty
              <tr><td colspan="7" class="es"><i class="bi bi-layers"></i>No variant data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- TAB: Pending Products --}}
  <div id="tabPending" class="tab-panel">
    <div class="tc">
      <div class="th th-o"><i class="bi bi-clock-history"></i>Pending Products (Awaiting Approval)</div>
      <div style="overflow-x:auto;max-height:420px;overflow-y:auto;">
        <table class="rt">
          <thead><tr><th>Product</th><th>Seller</th><th>Category</th><th>Status</th></tr></thead>
          <tbody>
            @forelse($pendingProductsList as $p)
              <tr>
                <td>{{ $p->name }}</td>
                <td>{{ $p->seller?->business_name ?? '—' }}</td>
                <td>{{ $p->category?->name ?? '—' }}</td>
                <td><span class="bp">Pending</span></td>
              </tr>
            @empty
              <tr><td colspan="4" class="es"><i class="bi bi-check2-all"></i>No pending products.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- TAB: Rejected Products --}}
  <div id="tabRejected" class="tab-panel">
    <div class="tc">
      <div class="th th-r"><i class="bi bi-x-circle-fill"></i>Rejected Products</div>
      <div style="overflow-x:auto;max-height:420px;overflow-y:auto;">
        <table class="rt">
          <thead><tr><th>Product</th><th>Seller</th><th>Category</th><th>Status</th></tr></thead>
          <tbody>
            @forelse($rejectedProductsList as $p)
              <tr>
                <td>{{ $p->name }}</td>
                <td>{{ $p->seller?->business_name ?? '—' }}</td>
                <td>{{ $p->category?->name ?? '—' }}</td>
                <td><span class="br">Rejected</span></td>
              </tr>
            @empty
              <tr><td colspan="4" class="es"><i class="bi bi-check2-all"></i>No rejected products.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- TAB: Zero Interest --}}
  <div id="tabZero" class="tab-panel">
    <div class="tc">
      <div class="th th-gr"><i class="bi bi-exclamation-triangle-fill"></i>Zero Interest Products</div>
      <div style="overflow-x:auto;max-height:420px;overflow-y:auto;">
        <table class="rt">
          <thead><tr><th>Product</th><th>Seller</th><th>Category</th><th>Interest</th></tr></thead>
          <tbody>
            @forelse($zeroInterestList as $a)
              <tr>
                <td>{{ $a->product?->name ?? 'Unknown' }}</td>
                <td>{{ $a->product?->seller?->business_name ?? '—' }}</td>
                <td>{{ $a->product?->category?->name ?? '—' }}</td>
                <td><span class="bz">0</span></td>
              </tr>
            @empty
            @endforelse
            @foreach($untrackedProducts as $p)
              <tr>
                <td>{{ $p->name }}</td>
                <td>{{ $p->seller?->business_name ?? '—' }}</td>
                <td>{{ $p->category?->name ?? '—' }}</td>
                <td><span class="bz">No Data</span></td>
              </tr>
            @endforeach
            @if($zeroInterestList->isEmpty() && $untrackedProducts->isEmpty())
              <tr><td colspan="4" class="es"><i class="bi bi-emoji-smile"></i>All products have interest!</td></tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

{{-- ── SCRIPTS ──────────────────────────────────────────────────────────────── --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
let currentActiveTab = 'top-sellers'; // default active tab

function showTab(id, el) {
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
  document.getElementById(id).classList.add('active');
  el.classList.add('active');
  
  const tabMap = {
    'tabTopSellers': 'top-sellers',
    'tabTopProducts': 'top-products',
    'tabCategory': 'category',
    'tabVariant': 'variant',
    'tabPending': 'pending',
    'tabRejected': 'rejected',
    'tabZero': 'zero'
  };
  currentActiveTab = tabMap[id] || 'top-sellers';
}

function aSetQD(val) {
  document.getElementById('aqdInput').value = val;
  document.querySelectorAll('.qd-btn').forEach(b => b.classList.remove('active'));
  event.target.classList.add('active');
  const today = new Date(), fmt = d => d.toISOString().split('T')[0];
  const df = document.getElementById('adf'), dt = document.getElementById('adt');
  if (val==='today')      { df.value=fmt(today); dt.value=fmt(today); }
  else if (val==='yesterday') { const y=new Date(today); y.setDate(y.getDate()-1); df.value=fmt(y); dt.value=fmt(y); }
  else if (val==='last7')  { const s=new Date(today); s.setDate(s.getDate()-6); df.value=fmt(s); dt.value=fmt(today); }
  else if (val==='last30') { const s=new Date(today); s.setDate(s.getDate()-29); df.value=fmt(s); dt.value=fmt(today); }
  else if (val==='this_month') { df.value=fmt(new Date(today.getFullYear(),today.getMonth(),1)); dt.value=fmt(new Date(today.getFullYear(),today.getMonth()+1,0)); }
  else if (val==='last_month') { df.value=fmt(new Date(today.getFullYear(),today.getMonth()-1,1)); dt.value=fmt(new Date(today.getFullYear(),today.getMonth(),0)); }
  else { df.value=''; dt.value=''; }
}

document.addEventListener('DOMContentLoaded', function() {
  const COLORS = ['#2874f0','#e02020','#1a9e5f','#f09820','#6366f1','#0d9488','#ec4899','#8b5cf6'];

  const selLabels  = {!! json_encode($sellerChartLabels->values()) !!};
  const selWl      = {!! json_encode($sellerChartWishlist->values()) !!};
  const selCt      = {!! json_encode($sellerChartCart->values()) !!};
  const prodLabels = {!! json_encode($productChartLabels->values()) !!};
  const prodWl     = {!! json_encode($productChartWishlist->values()) !!};
  const prodCt     = {!! json_encode($productChartCart->values()) !!};
  const catLabels  = {!! json_encode($catChartLabels->values()) !!};
  const catInt     = {!! json_encode($catChartInterest->values()) !!};
  const catWl      = {!! json_encode($catChartWishlist->values()) !!};
  const catCt      = {!! json_encode($catChartCart->values()) !!};

  // Seller horizontal stacked bar
  if (selLabels.length && document.querySelector('#chSellerBar')) {
    new ApexCharts(document.querySelector('#chSellerBar'), {
      series: [{ name:'Wishlist', data: selWl }, { name:'Cart', data: selCt }],
      chart: { type:'bar', height:280, stacked:true, toolbar:{show:false}, fontFamily:'inherit' },
      plotOptions: { bar: { horizontal:true, borderRadius:4 } },
      xaxis: { categories: selLabels },
      colors: ['#e02020','#1a9e5f'],
      legend: { position:'top' },
    }).render();
  }

  // Products bar
  if (prodLabels.length && document.querySelector('#chProductBar')) {
    new ApexCharts(document.querySelector('#chProductBar'), {
      series: [{ name:'Wishlist', data:prodWl }, { name:'Cart', data:prodCt }],
      chart: { type:'bar', height:280, toolbar:{show:false}, fontFamily:'inherit' },
      plotOptions: { bar: { columnWidth:'55%', borderRadius:5 } },
      dataLabels: { enabled:false },
      xaxis: { categories: prodLabels, labels:{ rotate:-30, style:{fontSize:'10px'} } },
      colors: ['#e02020','#1a9e5f'],
      legend: { position:'top' },
    }).render();
  }

  // Category donut
  if (catLabels.length && document.querySelector('#chCategoryDonut')) {
    new ApexCharts(document.querySelector('#chCategoryDonut'), {
      series: catInt,
      labels: catLabels,
      chart: { type:'donut', height:280, fontFamily:'inherit' },
      colors: COLORS,
      legend: { position:'bottom', fontSize:'10px' },
      plotOptions: { pie: { donut: { size:'60%' } } },
    }).render();
  }

  // Marketplace Wishlist vs Cart (category grouped)
  if (catLabels.length && document.querySelector('#chMarketWishCart')) {
    new ApexCharts(document.querySelector('#chMarketWishCart'), {
      series: [{ name:'Wishlist', data:catWl }, { name:'Cart', data:catCt }],
      chart: { type:'bar', height:280, toolbar:{show:false}, fontFamily:'inherit' },
      plotOptions: { bar: { columnWidth:'55%', borderRadius:5 } },
      dataLabels: { enabled:false },
      xaxis: { categories: catLabels, labels:{ rotate:-30, style:{fontSize:'10px'} } },
      colors: ['#6366f1','#0d9488'],
      legend: { position:'top' },
    }).render();
  }
});

// ── Export Modal: Populate Applied Filters & Handle Toggles ──
document.addEventListener('DOMContentLoaded', function() {
  const exportBtn = document.getElementById('openAdminExportBtn');
  if (exportBtn) {
    exportBtn.addEventListener('click', function() {
      // 1. Populate filters content
      const params = new URLSearchParams(window.location.search);
      const parts = [];
      if (params.get('search'))      parts.push('Search: ' + params.get('search'));
      if (params.get('seller_id'))   parts.push('Seller ID: ' + params.get('seller_id'));
      if (params.get('category_id')) parts.push('Category ID: ' + params.get('category_id'));
      if (params.get('brand_id'))    parts.push('Brand ID: ' + params.get('brand_id'));
      if (params.get('status') && params.get('status') !== 'all') parts.push('Status: ' + params.get('status'));
      if (params.get('approval_status') && params.get('approval_status') !== 'all') parts.push('Approval: ' + params.get('approval_status'));
      if (params.get('date_from'))   parts.push('From: ' + params.get('date_from'));
      if (params.get('date_to'))     parts.push('To: ' + params.get('date_to'));
      const qd = params.get('quick_date');
      if (qd && qd !== 'custom') {
        const qdMap = {today:'Today',yesterday:'Yesterday',last7:'Last 7 Days',last30:'Last 30 Days',this_month:'This Month',last_month:'Last Month'};
        parts.push('Period: ' + (qdMap[qd] || qd));
      }
      const container = document.getElementById('rp-filters-content-admin');
      if (parts.length === 0) {
        container.innerHTML = '<span style="color:#64748b;">No filters applied — showing all data.</span>';
      } else {
        container.innerHTML = parts.map(p => '<span>• ' + p + '</span>').join(' &nbsp;');
      }

      // 2. Set Modal Report Title based on active tab
      const titleMap = {
        'top-sellers': 'Seller Performance Report',
        'top-products': 'Product Analytics Report',
        'category': 'Category Performance Report',
        'variant': 'Variant Analytics Report',
        'pending': 'Pending Products Report',
        'rejected': 'Rejected Products Report',
        'zero': 'Zero Interest Products Report'
      };
      document.getElementById('modalReportTitle').innerText = titleMap[currentActiveTab] || 'Marketplace Analytics Report';

      // 3. Toggle preview sections
      document.querySelectorAll('.admin-preview-section').forEach(sec => {
        sec.style.display = 'none';
      });
      const targetSec = document.getElementById('preview-section-' + currentActiveTab);
      if (targetSec) {
        targetSec.style.display = 'block';
      }

      // 4. Update action buttons in modal
      document.getElementById('modalPrintBtn').setAttribute('onclick', `arPrintReport('${currentActiveTab}')`);
      document.getElementById('modalPdfBtn').setAttribute('onclick', `arDownloadPDF('${currentActiveTab}')`);
    });
  }
});

function arPrintReport(tab) {
  const params = new URLSearchParams(window.location.search);
  params.set('tab', tab);
  params.set('autoprint', '1');
  const url = '{{ route("admin.reports.export-pdf") }}?' + params.toString();
  const win = window.open(url, '_blank', 'width=1100,height=850,scrollbars=yes,resizable=yes');
  if (!win || win.closed || typeof win.closed === 'undefined') {
    window.open(url, '_blank');
  }
}

function arDownloadPDF(tab) {
  const params = new URLSearchParams(window.location.search);
  params.set('tab', tab);
  const url = '{{ route("admin.reports.export-pdf") }}?' + params.toString();
  window.open(url, '_blank');
}
</script>

{{-- ── ADMIN EXPORT MODAL ─────────────────────────────────────────────── --}}
<div class="modal fade" id="adminExportModal" tabindex="-1" aria-labelledby="adminExportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <span class="modal-title" id="adminExportModalLabel">
          <i class="bi bi-file-earmark-arrow-down-fill me-2"></i>Export Report Preview
        </span>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body" style="padding:0; background:var(--clr-bg); max-height:78vh; overflow-y:auto;">
        <div id="reportPreview">

          {{-- Cover --}}
          <div class="rp-cover">
            <div>
              <div class="rp-cover-logo">🛒 Flip<span>kart</span> <span style="font-size:.8rem;font-weight:400;opacity:.7;">Marketplace</span></div>
              <div style="margin-top:14px;">
                <div class="rp-cover-title" id="modalReportTitle">Marketplace Analytics Report</div>
                <div class="rp-cover-sub">Super Admin &nbsp;·&nbsp; Full Marketplace Visibility</div>
              </div>
            </div>
            <div class="rp-cover-right">
              <div style="font-size:.7rem;color:#64748b;margin-bottom:4px;text-transform:uppercase;letter-spacing:1px;">Generated</div>
              <div style="font-size:.9rem;font-weight:700;">{{ now()->format('d M Y') }}</div>
              <div style="font-size:.75rem;color:#94a3b8;">{{ now()->format('h:i A') }}</div>
              <div class="rp-cover-meta" style="margin-top:14px;">Confidential · Admin Use Only</div>
            </div>
          </div>

          <div class="rp-body">

            {{-- Filters --}}
            <div class="rp-section">
              <div class="rp-section-title"><i class="bi bi-funnel-fill"></i>Applied Filters</div>
              <div class="rp-filters-block" id="rp-filters-content-admin">
                <span>Loading filters…</span>
              </div>
            </div>

            {{-- SECTION: TOP SELLERS --}}
            <div class="admin-preview-section" id="preview-section-top-sellers">
              <div class="rp-section">
                <div class="rp-section-title"><i class="bi bi-grid-1x2-fill"></i>Summary Overview</div>
                <div class="rp-cards">
                  <div class="rp-card"><div class="rp-card-val" style="color:#2874f0;">{{ $totalSellers }}</div><div class="rp-card-lbl">Total Sellers</div></div>
                  <div class="rp-card"><div class="rp-card-val" style="color:#1a9e5f;">{{ $approvedSellers }}</div><div class="rp-card-lbl">Approved</div></div>
                  <div class="rp-card"><div class="rp-card-val" style="color:#f09820;">{{ $pendingSellers }}</div><div class="rp-card-lbl">Pending</div></div>
                  <div class="rp-card"><div class="rp-card-val" style="color:#e02020;">{{ $totalWishlist }}</div><div class="rp-card-lbl">Wishlists</div></div>
                  <div class="rp-card"><div class="rp-card-val" style="color:#1a9e5f;">{{ $totalCart }}</div><div class="rp-card-lbl">Carts</div></div>
                  <div class="rp-card" style="grid-column:span 2;"><div class="rp-card-val" style="color:#2874f0;">{{ $totalInterest }}</div><div class="rp-card-lbl">Total Interest</div></div>
                </div>
              </div>
              <div class="rp-section">
                <div class="rp-section-title"><i class="bi bi-trophy-fill"></i>Top Sellers by Customer Interest</div>
                <table class="rp-table">
                  <thead>
                    <tr><th>#</th><th>Seller</th><th>Products</th><th>Wishlist</th><th>Cart</th><th>Total Interest</th></tr>
                  </thead>
                  <tbody>
                    @forelse($sellerPerformance as $idx => $sp)
                      <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td style="font-weight:600;">{{ $sp['seller']?->business_name ?? 'Unknown' }}</td>
                        <td>{{ $sp['product_count'] }}</td>
                        <td>{{ $sp['wishlist_count'] }}</td>
                        <td>{{ $sp['cart_count'] }}</td>
                        <td style="font-weight:700;color:#2874f0;">{{ $sp['total_interest'] }}</td>
                      </tr>
                    @empty
                      <tr><td colspan="6" style="text-align:center;">No data available.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            {{-- SECTION: TOP PRODUCTS --}}
            <div class="admin-preview-section" id="preview-section-top-products">
              <div class="rp-section">
                <div class="rp-section-title"><i class="bi bi-grid-1x2-fill"></i>Summary Overview</div>
                <div class="rp-cards">
                  <div class="rp-card"><div class="rp-card-val" style="color:#2874f0;">{{ $totalProducts }}</div><div class="rp-card-lbl">Total Products</div></div>
                  <div class="rp-card"><div class="rp-card-val" style="color:#1a9e5f;">{{ $approvedProducts }}</div><div class="rp-card-lbl">Approved</div></div>
                  <div class="rp-card"><div class="rp-card-val" style="color:#f09820;">{{ $pendingProducts }}</div><div class="rp-card-lbl">Pending</div></div>
                  <div class="rp-card"><div class="rp-card-val" style="color:#1a9e5f;">{{ count($brands) }}</div><div class="rp-card-lbl">Brands</div></div>
                  <div class="rp-card"><div class="rp-card-val" style="color:#e02020;">{{ $totalWishlist }}</div><div class="rp-card-lbl">Wishlists</div></div>
                  <div class="rp-card"><div class="rp-card-val" style="color:#1a9e5f;">{{ $totalCart }}</div><div class="rp-card-lbl">Carts</div></div>
                  <div class="rp-card" style="grid-column:span 2;"><div class="rp-card-val" style="color:#2874f0;">{{ $totalInterest }}</div><div class="rp-card-lbl">Total Interest</div></div>
                </div>
              </div>
              <div class="rp-section">
                <div class="rp-section-title"><i class="bi bi-box-seam"></i>Top Products by Customer Interest</div>
                <table class="rp-table">
                  <thead>
                    <tr><th>#</th><th>Product Name</th><th>Seller</th><th>Category</th><th>Wishlist</th><th>Cart</th><th>Total Interest</th></tr>
                  </thead>
                  <tbody>
                    @forelse($popularProducts as $idx => $a)
                      <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td style="font-weight:600;">{{ $a->product?->name ?? 'Unknown' }}</td>
                        <td>{{ $a->product?->seller?->business_name ?? '—' }}</td>
                        <td>{{ $a->product?->category?->name ?? '—' }}</td>
                        <td>{{ $a->wishlist_count }}</td>
                        <td>{{ $a->cart_count }}</td>
                        <td style="font-weight:700;color:#2874f0;">{{ $a->total_interest }}</td>
                      </tr>
                    @empty
                      <tr><td colspan="7" style="text-align:center;">No data available.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            {{-- SECTION: CATEGORIES --}}
            <div class="admin-preview-section" id="preview-section-category">
              <div class="rp-section">
                <div class="rp-section-title"><i class="bi bi-grid-1x2-fill"></i>Summary Overview</div>
                <div class="rp-cards">
                  <div class="rp-card"><div class="rp-card-val" style="color:#2874f0;">{{ count($categories) }}</div><div class="rp-card-lbl">Total Categories</div></div>
                  <div class="rp-card"><div class="rp-card-val" style="color:#e02020;">{{ $totalWishlist }}</div><div class="rp-card-lbl">Wishlists</div></div>
                  <div class="rp-card"><div class="rp-card-val" style="color:#1a9e5f;">{{ $totalCart }}</div><div class="rp-card-lbl">Carts</div></div>
                  <div class="rp-card"><div class="rp-card-val" style="color:#2874f0;">{{ $totalInterest }}</div><div class="rp-card-lbl">Total Interest</div></div>
                </div>
              </div>
              <div class="rp-section">
                <div class="rp-section-title"><i class="bi bi-tags-fill"></i>Category Analytics</div>
                <table class="rp-table">
                  <thead>
                    <tr><th>#</th><th>Category</th><th>Wishlist</th><th>Cart</th><th>Total Interest</th></tr>
                  </thead>
                  <tbody>
                    @forelse($categoryPerformance as $idx => $cp)
                      <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td style="font-weight:600;">{{ $cp['category']?->name ?? 'Unknown' }}</td>
                        <td>{{ $cp['wishlist_count'] }}</td>
                        <td>{{ $cp['cart_count'] }}</td>
                        <td style="font-weight:700;color:#2874f0;">{{ $cp['total_interest'] }}</td>
                      </tr>
                    @empty
                      <tr><td colspan="5" style="text-align:center;">No data available.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            {{-- SECTION: VARIANT ANALYTICS --}}
            <div class="admin-preview-section" id="preview-section-variant">
              <div class="rp-section">
                <div class="rp-section-title"><i class="bi bi-grid-1x2-fill"></i>Summary Overview</div>
                <div class="rp-cards">
                  <div class="rp-card"><div class="rp-card-val" style="color:#2874f0;">{{ $totalProducts }}</div><div class="rp-card-lbl">Total Products</div></div>
                  <div class="rp-card"><div class="rp-card-val" style="color:#e02020;">{{ $totalWishlist }}</div><div class="rp-card-lbl">Wishlists</div></div>
                  <div class="rp-card"><div class="rp-card-val" style="color:#1a9e5f;">{{ $totalCart }}</div><div class="rp-card-lbl">Carts</div></div>
                  <div class="rp-card"><div class="rp-card-val" style="color:#2874f0;">{{ $totalInterest }}</div><div class="rp-card-lbl">Total Interest</div></div>
                </div>
              </div>
              <div class="rp-section">
                <div class="rp-section-title"><i class="bi bi-layers-fill"></i>Variant Analytics — Per Color</div>
                <table class="rp-table">
                  <thead>
                    <tr><th>Product</th><th>Seller</th><th>Color / Variant</th><th>Sizes</th><th>Wishlist</th><th>Cart</th><th>Interest</th></tr>
                  </thead>
                  <tbody>
                    @forelse($variantAnalytics as $va)
                      <tr>
                        <td style="font-weight:600;">{{ $va['product']->name ?? 'Unknown' }}</td>
                        <td>{{ $va['product']->seller?->business_name ?? '—' }}</td>
                        <td>{{ $va['color']?->name ?? 'N/A' }}</td>
                        <td>
                          @foreach($va['sizes'] as $sz)
                            {{ $sz->name }}({{ $sz->pivot->stock }}){{ !$loop->last ? ', ' : '' }}
                          @endforeach
                        </td>
                        <td>{{ $va['wishlist'] }}</td>
                        <td>{{ $va['cart'] }}</td>
                        <td style="font-weight:700;color:#2874f0;">{{ $va['total_interest'] }}</td>
                      </tr>
                    @empty
                      <tr><td colspan="7" style="text-align:center;">No data available.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            {{-- SECTION: PENDING --}}
            <div class="admin-preview-section" id="preview-section-pending">
              <div class="rp-section">
                <div class="rp-section-title"><i class="bi bi-grid-1x2-fill"></i>Summary Overview</div>
                <div class="rp-cards">
                  <div class="rp-card" style="grid-column:span 4;"><div class="rp-card-val" style="color:#f09820;">{{ $pendingProducts }}</div><div class="rp-card-lbl">Pending Products</div></div>
                </div>
              </div>
              <div class="rp-section">
                <div class="rp-section-title"><i class="bi bi-hourglass-split"></i>Pending Approval Products</div>
                <table class="rp-table">
                  <thead>
                    <tr><th>#</th><th>Product Name</th><th>Seller</th><th>Category</th><th>Status</th></tr>
                  </thead>
                  <tbody>
                    @forelse($pendingProductsList as $idx => $p)
                      <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td style="font-weight:600;">{{ $p->name }}</td>
                        <td>{{ $p->seller?->business_name ?? '—' }}</td>
                        <td>{{ $p->category?->name ?? '—' }}</td>
                        <td><span class="bp">Pending</span></td>
                      </tr>
                    @empty
                      <tr><td colspan="5" style="text-align:center;">No pending products.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            {{-- SECTION: REJECTED --}}
            <div class="admin-preview-section" id="preview-section-rejected">
              <div class="rp-section">
                <div class="rp-section-title"><i class="bi bi-grid-1x2-fill"></i>Summary Overview</div>
                <div class="rp-cards">
                  <div class="rp-card" style="grid-column:span 4;"><div class="rp-card-val" style="color:#e02020;">{{ count($rejectedProductsList) }}</div><div class="rp-card-lbl">Rejected Products</div></div>
                </div>
              </div>
              <div class="rp-section">
                <div class="rp-section-title"><i class="bi bi-x-circle-fill"></i>Rejected Products</div>
                <table class="rp-table">
                  <thead>
                    <tr><th>#</th><th>Product Name</th><th>Seller</th><th>Category</th><th>Status</th></tr>
                  </thead>
                  <tbody>
                    @forelse($rejectedProductsList as $idx => $p)
                      <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td style="font-weight:600;">{{ $p->name }}</td>
                        <td>{{ $p->seller?->business_name ?? '—' }}</td>
                        <td>{{ $p->category?->name ?? '—' }}</td>
                        <td><span class="br">Rejected</span></td>
                      </tr>
                    @empty
                      <tr><td colspan="5" style="text-align:center;">No rejected products.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            {{-- SECTION: ZERO INTEREST --}}
            <div class="admin-preview-section" id="preview-section-zero">
              <div class="rp-section">
                <div class="rp-section-title"><i class="bi bi-grid-1x2-fill"></i>Summary Overview</div>
                <div class="rp-cards">
                  <div class="rp-card" style="grid-column:span 4;"><div class="rp-card-val" style="color:#6c757d;">{{ count($zeroInterestList) + count($untrackedProducts) }}</div><div class="rp-card-lbl">Zero Interest Products</div></div>
                </div>
              </div>
              <div class="rp-section">
                <div class="rp-section-title"><i class="bi bi-emoji-neutral"></i>Zero Interest Products</div>
                <table class="rp-table">
                  <thead>
                    <tr><th>#</th><th>Product Name</th><th>Seller</th><th>Category</th><th>Status</th></tr>
                  </thead>
                  <tbody>
                    @php $counter = 1; @endphp
                    @foreach($zeroInterestList as $a)
                      <tr>
                        <td>{{ $counter++ }}</td>
                        <td style="font-weight:600;">{{ $a->product?->name ?? 'Unknown' }}</td>
                        <td>{{ $a->product?->seller?->business_name ?? '—' }}</td>
                        <td>{{ $a->product?->category?->name ?? '—' }}</td>
                        <td><span class="bz">Zero Interest</span></td>
                      </tr>
                    @endforeach
                    @foreach($untrackedProducts as $p)
                      <tr>
                        <td>{{ $counter++ }}</td>
                        <td style="font-weight:600;">{{ $p->name }}</td>
                        <td>{{ $p->seller?->business_name ?? '—' }}</td>
                        <td>{{ $p->category?->name ?? '—' }}</td>
                        <td><span class="bz">No Data</span></td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>

          </div>{{-- /rp-body --}}

          {{-- Footer --}}
          <div class="rp-footer">
            <span><strong>Flipkart Marketplace</strong> — Confidential</span>
            <span>Super Admin</span>
            <span>{{ now()->format('d M Y, h:i A') }}</span>
          </div>

        </div>{{-- /reportPreview --}}
      </div>{{-- /modal-body --}}

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i>Close
        </button>
        <button type="button" class="btn btn-primary btn-sm fw-bold" id="modalPrintBtn">
          <i class="bi bi-printer-fill me-1"></i>Print Report
        </button>
        <button type="button" class="btn btn-warning btn-sm fw-bold" id="modalPdfBtn">
          <i class="bi bi-file-earmark-pdf-fill me-1"></i>Download PDF
        </button>
      </div>

    </div>
  </div>
</div>{{-- /adminExportModal --}}

@endsection
