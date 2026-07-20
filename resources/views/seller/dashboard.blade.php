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

{{-- Account Status Notifications --}}
@if(!empty($accountNotifications))
  <div style="margin-bottom: 20px;">
    @foreach($accountNotifications as $notif)
      <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="bi bi-info-circle-fill"></i>
        <div>{{ $notif['message'] }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endforeach
  </div>
@endif

@php
  $sellerUser = $seller ?? auth()->guard('seller')->user();
@endphp

{{-- ── 1. PENDING STATUS NOTICE ────────────────────────────────── --}}
@if($sellerUser->isPending())
<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-warning text-dark py-3 px-4 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-hourglass-split fs-4"></i>
            <h5 class="mb-0 fw-bold">Registration Under Review</h5>
        </div>
        <span class="badge bg-dark px-3 py-2 text-uppercase letter-spacing-1">Status: Pending</span>
    </div>
    <div class="card-body p-4 bg-white">
        <div class="row align-items-center g-4">
            <div class="col-md-8">
                <h4 class="fw-bold text-dark mb-2">Welcome, {{ $sellerUser->business_name }}!</h4>
                <p class="text-secondary mb-3">
                    Thank you for submitting your seller registration details. Our marketplace administration team is currently reviewing your account application.
                </p>
                <div class="p-3 bg-light rounded-3 border mb-3">
                    <h6 class="fw-semibold text-dark mb-2"><i class="bi bi-shield-check me-2 text-warning"></i>What happens next?</h6>
                    <ul class="mb-0 text-muted small ps-3">
                        <li>The marketplace admin verifies your business, contact, tax (GST/PAN), and banking details.</li>
                        <li>Once approved, your seller features (product management, orders, reports) will be instantly unlocked.</li>
                        <li>If any document or information requires correction, you will be notified here with details on how to resubmit.</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 text-center border-start ps-md-4">
                <div class="p-3">
                    <i class="bi bi-file-earmark-person text-warning display-4 d-block mb-2"></i>
                    <span class="small text-muted d-block">Submitted on</span>
                    <strong class="text-dark">{{ $sellerUser->created_at->format('M d, Y') }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── 2. REJECTED STATUS NOTICE & RESUBMIT WORKFLOW ──────────── --}}
@elseif($sellerUser->isRejected())
<div class="card shadow border-danger mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-danger text-white py-3 px-4 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-x-circle-fill fs-4"></i>
            <h5 class="mb-0 fw-bold">Seller Registration Rejected</h5>
        </div>
        <span class="badge bg-white text-danger px-3 py-2 text-uppercase fw-bold">Action Required</span>
    </div>
    <div class="card-body p-4 bg-white">
        <div class="alert alert-danger border border-danger-subtle p-3 mb-4" style="border-radius: 8px;">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-danger flex-shrink-0 mt-1"></i>
                <div>
                    <h6 class="fw-bold mb-1">Your seller registration has been rejected by the marketplace admin.</h6>
                    <p class="mb-2 small">Please review the specific rejection reasons listed below, correct your business information, and resubmit your application.</p>
                </div>
            </div>
            <hr class="my-2 border-danger-subtle">
            <div class="bg-white p-3 rounded border border-danger-subtle mt-2">
                <span class="text-uppercase text-danger fw-bold small d-block mb-1"><i class="bi bi-chat-left-text me-1"></i>Admin Rejection Reason:</span>
                <p class="mb-0 fw-semibold text-dark" style="white-space: pre-line;">{{ $sellerUser->rejection_reason ?: 'No specific reason provided.' }}</p>
                @if($sellerUser->rejected_at)
                    <small class="text-muted d-block mt-2"><i class="bi bi-clock me-1"></i>Rejected on {{ $sellerUser->rejected_at->format('M d, Y h:i A') }}</small>
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 p-3 bg-light rounded border">
            <div>
                <h6 class="fw-bold mb-1 text-dark">Ready to fix your application?</h6>
                <p class="mb-0 text-muted small">Click the button to open your application editor with pre-filled details.</p>
            </div>
            <a href="{{ route('seller.resubmit') }}" class="btn btn-danger btn-lg px-4 fw-bold shadow-sm">
                <i class="bi bi-pencil-square me-2"></i>Resubmit Application
            </a>
        </div>
    </div>
</div>

{{-- ── 3. SUSPENDED STATUS NOTICE ──────────────────────────────── --}}
@elseif($sellerUser->isSuspended())
<div class="card shadow border-secondary mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-secondary text-white py-3 px-4 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-slash-circle-fill fs-4"></i>
            <h5 class="mb-0 fw-bold">Seller Account Suspended</h5>
        </div>
        <span class="badge bg-dark px-3 py-2 text-uppercase">Status: Suspended</span>
    </div>
    <div class="card-body p-4 bg-white">
        <div class="alert alert-secondary border p-3 mb-3">
            <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-2 text-secondary"></i>Your seller privileges have been temporarily suspended.</h6>
            <p class="mb-0 small text-muted">You are unable to add new products or manage existing inventory while suspended.</p>
            @if($sellerUser->suspension_reason)
                <div class="bg-white p-3 rounded border mt-3">
                    <strong class="text-secondary small text-uppercase d-block mb-1">Reason for Suspension:</strong>
                    <p class="mb-0 text-dark fw-semibold">{{ $sellerUser->suspension_reason }}</p>
                </div>
            @endif
        </div>
        <p class="text-muted small mb-0">If you believe this is an error or would like to request restoration, please contact marketplace administration support.</p>
    </div>
</div>

{{-- ── 4. APPROVED SELLER DASHBOARD (ORIGINAL DASHBOARD) ────────── --}}
@else

{{-- Product Notification Alerts --}}
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
    <p>Welcome back, <strong>{{ $sellerUser->business_name }}</strong> — here's your store summary.</p>
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

{{-- Rejected Products — action required --}}
@php
  $rejectedProducts = $sellerUser->products()
      ->where('approval_status', 'Rejected')
      ->with('category')
      ->latest()
      ->get();
@endphp

@if($rejectedProducts->isNotEmpty())
<div style="margin-top:24px;">
  <div style="
    background: #fff;
    border-radius: 10px;
    border: 1.5px solid #fca5a5;
    box-shadow: 0 2px 12px rgba(220,38,38,.08);
    overflow: hidden;
  ">
    {{-- Header --}}
    <div style="
      background: linear-gradient(135deg, #7f1d1d, #dc2626);
      padding: 16px 22px;
      display: flex; align-items: center; gap: 10px;
    ">
      <i class="bi bi-exclamation-triangle-fill" style="color:#fbbf24;font-size:1.1rem;"></i>
      <div>
        <p style="font-size:.9rem;font-weight:700;color:#fff;margin:0;">
          Action Required — {{ $rejectedProducts->count() }} Product{{ $rejectedProducts->count() > 1 ? 's' : '' }} Rejected
        </p>
        <p style="font-size:.75rem;color:rgba(255,255,255,.75);margin:2px 0 0;">
          Please review the admin feedback and re-upload your products.
        </p>
      </div>
    </div>

    {{-- List --}}
    <div style="padding: 6px 0;">
      @foreach($rejectedProducts as $rp)
      <div style="
        padding: 16px 22px;
        border-bottom: 1px solid #fee2e2;
        display: flex; align-items: flex-start; gap: 14px;
      ">
        {{-- Thumbnail --}}
        @if($rp->image)
          <img src="{{ asset('storage/'.$rp->image) }}"
               alt="{{ $rp->name }}"
               style="width:50px;height:50px;object-fit:cover;border-radius:7px;border:1px solid #fecaca;flex-shrink:0;">
        @else
          <div style="width:50px;height:50px;background:#fef2f2;border:1px solid #fecaca;border-radius:7px;display:flex;align-items:center;justify-content:center;color:#fca5a5;flex-shrink:0;">
            <i class="bi bi-image" style="font-size:1.2rem;"></i>
          </div>
        @endif

        {{-- Details --}}
        <div style="flex:1;min-width:0;">
          <p style="font-weight:700;color:#1e293b;font-size:.9rem;margin:0 0 4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
            {{ $rp->name }}
          </p>
          @if($rp->category)
            <span style="font-size:.72rem;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:4px;padding:1px 7px;color:#64748b;font-weight:600;">
              {{ $rp->category->name }}
            </span>
          @endif
          @if($rp->rejection_reason)
            <div style="
              margin-top:8px;
              background:#fef2f2; border:1px solid #fecaca;
              border-left:3px solid #dc2626;
              border-radius:5px; padding:8px 12px;
              font-size:.8rem; color:#7f1d1d; line-height:1.55;
            ">
              <strong style="font-size:.7rem;text-transform:uppercase;letter-spacing:.4px;color:#dc2626;">
                <i class="bi bi-chat-left-text-fill me-1"></i>Admin Reason:
              </strong><br>
              {{ $rp->rejection_reason }}
            </div>
          @endif
          @if($rp->rejected_at)
            <p style="font-size:.7rem;color:#94a3b8;margin:6px 0 0;">
              <i class="bi bi-clock me-1"></i>Rejected {{ $rp->rejected_at->diffForHumans() }}
            </p>
          @endif
        </div>

        {{-- Re-upload button --}}
        <div style="flex-shrink:0;">
          <a href="{{ route('seller.products.edit', $rp) }}"
             style="
               display:inline-flex;align-items:center;gap:6px;
               background:#d97706;color:#fff;border:none;
               border-radius:7px;padding:8px 14px;
               font-size:.78rem;font-weight:700;
               text-decoration:none;
               box-shadow:0 2px 6px rgba(217,119,6,.3);
               transition:all .2s;
             "
             onmouseover="this.style.background='#b45309'"
             onmouseout="this.style.background='#d97706'">
            <i class="bi bi-arrow-up-circle-fill"></i> Re-upload
          </a>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endif

@endif

@endsection
