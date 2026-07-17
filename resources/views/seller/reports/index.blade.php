@extends('layout.seller')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════
     SELLER ANALYTICS & REPORTS — Professional UI
     Color palette: Navy #0f1f3d · Blue #2563eb · Slate #1e293b
═══════════════════════════════════════════════════════════════ --}}

<style>
/* ── Design Tokens ──────────────────────────────────────────── */
:root {
  --clr-navy:       #0f1f3d;
  --clr-navy-mid:   #162447;
  --clr-blue:       #2563eb;
  --clr-blue-light: #3b82f6;
  --clr-blue-dim:   rgba(37,99,235,0.10);
  --clr-slate:      #1e293b;
  --clr-text:       #334155;
  --clr-muted:      #64748b;
  --clr-border:     #e2e8f0;
  --clr-bg:         #f1f5f9;
  --clr-surface:    #ffffff;
  --radius:         10px;
  --radius-sm:      6px;
  --shadow:         0 2px 12px rgba(0,0,0,0.07);
  --shadow-hover:   0 8px 28px rgba(0,0,0,0.11);
  --transition:     all 0.2s cubic-bezier(0.4,0,0.2,1);
  /* Semantic */
  --clr-green:      #16a34a;
  --clr-green-dim:  rgba(22,163,74,0.10);
  --clr-red:        #dc2626;
  --clr-red-dim:    rgba(220,38,38,0.10);
  --clr-amber:      #d97706;
  --clr-amber-dim:  rgba(217,119,6,0.10);
}

/* ── Page Header ─────────────────────────────────────────────── */
.rp-page-header {
  background: linear-gradient(135deg, var(--clr-navy) 0%, var(--clr-navy-mid) 60%, #1a2a5e 100%);
  border-radius: 12px;
  padding: 28px 30px 24px;
  margin-bottom: 22px;
  display: flex; align-items: center; justify-content: space-between;
  flex-wrap: wrap; gap: 14px;
  box-shadow: 0 4px 24px rgba(15,31,61,0.25);
}
.rp-page-header h1 {
  font-size: 1.45rem; font-weight: 800;
  color: #f1f5f9; letter-spacing: -0.02em; margin: 0;
  display: flex; align-items: center; gap: 10px;
}
.rp-page-header h1 i { color: var(--clr-blue-light); }
.rp-page-header p {
  margin: 5px 0 0; font-size: 0.8rem; color: #94a3b8;
}

.btn-export {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--clr-blue);
  color: #fff; border: none; border-radius: var(--radius-sm);
  padding: 10px 22px; font-size: 0.875rem; font-weight: 600;
  cursor: pointer;
  box-shadow: 0 2px 12px rgba(37,99,235,0.4);
  transition: var(--transition);
}
.btn-export:hover {
  background: var(--clr-blue-light);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(37,99,235,0.45);
}

/* ── Filter Card ─────────────────────────────────────────────── */
.rp-filter-card {
  background: var(--clr-surface);
  border-radius: var(--radius);
  border: 1px solid var(--clr-border);
  box-shadow: var(--shadow);
  padding: 22px 24px;
  margin-bottom: 20px;
}
.filter-title {
  font-size: 0.72rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: 1.2px;
  color: var(--clr-muted); margin-bottom: 18px;
  display: flex; align-items: center; gap: 8px;
}
.filter-title i { color: var(--clr-blue); }

.filter-row { display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; }
.filter-group {
  display: flex; flex-direction: column; gap: 5px;
  flex: 1; min-width: 150px;
}
.filter-group label {
  font-size: 0.71rem; font-weight: 700;
  color: var(--clr-slate); text-transform: uppercase; letter-spacing: 0.4px;
}
.filter-group select,
.filter-group input[type=text],
.filter-group input[type=date] {
  border: 1.5px solid var(--clr-border);
  border-radius: var(--radius-sm);
  padding: 8px 12px;
  font-size: 0.84rem;
  color: var(--clr-text);
  background: var(--clr-bg);
  font-family: inherit;
  transition: var(--transition);
  outline: none;
}
.filter-group select:focus,
.filter-group input:focus {
  border-color: var(--clr-blue);
  background: #fff;
  box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
}

/* Quick date pills */
.qd-row {
  display: flex; flex-wrap: wrap; gap: 6px;
  margin-top: 14px; align-items: center;
}
.qd-label { font-size: 0.72rem; color: var(--clr-muted); font-weight: 600; }
.qd-btn {
  font-size: 0.72rem; padding: 4px 13px; border-radius: 20px;
  border: 1.5px solid var(--clr-border);
  background: var(--clr-surface); cursor: pointer;
  color: var(--clr-muted); font-family: inherit;
  transition: var(--transition);
}
.qd-btn:hover, .qd-btn.active {
  background: var(--clr-blue); color: #fff;
  border-color: var(--clr-blue);
}

/* Filter action buttons */
.filter-actions { display: flex; gap: 8px; margin-top: 18px; flex-wrap: wrap; }
.btn-filter {
  display: inline-flex; align-items: center; gap: 6px;
  background: var(--clr-blue); color: #fff;
  border: none; border-radius: var(--radius-sm);
  padding: 9px 20px; font-size: 0.84rem; font-weight: 600;
  cursor: pointer; font-family: inherit; transition: var(--transition);
}
.btn-filter:hover { background: var(--clr-blue-light); }

.btn-reset {
  display: inline-flex; align-items: center; gap: 6px;
  background: var(--clr-surface); color: var(--clr-muted);
  border: 1.5px solid var(--clr-border); border-radius: var(--radius-sm);
  padding: 9px 16px; font-size: 0.84rem; font-weight: 500;
  cursor: pointer; text-decoration: none; font-family: inherit;
  transition: var(--transition);
}
.btn-reset:hover { background: var(--clr-bg); color: var(--clr-slate); }

.btn-refresh {
  display: inline-flex; align-items: center; gap: 6px;
  background: var(--clr-green-dim); color: var(--clr-green);
  border: 1.5px solid rgba(22,163,74,0.25); border-radius: var(--radius-sm);
  padding: 9px 16px; font-size: 0.84rem; font-weight: 500;
  cursor: pointer; font-family: inherit; transition: var(--transition);
}
.btn-refresh:hover { background: rgba(22,163,74,0.18); }

/* ── Tab Navigation ─────────────────────────────────────────── */
.rp-tabs {
  display: flex; gap: 0;
  background: var(--clr-surface);
  border: 1px solid var(--clr-border);
  border-radius: var(--radius) var(--radius) 0 0;
  overflow: hidden;
  box-shadow: var(--shadow);
  flex-wrap: wrap;
}
.rp-tab {
  padding: 13px 22px; cursor: pointer;
  font-size: 0.85rem; font-weight: 600;
  color: var(--clr-muted);
  border: none; border-bottom: 3px solid transparent;
  background: none; font-family: inherit;
  display: flex; align-items: center; gap: 8px;
  transition: var(--transition);
  white-space: nowrap; margin-bottom: -1px;
}
.rp-tab:hover { color: var(--clr-blue); background: rgba(37,99,235,0.04); }
.rp-tab.active {
  color: var(--clr-blue);
  border-bottom-color: var(--clr-blue);
  background: var(--clr-blue-dim);
}
.tab-count {
  background: var(--clr-bg); color: var(--clr-muted);
  border-radius: 20px; padding: 1px 8px;
  font-size: 0.68rem; font-weight: 700;
}
.rp-tab.active .tab-count { background: var(--clr-blue); color: #fff; }
.tab-count.danger-count { background: var(--clr-red-dim); color: var(--clr-red); }
.rp-tab.active .tab-count.danger-count { background: var(--clr-red); color: #fff; }

.tab-panel { display: none; }
.tab-panel.active { display: block; }

/* ── Stat Cards ─────────────────────────────────────────────── */
.sc-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(168px, 1fr));
  gap: 14px; margin-bottom: 22px;
}
.sc {
  background: var(--clr-surface);
  border-radius: var(--radius);
  border: 1px solid var(--clr-border);
  padding: 18px 16px;
  box-shadow: var(--shadow);
  transition: var(--transition);
  position: relative; overflow: hidden;
}
.sc::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
}
.sc:hover { transform: translateY(-3px); box-shadow: var(--shadow-hover); }
.sc.sc-b::before  { background: var(--clr-blue); }
.sc.sc-r::before  { background: var(--clr-red); }
.sc.sc-g::before  { background: var(--clr-green); }
.sc.sc-a::before  { background: var(--clr-amber); }
.sc.sc-s::before  { background: var(--clr-slate); }

.sc .ico {
  width: 40px; height: 40px; border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.1rem; margin-bottom: 12px;
}
.sc-b .ico { background: var(--clr-blue-dim);  color: var(--clr-blue); }
.sc-r .ico { background: var(--clr-red-dim);   color: var(--clr-red); }
.sc-g .ico { background: var(--clr-green-dim); color: var(--clr-green); }
.sc-a .ico { background: var(--clr-amber-dim); color: var(--clr-amber); }
.sc-s .ico { background: rgba(30,41,59,0.08);  color: var(--clr-slate); }

.sc .val {
  font-size: 1.8rem; font-weight: 800; line-height: 1;
  letter-spacing: -0.03em; margin-bottom: 4px;
}
.sc-b .val { color: var(--clr-blue); }
.sc-r .val { color: var(--clr-red); }
.sc-g .val { color: var(--clr-green); }
.sc-a .val { color: var(--clr-amber); }
.sc-s .val { color: var(--clr-slate); font-size: 1.05rem; line-height: 1.3; }

.sc .lbl {
  font-size: 0.7rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: 0.5px;
  color: var(--clr-muted);
}
.sc .sub { font-size: 0.67rem; color: #94a3b8; margin-top: 3px; }

/* ── Quick Stats Bar ─────────────────────────────────────────── */
.qs-bar {
  display: flex; flex-wrap: wrap; gap: 0;
  background: var(--clr-surface);
  border: 1px solid var(--clr-border);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  margin-bottom: 22px; overflow: hidden;
}
.qs-item {
  display: flex; flex-direction: column;
  align-items: center; flex: 1; min-width: 110px;
  padding: 18px 12px;
  border-right: 1px solid var(--clr-border);
  transition: var(--transition);
}
.qs-item:last-child { border-right: none; }
.qs-item:hover { background: var(--clr-bg); }
.qs-val {
  font-size: 1.5rem; font-weight: 800;
  letter-spacing: -0.02em; line-height: 1;
}
.qs-lbl {
  font-size: 0.67rem; font-weight: 600;
  text-transform: uppercase; letter-spacing: 0.4px;
  color: var(--clr-muted); margin-top: 4px; text-align: center;
}

/* ── Charts ──────────────────────────────────────────────────── */
.chart-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px; margin-bottom: 22px;
}
@media (max-width: 900px) { .chart-grid { grid-template-columns: 1fr; } }
.chart-card {
  background: var(--clr-surface);
  border: 1px solid var(--clr-border);
  border-radius: var(--radius);
  padding: 18px;
  box-shadow: var(--shadow);
}
.chart-title {
  font-size: 0.85rem; font-weight: 700;
  color: var(--clr-slate); margin-bottom: 14px;
  display: flex; align-items: center; gap: 8px;
  padding-bottom: 12px; border-bottom: 1px solid var(--clr-border);
}
.chart-title i { color: var(--clr-blue); }

/* ── Table Card ──────────────────────────────────────────────── */
.tc {
  background: var(--clr-surface);
  border: 1px solid var(--clr-border);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  overflow: hidden; margin-bottom: 20px;
}
.tc-header {
  padding: 16px 20px;
  border-bottom: 1px solid var(--clr-border);
  display: flex; align-items: center; gap: 10px;
  font-size: 0.88rem; font-weight: 700;
}
.tc-header-navy {
  background: linear-gradient(90deg, var(--clr-navy), var(--clr-navy-mid));
  color: #f1f5f9;
}
.tc-header-blue {
  background: linear-gradient(90deg, #dbeafe, #eff6ff);
  color: #1d4ed8;
}
.tc-header-green {
  background: linear-gradient(90deg, #dcfce7, #f0fdf4);
  color: #166534;
}
.tc-header-amber {
  background: linear-gradient(90deg, #fef9c3, #fffbeb);
  color: #92400e;
}
.tc-header .tc-count {
  margin-left: auto; font-size: 0.72rem; font-weight: 500;
  opacity: 0.75;
}

/* ── Data Table ──────────────────────────────────────────────── */
.dt { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
.dt thead th {
  background: var(--clr-bg);
  font-size: 0.71rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: 0.5px;
  color: var(--clr-muted); padding: 10px 16px;
  border-bottom: 1px solid var(--clr-border);
  white-space: nowrap; text-align: left;
}
.dt tbody td {
  padding: 11px 16px;
  border-bottom: 1px solid #f0f4f8;
  color: var(--clr-text); vertical-align: middle;
}
.dt tbody tr:last-child td { border-bottom: none; }
.dt tbody tr:hover { background: #f8fafc; }

/* ── Badges ──────────────────────────────────────────────────── */
.badge-wish { background: #fee2e2; color: #991b1b; border-radius: 20px; padding: 3px 11px; font-size: .71rem; font-weight: 700; display: inline-block; }
.badge-cart { background: #dcfce7; color: #166534; border-radius: 20px; padding: 3px 11px; font-size: .71rem; font-weight: 700; display: inline-block; }
.badge-int  { background: var(--clr-blue-dim); color: var(--clr-blue); border-radius: 20px; padding: 3px 11px; font-size: .71rem; font-weight: 700; display: inline-block; }
.badge-zero { background: var(--clr-bg); color: var(--clr-muted); border-radius: 20px; padding: 3px 11px; font-size: .71rem; display: inline-block; }
.badge-cat  { background: var(--clr-blue-dim); color: var(--clr-blue); border-radius: 6px; padding: 3px 10px; font-size: .73rem; font-weight: 600; display: inline-block; }

/* Product name cell */
.pnc { display: flex; align-items: center; gap: 10px; }
.prod-thumb {
  width: 36px; height: 36px; object-fit: cover;
  border-radius: 6px; border: 1px solid var(--clr-border); flex-shrink: 0;
}
.prod-no-img {
  width: 36px; height: 36px; border-radius: 6px;
  background: var(--clr-bg); border: 1px solid var(--clr-border);
  display: flex; align-items: center; justify-content: center;
  color: #94a3b8; font-size: 0.9rem; flex-shrink: 0;
}
.prod-name-text { font-weight: 600; color: var(--clr-slate); }

/* Rank badge */
.rank-badge {
  width: 28px; height: 28px; border-radius: 6px;
  background: var(--clr-bg); border: 1px solid var(--clr-border);
  display: flex; align-items: center; justify-content: center;
  font-size: 0.8rem; font-weight: 800; color: var(--clr-muted);
  flex-shrink: 0;
}
.rank-badge.top1 { background: var(--clr-blue); color: #fff; border-color: var(--clr-blue); }
.rank-badge.top2 { background: var(--clr-slate); color: #fff; border-color: var(--clr-slate); }
.rank-badge.top3 { background: var(--clr-blue-dim); color: var(--clr-blue); }

/* Color swatch */
.cs {
  display: inline-block; width: 13px; height: 13px;
  border-radius: 3px; border: 1px solid rgba(0,0,0,.1);
  vertical-align: middle; margin-right: 5px; flex-shrink: 0;
}

/* Empty state */
.es { text-align: center; padding: 50px 20px; color: var(--clr-muted); }
.es i { font-size: 2.2rem; display: block; margin-bottom: 10px; color: #cbd5e1; }
.es p { font-size: 0.88rem; margin: 0; }
.es small { font-size: 0.76rem; color: #94a3b8; margin-top: 4px; display: block; }

/* ── Export Modal ────────────────────────────────────────────── */
.modal-content { border-radius: 14px; border: none; overflow: hidden; box-shadow: 0 24px 80px rgba(0,0,0,0.2); }
.modal-content .modal-header {
  background: linear-gradient(135deg, var(--clr-navy), var(--clr-navy-mid));
  color: #fff; padding: 18px 24px; border-bottom: none;
}
.modal-content .modal-header .modal-title { font-size: 0.95rem; font-weight: 700; display: flex; align-items: center; gap: 8px; }
.modal-content .modal-header .btn-close { filter: invert(1); opacity: .7; }
.modal-content .modal-footer { background: var(--clr-bg); border-top: 1px solid var(--clr-border); gap: 8px; }

/* Report Preview */
#reportPreview {
  font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
  font-size: 12px; color: var(--clr-slate); background: #fff; padding: 0;
}
.rp-cover {
  background: linear-gradient(135deg, var(--clr-navy) 0%, var(--clr-navy-mid) 100%);
  color: #fff; padding: 32px 36px;
  display: flex; justify-content: space-between; align-items: flex-start;
  flex-wrap: wrap; gap: 14px;
}
.rp-cover-logo { font-size: 1.1rem; font-weight: 800; letter-spacing: -0.3px; }
.rp-cover-logo span { color: var(--clr-blue-light); }
.rp-cover-title { font-size: 1rem; font-weight: 700; margin-bottom: 4px; }
.rp-cover-sub { font-size: 0.77rem; color: #94a3b8; }
.rp-cover-right { text-align: right; }
.rp-cover-meta { font-size: 0.67rem; color: #64748b; margin-top: 8px; }
.rp-body { padding: 24px 28px; }
.rp-section { margin-bottom: 24px; }
.rp-section-title {
  font-size: 0.72rem; font-weight: 800;
  text-transform: uppercase; letter-spacing: 1px;
  color: var(--clr-blue);
  border-bottom: 2px solid var(--clr-blue-dim);
  padding-bottom: 6px; margin-bottom: 14px;
  display: flex; align-items: center; gap: 6px;
}
.rp-filters-block {
  background: var(--clr-blue-dim); border-left: 3px solid var(--clr-blue);
  padding: 10px 14px; border-radius: 6px; font-size: 0.78rem;
}
.rp-cards { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; margin-bottom: 20px; }
.rp-card {
  background: var(--clr-bg); border-radius: 8px; padding: 12px;
  text-align: center; border: 1px solid var(--clr-border);
}
.rp-card-val { font-size: 1.25rem; font-weight: 800; margin-bottom: 2px; }
.rp-card-lbl { font-size: 0.64rem; text-transform: uppercase; letter-spacing: 0.4px; color: var(--clr-muted); }
.rp-table { width: 100%; border-collapse: collapse; font-size: 0.78rem; }
.rp-table th {
  background: var(--clr-navy); color: #fff;
  padding: 7px 10px; font-size: 0.67rem;
  text-transform: uppercase; letter-spacing: 0.5px; text-align: left;
}
.rp-table td { padding: 7px 10px; border-bottom: 1px solid var(--clr-border); }
.rp-table tbody tr:nth-child(even) td { background: var(--clr-bg); }
.rp-footer {
  background: var(--clr-bg); border-top: 2px solid var(--clr-border);
  padding: 14px 28px; display: flex; justify-content: space-between;
  font-size: 0.7rem; color: var(--clr-muted); flex-wrap: wrap; gap: 8px;
}

/* ── Print ───────────────────────────────────────────────────── */
@media print {
  .seller-sidebar, .seller-topbar, .rp-page-header, .rp-filter-card,
  .rp-tabs, .tab-panel, .no-print, .modal-header, .modal-footer,
  .modal-backdrop, body > *:not(.modal) { display: none !important; }
  body { background: #fff !important; margin: 0; padding: 0; }
  .modal { display: block !important; position: static !important; }
  .modal-dialog { max-width: 100% !important; margin: 0; }
  .modal-content { border: none !important; border-radius: 0 !important; box-shadow: none !important; }
  .modal-body { padding: 0 !important; overflow: visible !important; max-height: none !important; }
  #reportPreview { display: block !important; }
  #reportPreview * { visibility: visible !important; }
  .rp-cover, .rp-table th, .rp-footer { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>

<div style="padding-bottom: 40px;">

  {{-- ── HEADER ──────────────────────────────────────────────── --}}
  <div class="rp-page-header no-print">
    <div>
      <h1><i class="bi bi-bar-chart-line-fill"></i>Analytics & Reports</h1>
      <p>{{ $seller->business_name }} &nbsp;·&nbsp; Generated {{ now()->format('d M Y, h:i A') }}</p>
    </div>
    <div>
      <button class="btn-export" data-bs-toggle="modal" data-bs-target="#exportModal" id="openExportBtn">
        <i class="bi bi-file-earmark-arrow-down-fill"></i> Export Report
      </button>
    </div>
  </div>

  {{-- ── FILTERS ──────────────────────────────────────────────── --}}
  <div class="rp-filter-card no-print">
    <div class="filter-title"><i class="bi bi-funnel-fill"></i> Report Filters</div>
    <form method="GET" action="{{ route('seller.reports.index') }}" id="filterForm">
      <input type="hidden" name="tab" id="activeTabInput" value="{{ request('tab', 'overview') }}">

      <div class="filter-row">
        <div class="filter-group" style="min-width:200px;">
          <label>Search Product</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Type product name…">
        </div>
        <div class="filter-group">
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
        <div class="filter-group">
          <label>Product</label>
          <select name="product_id">
            <option value="">All Products</option>
            @foreach($sellerProducts as $p)
              <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>
                {{ Str::limit($p->name, 40) }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="filter-group" style="min-width:130px;">
          <label>From Date</label>
          <input type="date" name="date_from" id="srDateFrom" value="{{ request('date_from') }}">
        </div>
        <div class="filter-group" style="min-width:130px;">
          <label>To Date</label>
          <input type="date" name="date_to" id="srDateTo" value="{{ request('date_to') }}">
        </div>
        <input type="hidden" name="quick_date" id="srQdInput" value="{{ request('quick_date', '') }}">
      </div>

      <div class="qd-row">
        <span class="qd-label">Quick:</span>
        @foreach(['today'=>'Today','yesterday'=>'Yesterday','last7'=>'Last 7 Days','last30'=>'Last 30 Days','this_month'=>'This Month','last_month'=>'Last Month','custom'=>'Custom'] as $v=>$l)
          <button type="button" class="qd-btn {{ request('quick_date')===$v?'active':'' }}"
                  onclick="srSetQD('{{ $v }}')">{{ $l }}</button>
        @endforeach
      </div>

      <div class="filter-actions">
        <button type="submit" class="btn-filter"><i class="bi bi-funnel me-1"></i>Filter</button>
        <a href="{{ route('seller.reports.index') }}" class="btn-reset"><i class="bi bi-x-circle me-1"></i>Reset</a>
        <button type="button" class="btn-refresh" onclick="location.reload()"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button>
      </div>
    </form>
  </div>

  {{-- ── TABS ─────────────────────────────────────────────────── --}}
  <div class="rp-tabs no-print">
    <button class="rp-tab" id="tabBtnOverview" onclick="srShowTab('tabOverview','overview',this)">
      <i class="bi bi-grid-1x2-fill"></i> Overview
    </button>
    <button class="rp-tab" id="tabBtnTopProducts" onclick="srShowTab('tabTopProducts','top-products',this)">
      <i class="bi bi-star-fill"></i> Top Products
      <span class="tab-count">{{ $topProducts->count() }}</span>
    </button>
    <button class="rp-tab" id="tabBtnVariant" onclick="srShowTab('tabVariant','variant',this)">
      <i class="bi bi-layers-fill"></i> Variant Analytics
      <span class="tab-count">{{ $variantAnalytics->count() }}</span>
    </button>
    <button class="rp-tab" id="tabBtnZero" onclick="srShowTab('tabZero','zero',this)">
      <i class="bi bi-exclamation-triangle-fill"></i> Zero Interest
      @php $zeroCount = $zeroInterest->count() + $untrackedProds->count(); @endphp
      <span class="tab-count {{ $zeroCount > 0 ? 'danger-count' : '' }}">{{ $zeroCount }}</span>
    </button>
  </div>

  {{-- ═══════════════════════════════════════════════════
       TAB 1 — OVERVIEW
  ═══════════════════════════════════════════════════ --}}
  <div id="tabOverview" class="tab-panel">

    {{-- Summary Cards --}}
    <div class="sc-grid">
      <div class="sc sc-b">
        <div class="ico"><i class="bi bi-box-seam-fill"></i></div>
        <div class="val">{{ $totalProducts }}</div>
        <div class="lbl">Total Products</div>
      </div>
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
      <div class="sc sc-b">
        <div class="ico"><i class="bi bi-lightning-fill"></i></div>
        <div class="val">{{ $totalInterest }}</div>
        <div class="lbl">Total Interest</div>
      </div>
      <div class="sc sc-a">
        <div class="ico"><i class="bi bi-eye-slash-fill"></i></div>
        <div class="val">{{ $zeroInterestCount }}</div>
        <div class="lbl">Zero Interest</div>
      </div>
      <div class="sc sc-s">
        <div class="ico"><i class="bi bi-hourglass-split"></i></div>
        <div class="val" style="font-size:1.8rem;">{{ $pendingCount }}</div>
        <div class="lbl">Pending Approval</div>
      </div>
      <div class="sc sc-s">
        <div class="ico"><i class="bi bi-trophy-fill"></i></div>
        <div class="val">{{ Str::limit($mostPopular?->product?->name ?? 'N/A', 18) }}</div>
        <div class="lbl">Most Popular</div>
        @if($mostPopular)
          <div class="sub">♥ {{ $mostPopular->wishlist_count }} · 🛒 {{ $mostPopular->cart_count }}</div>
        @endif
      </div>
    </div>

    {{-- Quick Stats Bar --}}
    <div class="qs-bar">
      <div class="qs-item">
        <div class="qs-val" style="color:var(--clr-blue);">{{ $totalProducts > 0 ? round(($totalInterest / $totalProducts), 1) : 0 }}</div>
        <div class="qs-lbl">Avg. Interest / Product</div>
      </div>
      <div class="qs-item">
        <div class="qs-val" style="color:var(--clr-red);">{{ $totalInterest > 0 ? round(($totalWishlist / $totalInterest) * 100) : 0 }}%</div>
        <div class="qs-lbl">Wishlist Share</div>
      </div>
      <div class="qs-item">
        <div class="qs-val" style="color:var(--clr-green);">{{ $totalInterest > 0 ? round(($totalCart / $totalInterest) * 100) : 0 }}%</div>
        <div class="qs-lbl">Cart Share</div>
      </div>
      <div class="qs-item">
        <div class="qs-val" style="color:var(--clr-amber);">{{ $totalProducts > 0 ? round((($totalProducts - $zeroInterestCount) / $totalProducts) * 100) : 0 }}%</div>
        <div class="qs-lbl">Products w/ Interest</div>
      </div>
      <div class="qs-item">
        <div class="qs-val" style="color:var(--clr-blue);">{{ $variantAnalytics->count() }}</div>
        <div class="qs-lbl">Total Variants</div>
      </div>
    </div>

    {{-- Charts --}}
    @if($topProducts->isNotEmpty())
    <div class="chart-grid">
      <div class="chart-card">
        <div class="chart-title"><i class="bi bi-bar-chart-fill"></i>Wishlist vs Cart — Top Products</div>
        <div id="chartWishlistCart" style="min-height:280px;"></div>
      </div>
      <div class="chart-card">
        <div class="chart-title"><i class="bi bi-pie-chart-fill"></i>Category Distribution</div>
        <div id="chartCategoryDonut" style="min-height:280px;"></div>
      </div>
      <div class="chart-card">
        <div class="chart-title"><i class="bi bi-graph-up-arrow"></i>Total Interest by Product</div>
        <div id="chartInterestTrend" style="min-height:280px;"></div>
      </div>
      <div class="chart-card">
        <div class="chart-title"><i class="bi bi-list-ol"></i>Top Products Ranking</div>
        <div id="chartTopProducts" style="min-height:280px;"></div>
      </div>
    </div>
    @else
    <div class="chart-card">
      <div class="es">
        <i class="bi bi-bar-chart"></i>
        <p>No analytics data available yet.</p>
        <small>Analytics will appear once customers start interacting with your products.</small>
      </div>
    </div>
    @endif

  </div>{{-- /tabOverview --}}

  {{-- ═══════════════════════════════════════════════════
       TAB 2 — TOP PRODUCTS
  ═══════════════════════════════════════════════════ --}}
  <div id="tabTopProducts" class="tab-panel">
    <div class="tc">
      <div class="tc-header tc-header-navy">
        <i class="bi bi-star-fill"></i> Top Products by Customer Interest
        <span class="tc-count">{{ $topProducts->count() }} product{{ $topProducts->count() !== 1 ? 's' : '' }}</span>
      </div>
      <div style="overflow-x:auto;">
        <table class="dt">
          <thead>
            <tr>
              <th style="width:50px;">#</th>
              <th>Product</th>
              <th>Category</th>
              <th>Wishlist</th>
              <th>Cart</th>
              <th>Total Interest</th>
            </tr>
          </thead>
          <tbody>
            @forelse($topProducts as $idx => $a)
              <tr>
                <td>
                  <div class="rank-badge {{ $idx===0?'top1':($idx===1?'top2':($idx===2?'top3':'')) }}">
                    {{ $idx + 1 }}
                  </div>
                </td>
                <td>
                  <div class="pnc">
                    @if($a->product?->image)
                      <img src="{{ asset('storage/' . $a->product->image) }}" class="prod-thumb" alt="">
                    @else
                      <div class="prod-no-img"><i class="bi bi-image"></i></div>
                    @endif
                    <span class="prod-name-text">{{ $a->product?->name ?? 'Unknown' }}</span>
                  </div>
                </td>
                <td>
                  @if($a->product?->category?->name)
                    <span class="badge-cat">{{ $a->product->category->name }}</span>
                  @else
                    <span class="badge-zero">—</span>
                  @endif
                </td>
                <td><span class="badge-wish">♥ {{ $a->wishlist_count }}</span></td>
                <td><span class="badge-cart">🛒 {{ $a->cart_count }}</span></td>
                <td><span class="badge-int">⚡ {{ $a->total_interest }}</span></td>
              </tr>
            @empty
              <tr>
                <td colspan="6">
                  <div class="es">
                    <i class="bi bi-inbox"></i>
                    <p>No analytics data yet.</p>
                    <small>Customer interactions will appear here once activity is recorded.</small>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>{{-- /tabTopProducts --}}

  {{-- ═══════════════════════════════════════════════════
       TAB 3 — VARIANT ANALYTICS
  ═══════════════════════════════════════════════════ --}}
  <div id="tabVariant" class="tab-panel">
    <div class="tc">
      <div class="tc-header tc-header-blue">
        <i class="bi bi-layers-fill"></i> Variant Analytics — Per Color & Size
        <span class="tc-count" style="background:rgba(37,99,235,0.12);color:var(--clr-blue);padding:2px 10px;border-radius:6px;">
          ✅ Per-variant accurate counts
        </span>
      </div>
      <div style="overflow-x:auto;">
        <table class="dt">
          <thead>
            <tr>
              <th>Product</th>
              <th>Color / Variant</th>
              <th>Sizes Available</th>
              <th>Wishlist</th>
              <th>Cart</th>
              <th>Total Interest</th>
            </tr>
          </thead>
          <tbody>
            @forelse($variantAnalytics as $va)
              <tr>
                <td class="prod-name-text" style="font-weight:600;color:var(--clr-slate);">{{ $va['product']->name ?? 'Unknown' }}</td>
                <td>
                  @if($va['color'])
                    <div style="display:flex;align-items:center;gap:6px;">
                      <span class="cs" style="background:{{ $va['color']->code ?? '#ccc' }};"></span>
                      <span>{{ $va['color']->name }}</span>
                    </div>
                  @else
                    <span class="badge-zero">N/A</span>
                  @endif
                </td>
                <td>
                  @forelse($va['sizes'] as $sz)
                    <span class="badge-zero" style="margin:2px;">{{ $sz->name }} <span style="color:#94a3b8;">({{ $sz->pivot->stock }})</span></span>
                  @empty
                    <span class="badge-zero">—</span>
                  @endforelse
                </td>
                <td><span class="{{ $va['wishlist'] > 0 ? 'badge-wish' : 'badge-zero' }}">♥ {{ $va['wishlist'] }}</span></td>
                <td><span class="{{ $va['cart'] > 0 ? 'badge-cart' : 'badge-zero' }}">🛒 {{ $va['cart'] }}</span></td>
                <td><span class="{{ $va['total_interest'] > 0 ? 'badge-int' : 'badge-zero' }}">⚡ {{ $va['total_interest'] }}</span></td>
              </tr>
            @empty
              <tr>
                <td colspan="6">
                  <div class="es">
                    <i class="bi bi-layers"></i>
                    <p>No variant data available.</p>
                    <small>Add product variants with colors and sizes to see analytics here.</small>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>{{-- /tabVariant --}}

  {{-- ═══════════════════════════════════════════════════
       TAB 4 — ZERO INTEREST
  ═══════════════════════════════════════════════════ --}}
  <div id="tabZero" class="tab-panel">
    <div class="tc">
      <div class="tc-header tc-header-amber">
        <i class="bi bi-exclamation-triangle-fill"></i> Zero Interest Products
        <span class="tc-count">Products with no customer activity</span>
      </div>
      <div style="overflow-x:auto;">
        @if($zeroInterest->isEmpty() && $untrackedProds->isEmpty())
          <div class="es" style="padding:60px 20px;">
            <i class="bi bi-emoji-smile-fill" style="color:var(--clr-green);"></i>
            <p style="font-size:1rem;font-weight:700;color:var(--clr-green);margin-bottom:6px;">
              All your products have customer interest.
            </p>
            <small>Every product has been wishlisted or added to cart. Keep up the great work!</small>
          </div>
        @else
          <table class="dt">
            <thead>
              <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Status</th>
                <th>Wishlist</th>
                <th>Cart</th>
              </tr>
            </thead>
            <tbody>
              @foreach($untrackedProds as $p)
                <tr>
                  <td style="font-weight:600;color:var(--clr-slate);">{{ $p->name }}</td>
                  <td>{{ $p->category?->name ?? '—' }}</td>
                  <td><span class="badge-zero">No Analytics Data</span></td>
                  <td><span class="badge-zero">♥ 0</span></td>
                  <td><span class="badge-zero">🛒 0</span></td>
                </tr>
              @endforeach
              @foreach($zeroInterest as $a)
                <tr>
                  <td style="font-weight:600;color:var(--clr-slate);">{{ $a->product?->name ?? 'Unknown' }}</td>
                  <td>{{ $a->product?->category?->name ?? '—' }}</td>
                  <td><span style="background:var(--clr-amber-dim);color:var(--clr-amber);border-radius:20px;padding:2px 10px;font-size:.71rem;font-weight:700;display:inline-block;">Zero Interest</span></td>
                  <td><span class="badge-zero">♥ 0</span></td>
                  <td><span class="badge-zero">🛒 0</span></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @endif
      </div>
    </div>
  </div>{{-- /tabZero --}}

</div>{{-- /bg-wrapper --}}

{{-- ══════════════════════════════════════════════════════
     EXPORT MODAL
══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <span class="modal-title" id="exportModalLabel">
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
                <div class="rp-cover-title">Seller Analytics Report</div>
                <div class="rp-cover-sub">{{ $seller->business_name }} &nbsp;·&nbsp; Owner: {{ $seller->owner_name }}</div>
              </div>
            </div>
            <div class="rp-cover-right">
              <div style="font-size:.7rem;color:#64748b;margin-bottom:4px;text-transform:uppercase;letter-spacing:1px;">Generated</div>
              <div style="font-size:.9rem;font-weight:700;">{{ now()->format('d M Y') }}</div>
              <div style="font-size:.75rem;color:#94a3b8;">{{ now()->format('h:i A') }}</div>
              <div class="rp-cover-meta" style="margin-top:14px;">Confidential · For Seller Use Only</div>
            </div>
          </div>

          <div class="rp-body">

            {{-- Filters --}}
            <div class="rp-section">
              <div class="rp-section-title"><i class="bi bi-funnel-fill"></i>Applied Filters</div>
              <div class="rp-filters-block" id="rp-filters-content">
                <span>Loading filters…</span>
              </div>
            </div>

            {{-- Summary Cards --}}
            <div class="rp-section">
              <div class="rp-section-title"><i class="bi bi-grid-1x2-fill"></i>Summary Overview</div>
              <div class="rp-cards">
                <div class="rp-card">
                  <div class="rp-card-val" style="color:var(--clr-blue);">{{ $totalProducts }}</div>
                  <div class="rp-card-lbl">Total Products</div>
                </div>
                <div class="rp-card">
                  <div class="rp-card-val" style="color:var(--clr-red);">{{ $totalWishlist }}</div>
                  <div class="rp-card-lbl">Wishlist Count</div>
                </div>
                <div class="rp-card">
                  <div class="rp-card-val" style="color:var(--clr-green);">{{ $totalCart }}</div>
                  <div class="rp-card-lbl">Cart Count</div>
                </div>
                <div class="rp-card">
                  <div class="rp-card-val" style="color:var(--clr-blue);">{{ $totalInterest }}</div>
                  <div class="rp-card-lbl">Total Interest</div>
                </div>
                <div class="rp-card">
                  <div class="rp-card-val" style="color:var(--clr-amber);">{{ $zeroInterestCount }}</div>
                  <div class="rp-card-lbl">Zero Interest</div>
                </div>
                <div class="rp-card">
                  <div class="rp-card-val" style="color:var(--clr-slate);">{{ $pendingCount }}</div>
                  <div class="rp-card-lbl">Pending Approval</div>
                </div>
                <div class="rp-card" style="grid-column:span 2;">
                  <div class="rp-card-val" style="color:var(--clr-muted);font-size:1rem;">
                    {{ Str::limit($mostPopular?->product?->name ?? 'N/A', 30) }}
                  </div>
                  <div class="rp-card-lbl">Most Popular Product</div>
                  @if($mostPopular)
                    <div style="font-size:.64rem;color:#94a3b8;margin-top:3px;">
                      ♥ {{ $mostPopular->wishlist_count }} · 🛒 {{ $mostPopular->cart_count }}
                    </div>
                  @endif
                </div>
              </div>
            </div>

            {{-- Top Products Table --}}
            <div class="rp-section">
              <div class="rp-section-title"><i class="bi bi-star-fill"></i>Top Products by Customer Interest</div>
              <div class="tc" style="margin-bottom:0;">
                <table class="rp-table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Product Name</th>
                      <th>Category</th>
                      <th style="text-align:center;">Wishlist</th>
                      <th style="text-align:center;">Cart</th>
                      <th style="text-align:center;">Total Interest</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($topProducts->take(20) as $idx => $a)
                      <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td style="font-weight:600;">{{ $a->product?->name ?? 'Unknown' }}</td>
                        <td>{{ $a->product?->category?->name ?? '—' }}</td>
                        <td style="text-align:center;">{{ $a->wishlist_count }}</td>
                        <td style="text-align:center;">{{ $a->cart_count }}</td>
                        <td style="text-align:center;font-weight:700;color:var(--clr-blue);">{{ $a->total_interest }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="6" style="text-align:center;color:#94a3b8;padding:16px;">No analytics data available.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            {{-- Variant Analytics Table --}}
            @if($variantAnalytics->isNotEmpty())
            <div class="rp-section">
              <div class="rp-section-title"><i class="bi bi-layers-fill"></i>Variant Analytics — Per Color</div>
              <div class="tc" style="margin-bottom:0;">
                <table class="rp-table">
                  <thead>
                    <tr>
                      <th>Product</th>
                      <th>Color / Variant</th>
                      <th>Sizes</th>
                      <th style="text-align:center;">Wishlist</th>
                      <th style="text-align:center;">Cart</th>
                      <th style="text-align:center;">Interest</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($variantAnalytics->take(30) as $va)
                      <tr>
                        <td style="font-weight:600;">{{ $va['product']->name ?? 'Unknown' }}</td>
                        <td>{{ $va['color']?->name ?? 'N/A' }}</td>
                        <td>
                          @foreach($va['sizes'] as $sz)
                            {{ $sz->name }}({{ $sz->pivot->stock }}){{ !$loop->last ? ', ' : '' }}
                          @endforeach
                        </td>
                        <td style="text-align:center;">{{ $va['wishlist'] }}</td>
                        <td style="text-align:center;">{{ $va['cart'] }}</td>
                        <td style="text-align:center;font-weight:700;color:var(--clr-blue);">{{ $va['total_interest'] }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            @endif

            {{-- Zero Interest Table --}}
            @if($zeroInterest->isNotEmpty() || $untrackedProds->isNotEmpty())
            <div class="rp-section">
              <div class="rp-section-title"><i class="bi bi-exclamation-triangle-fill"></i>Zero Interest Products</div>
              <div class="tc" style="margin-bottom:0;">
                <table class="rp-table">
                  <thead>
                    <tr><th>Product Name</th><th>Category</th><th>Status</th></tr>
                  </thead>
                  <tbody>
                    @foreach($untrackedProds as $p)
                      <tr>
                        <td style="font-weight:600;">{{ $p->name }}</td>
                        <td>{{ $p->category?->name ?? '—' }}</td>
                        <td>No Analytics Data</td>
                      </tr>
                    @endforeach
                    @foreach($zeroInterest as $a)
                      <tr>
                        <td style="font-weight:600;">{{ $a->product?->name ?? 'Unknown' }}</td>
                        <td>{{ $a->product?->category?->name ?? '—' }}</td>
                        <td>0 Wishlist · 0 Cart</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            @endif

          </div>{{-- /rp-body --}}

          {{-- Footer --}}
          <div class="rp-footer">
            <span><strong>Flipkart Marketplace</strong> — Confidential</span>
            <span>{{ $seller->business_name }}</span>
            <span>{{ now()->format('d M Y, h:i A') }}</span>
          </div>

        </div>{{-- /reportPreview --}}
      </div>{{-- /modal-body --}}

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i>Close
        </button>
        <button type="button" class="btn btn-primary btn-sm fw-bold" onclick="srPrintReport()">
          <i class="bi bi-printer-fill me-1"></i>Print Report
        </button>
        <button type="button" class="btn btn-warning btn-sm fw-bold" onclick="srDownloadPDF()">
          <i class="bi bi-file-earmark-pdf-fill me-1"></i>Download PDF
        </button>
      </div>

    </div>
  </div>
</div>{{-- /exportModal --}}

{{-- ── SCRIPTS ──────────────────────────────────────────────── --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// ── Tab System ──────────────────────────────────────────────
function srShowTab(panelId, tabKey, btn) {
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.rp-tab').forEach(t => t.classList.remove('active'));
  document.getElementById(panelId).classList.add('active');
  btn.classList.add('active');
  document.getElementById('activeTabInput').value = tabKey;
  localStorage.setItem('srActiveTab', tabKey);
}

document.addEventListener('DOMContentLoaded', function () {
  const urlTab    = '{{ request("tab", "") }}';
  const lsTab     = localStorage.getItem('srActiveTab') || '';
  const activeKey = urlTab || lsTab || 'overview';

  const tabMap = {
    'overview':     { panel: 'tabOverview',    btn: 'tabBtnOverview' },
    'top-products': { panel: 'tabTopProducts', btn: 'tabBtnTopProducts' },
    'variant':      { panel: 'tabVariant',     btn: 'tabBtnVariant' },
    'zero':         { panel: 'tabZero',        btn: 'tabBtnZero' },
  };

  const target = tabMap[activeKey] || tabMap['overview'];
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.rp-tab').forEach(t => t.classList.remove('active'));
  document.getElementById(target.panel).classList.add('active');
  document.getElementById(target.btn).classList.add('active');
  document.getElementById('activeTabInput').value = activeKey;

  // ── Charts ────────────────────────────────────────────────
  const labels   = {!! json_encode($chartLabels->values()) !!};
  const wishlist = {!! json_encode($chartWishlist->values()) !!};
  const cart     = {!! json_encode($chartCart->values()) !!};
  const interest = {!! json_encode($chartInterest->values()) !!};
  const catLabels= {!! json_encode($catChartLabels->values()) !!};
  const catData  = {!! json_encode($catChartData->values()) !!};

  // Cohesive palette: navy / blue shades / slate tones
  const PALETTE  = ['#2563eb','#0f1f3d','#3b82f6','#1e3a5f','#60a5fa','#162447','#93c5fd','#0ea5e9'];

  const CHART_OPTS = {
    chart: { fontFamily: 'Inter, system-ui, sans-serif', toolbar: { show: false } },
    grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
    tooltip: { theme: 'light' },
  };

  if (labels.length > 0 && document.querySelector('#chartWishlistCart')) {
    new ApexCharts(document.querySelector('#chartWishlistCart'), {
      ...CHART_OPTS,
      series: [{ name: 'Wishlist', data: wishlist }, { name: 'Cart', data: cart }],
      chart: { ...CHART_OPTS.chart, type: 'bar', height: 280 },
      plotOptions: { bar: { columnWidth: '55%', borderRadius: 4 } },
      dataLabels: { enabled: false },
      xaxis: { categories: labels, labels: { rotate: -30, style: { fontSize: '10px' } } },
      colors: ['#dc2626', '#16a34a'],
      legend: { position: 'top', fontSize: '12px' },
      tooltip: { y: { formatter: v => v + ' times' } },
    }).render();
  }

  if (catLabels.length > 0 && document.querySelector('#chartCategoryDonut')) {
    new ApexCharts(document.querySelector('#chartCategoryDonut'), {
      ...CHART_OPTS,
      series: catData, labels: catLabels,
      chart: { ...CHART_OPTS.chart, type: 'donut', height: 280 },
      colors: PALETTE,
      legend: { position: 'bottom', fontSize: '11px' },
      plotOptions: { pie: { donut: { size: '60%' } } },
      tooltip: { y: { formatter: v => v + ' interest' } },
    }).render();
  }

  if (labels.length > 0 && document.querySelector('#chartInterestTrend')) {
    new ApexCharts(document.querySelector('#chartInterestTrend'), {
      ...CHART_OPTS,
      series: [{ name: 'Total Interest', data: interest }],
      chart: { ...CHART_OPTS.chart, type: 'area', height: 280 },
      dataLabels: { enabled: false },
      stroke: { curve: 'smooth', width: 2.5 },
      xaxis: { categories: labels, labels: { rotate: -30, style: { fontSize: '10px' } } },
      colors: ['#2563eb'],
      fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.04 } },
      tooltip: { y: { formatter: v => v + ' total' } },
    }).render();
  }

  if (labels.length > 0 && document.querySelector('#chartTopProducts')) {
    new ApexCharts(document.querySelector('#chartTopProducts'), {
      ...CHART_OPTS,
      series: [{ name: 'Total Interest', data: interest }],
      chart: { ...CHART_OPTS.chart, type: 'bar', height: 280 },
      plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '60%' } },
      dataLabels: { enabled: true, style: { fontSize: '10px' } },
      xaxis: { categories: labels },
      colors: ['#0f1f3d'],
      tooltip: { y: { formatter: v => v + ' interest' } },
    }).render();
  }
});

// ── Quick Date ──────────────────────────────────────────────
function srSetQD(val) {
  document.getElementById('srQdInput').value = val;
  document.querySelectorAll('.qd-btn').forEach(b => b.classList.remove('active'));
  event.target.classList.add('active');
  const today = new Date(), fmt = d => d.toISOString().split('T')[0];
  const df = document.getElementById('srDateFrom'), dt = document.getElementById('srDateTo');
  if (val === 'today')       { df.value = fmt(today); dt.value = fmt(today); }
  else if (val === 'yesterday') { const y = new Date(today); y.setDate(y.getDate()-1); df.value = fmt(y); dt.value = fmt(y); }
  else if (val === 'last7')  { const s = new Date(today); s.setDate(s.getDate()-6); df.value = fmt(s); dt.value = fmt(today); }
  else if (val === 'last30') { const s = new Date(today); s.setDate(s.getDate()-29); df.value = fmt(s); dt.value = fmt(today); }
  else if (val === 'this_month') { df.value = fmt(new Date(today.getFullYear(),today.getMonth(),1)); dt.value = fmt(new Date(today.getFullYear(),today.getMonth()+1,0)); }
  else if (val === 'last_month') { df.value = fmt(new Date(today.getFullYear(),today.getMonth()-1,1)); dt.value = fmt(new Date(today.getFullYear(),today.getMonth(),0)); }
  else { df.value = ''; dt.value = ''; }
}

// ── Export Modal: Populate Applied Filters ──────────────────
document.getElementById('openExportBtn').addEventListener('click', function () {
  const params = new URLSearchParams(window.location.search);
  const parts  = [];
  if (params.get('search'))      parts.push('Search: ' + params.get('search'));
  if (params.get('category_id')) parts.push('Category ID: ' + params.get('category_id'));
  if (params.get('product_id'))  parts.push('Product ID: ' + params.get('product_id'));
  if (params.get('date_from'))   parts.push('From: ' + params.get('date_from'));
  if (params.get('date_to'))     parts.push('To: ' + params.get('date_to'));
  const qd = params.get('quick_date');
  if (qd && qd !== 'custom') {
    const qdMap = {today:'Today',yesterday:'Yesterday',last7:'Last 7 Days',last30:'Last 30 Days',this_month:'This Month',last_month:'Last Month'};
    parts.push('Period: ' + (qdMap[qd] || qd));
  }
  const container = document.getElementById('rp-filters-content');
  if (parts.length === 0) {
    container.innerHTML = '<span style="color:var(--clr-muted);">No filters applied — showing all data.</span>';
  } else {
    container.innerHTML = parts.map(p => '<span>• ' + p + '</span>').join(' &nbsp;');
  }
});

// ── Print / Download PDF ────────────────────────────────────
function srPrintReport() {
  const params = new URLSearchParams(window.location.search);
  params.delete('tab');
  params.set('autoprint', '1');
  const url = '{{ route("seller.reports.export-pdf") }}?' + params.toString();
  const win = window.open(url, '_blank', 'width=1100,height=850,scrollbars=yes,resizable=yes');
  if (!win || win.closed || typeof win.closed === 'undefined') {
    window.open(url, '_blank');
  }
}

function srDownloadPDF() {
  const params = new URLSearchParams(window.location.search);
  params.delete('tab');
  const url = '{{ route("seller.reports.export-pdf") }}?' + params.toString();
  window.open(url, '_blank');
}
</script>

@endsection
