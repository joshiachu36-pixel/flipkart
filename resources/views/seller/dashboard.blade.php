@extends('layout.seller')

@section('content')
<style>
  /* ── Dashboard Tokens ──────────────────────────────────── */
  :root {
    --clr-navy:       #0f1f3d;
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
    --shadow:         0 2px 12px rgba(0,0,0,0.07);
    --shadow-hover:   0 8px 28px rgba(0,0,0,0.12);
    --transition:     all 0.2s cubic-bezier(0.4,0,0.2,1);
  }

  /* ── Page Header ──────────────────────────────────────── */
  .dash-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 28px; flex-wrap: wrap; gap: 12px;
  }
  .dash-header-left h1 {
    font-size: 1.5rem; font-weight: 700;
    color: var(--clr-slate); letter-spacing: -0.02em; margin: 0;
  }
  .dash-header-left p {
    font-size: 0.82rem; color: var(--clr-muted); margin: 4px 0 0;
  }

  /* ── Stat Cards ───────────────────────────────────────── */
  .stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
  }
  .stat-card {
    background: var(--clr-surface);
    border-radius: var(--radius);
    padding: 22px 20px;
    box-shadow: var(--shadow);
    border: 1px solid var(--clr-border);
    display: flex; align-items: flex-start; gap: 16px;
    transition: var(--transition);
    position: relative; overflow: hidden;
  }
  .stat-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 3px;
  }
  .stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-hover);
  }
  .stat-card.sc-blue::before  { background: var(--clr-blue); }
  .stat-card.sc-green::before { background: #16a34a; }
  .stat-card.sc-amber::before { background: #d97706; }
  .stat-card.sc-red::before   { background: #dc2626; }

  .stat-icon {
    width: 46px; height: 46px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
  }
  .sc-blue  .stat-icon { background: var(--clr-blue-dim);    color: var(--clr-blue); }
  .sc-green .stat-icon { background: rgba(22,163,74,0.10);   color: #16a34a; }
  .sc-amber .stat-icon { background: rgba(217,119,6,0.10);   color: #d97706; }
  .sc-red   .stat-icon { background: rgba(220,38,38,0.10);   color: #dc2626; }

  .stat-body {}
  .stat-val {
    font-size: 2rem; font-weight: 800; line-height: 1.1;
    color: var(--clr-slate); letter-spacing: -0.03em;
  }
  .stat-label {
    font-size: 0.78rem; font-weight: 600;
    color: var(--clr-muted); text-transform: uppercase;
    letter-spacing: 0.5px; margin-top: 3px;
  }
  .stat-sub {
    font-size: 0.72rem; color: #94a3b8; margin-top: 4px;
  }

  /* ── Alert Overrides ──────────────────────────────────── */
  .alert {
    border-radius: var(--radius); border: none;
    font-size: 0.875rem; font-weight: 500;
    display: flex; align-items: center; gap: 10px;
    padding: 14px 18px;
    margin-bottom: 12px;
    box-shadow: var(--shadow);
  }
  .alert-success { background: #f0fdf4; color: #166534; border-left: 4px solid #16a34a; }
  .alert-danger  { background: #fef2f2; color: #991b1b; border-left: 4px solid #dc2626; }
  .alert-info    { background: #eff6ff; color: #1e40af; border-left: 4px solid var(--clr-blue); }
  .alert .btn-close { margin-left: auto; }

  /* ── Quick Links ──────────────────────────────────────── */
  .quick-links {
    background: var(--clr-surface);
    border-radius: var(--radius);
    border: 1px solid var(--clr-border);
    box-shadow: var(--shadow);
    padding: 22px 24px;
  }
  .quick-links h2 {
    font-size: 0.9rem; font-weight: 700;
    color: var(--clr-slate); margin-bottom: 16px;
    padding-bottom: 12px; border-bottom: 1px solid var(--clr-border);
    display: flex; align-items: center; gap: 8px;
  }
  .ql-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 10px;
  }
  .ql-item {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    padding: 16px 10px;
    background: var(--clr-bg);
    border-radius: var(--radius);
    border: 1px solid var(--clr-border);
    text-decoration: none;
    color: var(--clr-text);
    font-size: 0.82rem; font-weight: 500;
    transition: var(--transition);
    text-align: center;
  }
  .ql-item:hover {
    background: var(--clr-blue-dim);
    border-color: rgba(37,99,235,0.25);
    color: var(--clr-blue);
    transform: translateY(-2px);
    box-shadow: var(--shadow);
  }
  .ql-item i { font-size: 1.4rem; }
  .ql-item:hover i { color: var(--clr-blue); }
</style>

{{-- Notification Alerts --}}
@if(!empty($notifications))
  <div style="margin-bottom: 20px;">
    @foreach($notifications as $notif)
      @if($notif['type'] === 'approved')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle-fill"></i>
          <div><strong>Product Approved!</strong> {{ $notif['message'] }}</div>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @elseif($notif['type'] === 'rejected')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-x-circle-fill"></i>
          <div><strong>Product Rejected.</strong> {{ $notif['message'] }}</div>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @else
        <div class="alert alert-info alert-dismissible fade show" role="alert">
          <i class="bi bi-info-circle-fill"></i>
          <div>{{ $notif['message'] }}</div>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
    @endforeach
  </div>
@endif

{{-- Page Header --}}
<div class="dash-header">
  <div class="dash-header-left">
    <h1>Dashboard Overview</h1>
    <p>Welcome back, <strong>{{ auth()->guard('seller')->user()->business_name }}</strong> — here's your store summary.</p>
  </div>
</div>

{{-- Stats Cards --}}
<div class="stat-grid">
  <div class="stat-card sc-blue">
    <div class="stat-icon"><i class="bi bi-box-seam-fill"></i></div>
    <div class="stat-body">
      <div class="stat-val">{{ $stats['total_products'] ?? 0 }}</div>
      <div class="stat-label">Total Products</div>
      <div class="stat-sub">Products you've uploaded</div>
    </div>
  </div>

  <div class="stat-card sc-green">
    <div class="stat-icon"><i class="bi bi-patch-check-fill"></i></div>
    <div class="stat-body">
      <div class="stat-val">{{ $stats['approved_products'] ?? 0 }}</div>
      <div class="stat-label">Approved</div>
      <div class="stat-sub">Visible to customers</div>
    </div>
  </div>

  <div class="stat-card sc-amber">
    <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
    <div class="stat-body">
      <div class="stat-val">{{ $stats['pending_products'] ?? 0 }}</div>
      <div class="stat-label">Pending</div>
      <div class="stat-sub">Awaiting admin approval</div>
    </div>
  </div>

  <div class="stat-card sc-red">
    <div class="stat-icon"><i class="bi bi-x-circle-fill"></i></div>
    <div class="stat-body">
      <div class="stat-val">{{ $stats['rejected_products'] ?? 0 }}</div>
      <div class="stat-label">Rejected</div>
      <div class="stat-sub">Rejected by admin</div>
    </div>
  </div>
</div>

{{-- Quick Links --}}
<div class="quick-links">
  <h2><i class="bi bi-grid-1x2-fill" style="color:var(--clr-blue);"></i> Quick Access</h2>
  <div class="ql-grid">
    <a href="{{ route('seller.products.index') }}" class="ql-item">
      <i class="bi bi-box-seam-fill"></i>
      My Products
    </a>
    <a href="{{ route('seller.products.create') }}" class="ql-item">
      <i class="bi bi-plus-circle-fill"></i>
      Add Product
    </a>
    <a href="{{ route('seller.reports.index') }}" class="ql-item">
      <i class="bi bi-bar-chart-line-fill"></i>
      Analytics
    </a>
  </div>
</div>
@endsection
