@extends('layout.admin')

@section('content')
<style>
  /* ── Design Tokens ─────────────────────────────────────────────────── */
  :root {
    --clr-navy:    #0f1f3d;
    --clr-blue:    #2563eb;
    --clr-border:  #e2e8f0;
    --clr-bg:      #f1f5f9;
    --clr-surface: #ffffff;
    --clr-muted:   #64748b;
    --clr-slate:   #1e293b;
    --radius:      10px;
    --shadow:      0 2px 12px rgba(0,0,0,0.07);
    --transition:  all .2s cubic-bezier(.4,0,.2,1);
  }

  /* ── Page shell ────────────────────────────────────────────────────── */
  .review-page { max-width: 1200px; margin: 0 auto; padding: 24px 16px; }

  /* ── Breadcrumb ────────────────────────────────────────────────────── */
  .review-breadcrumb { font-size: .83rem; color: var(--clr-muted); margin-bottom: 20px; display: flex; align-items: center; gap: 6px; }
  .review-breadcrumb a { color: var(--clr-blue); text-decoration: none; }
  .review-breadcrumb a:hover { text-decoration: underline; }

  /* ── Page header ───────────────────────────────────────────────────── */
  .review-hdr { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 28px; }
  .review-hdr-title { font-size: 1.4rem; font-weight: 700; color: var(--clr-slate); margin: 0; }
  .review-hdr-sub   { font-size: .83rem; color: var(--clr-muted); margin: 4px 0 0; }

  /* Status badges */
  .badge-pending  { background:#fef9c3; color:#92400e; border:1px solid #fde68a; }
  .badge-approved { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
  .badge-rejected { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
  .status-pill {
    display:inline-flex; align-items:center; gap:6px;
    padding:6px 14px; border-radius:20px;
    font-size:.8rem; font-weight:700; letter-spacing:.3px;
  }

  /* ── Grid ──────────────────────────────────────────────────────────── */
  .review-grid {
    display: grid;
    grid-template-columns: 340px 1fr;
    gap: 24px;
    align-items: start;
  }
  @media(max-width:860px){ .review-grid { grid-template-columns: 1fr; } }

  /* ── Card base ─────────────────────────────────────────────────────── */
  .rv-card {
    background: var(--clr-surface);
    border-radius: var(--radius);
    border: 1px solid var(--clr-border);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 20px;
  }
  .rv-card-hdr {
    padding: 14px 20px;
    border-bottom: 1px solid var(--clr-border);
    background: var(--clr-bg);
    font-size: .82rem; font-weight: 700;
    color: var(--clr-slate);
    display: flex; align-items: center; gap: 8px;
  }
  .rv-card-hdr i { color: var(--clr-blue); }
  .rv-card-body { padding: 20px; }

  /* ── Main image ────────────────────────────────────────────────────── */
  .main-img {
    width: 100%; aspect-ratio: 1/1; object-fit: cover;
    border-radius: 8px; border: 1px solid var(--clr-border);
    display: block;
  }
  .no-img-placeholder {
    width: 100%; aspect-ratio: 1/1;
    background: var(--clr-bg);
    border: 2px dashed #cbd5e1;
    border-radius: 8px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    color: #94a3b8; font-size: .85rem; gap: 8px;
  }
  .no-img-placeholder i { font-size: 2.5rem; }

  /* ── Seller card ───────────────────────────────────────────────────── */
  .seller-logo {
    width: 56px; height: 56px; border-radius: 8px;
    object-fit: cover; border: 1px solid var(--clr-border);
  }
  .seller-logo-placeholder {
    width: 56px; height: 56px; border-radius: 8px;
    background: var(--clr-bg); border: 1px solid var(--clr-border);
    display: flex; align-items: center; justify-content: center;
    color: #94a3b8; font-size: 1.4rem;
  }

  /* ── Detail rows ───────────────────────────────────────────────────── */
  .detail-row {
    display: flex; align-items: baseline; gap: 10px;
    padding: 9px 0;
    border-bottom: 1px solid var(--clr-border);
    font-size: .86rem;
  }
  .detail-row:last-child { border-bottom: none; }
  .detail-label {
    min-width: 130px; flex-shrink: 0;
    font-weight: 600; color: var(--clr-muted);
    font-size: .78rem; text-transform: uppercase; letter-spacing: .4px;
  }
  .detail-val { color: var(--clr-slate); flex: 1; }

  /* ── Description box ───────────────────────────────────────────────── */
  .desc-box {
    background: var(--clr-bg);
    border: 1px solid var(--clr-border);
    border-radius: 8px;
    padding: 14px 16px;
    font-size: .88rem;
    color: var(--clr-slate);
    line-height: 1.65;
    white-space: pre-wrap;
    word-break: break-word;
  }

  /* ── Variants table ────────────────────────────────────────────────── */
  .var-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
  .var-table thead th {
    background: var(--clr-bg); color: var(--clr-muted);
    font-size: .72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .5px;
    padding: 9px 12px;
    border-bottom: 1px solid var(--clr-border);
  }
  .var-table tbody td {
    padding: 11px 12px;
    border-bottom: 1px solid var(--clr-border);
    vertical-align: middle;
    color: var(--clr-slate);
  }
  .var-table tbody tr:last-child td { border-bottom: none; }
  .var-table tbody tr:hover { background: #f8fafc; }

  .var-img {
    width: 40px; height: 40px; border-radius: 6px;
    object-fit: cover; border: 1px solid var(--clr-border);
  }
  .color-dot {
    display: inline-block; width: 14px; height: 14px;
    border-radius: 50%; border: 2px solid #fff;
    box-shadow: 0 0 0 1px #cbd5e1; margin-right: 6px;
    vertical-align: middle;
  }
  .size-chip {
    display: inline-block;
    background: var(--clr-bg); border: 1px solid var(--clr-border);
    border-radius: 4px; padding: 2px 8px;
    font-size: .72rem; font-weight: 600; margin: 2px 2px 2px 0;
  }

  /* ── Timeline ──────────────────────────────────────────────────────── */
  .timeline { list-style: none; padding: 0; margin: 0; position: relative; }
  .timeline::before {
    content:''; position:absolute; left:15px; top:0; bottom:0;
    width:2px; background: var(--clr-border);
  }
  .timeline-item { display: flex; gap: 14px; padding-bottom: 20px; position: relative; }
  .timeline-item:last-child { padding-bottom: 0; }
  .timeline-dot {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; flex-shrink: 0; z-index: 1;
    border: 3px solid var(--clr-surface);
  }
  .tl-pending  { background: #fef3c7; color: #d97706; }
  .tl-approved { background: #dcfce7; color: #16a34a; }
  .tl-rejected { background: #fee2e2; color: #dc2626; }
  .tl-neutral  { background: #f1f5f9; color: #64748b; }

  .timeline-content { flex: 1; }
  .timeline-title { font-size: .85rem; font-weight: 700; color: var(--clr-slate); margin: 0 0 2px; }
  .timeline-sub   { font-size: .76rem; color: var(--clr-muted); }
  .timeline-reason {
    margin-top: 6px; font-size: .8rem;
    background: #fef2f2; color: #991b1b;
    border-left: 3px solid #dc2626;
    border-radius: 4px; padding: 6px 10px;
    white-space: pre-wrap; word-break: break-word;
  }

  /* ── Action panel ──────────────────────────────────────────────────── */
  .action-panel {
    background: var(--clr-surface);
    border-radius: var(--radius);
    border: 1px solid var(--clr-border);
    box-shadow: var(--shadow);
    padding: 22px 24px;
    margin-bottom: 20px;
  }
  .action-panel-title { font-size: .85rem; font-weight: 700; color: var(--clr-slate); margin: 0 0 14px; display:flex; align-items:center; gap:8px; }

  .btn-approve {
    display: inline-flex; align-items: center; gap: 8px;
    background: #16a34a; color: #fff; border: none;
    border-radius: 8px; padding: 12px 28px;
    font-size: .9rem; font-weight: 700;
    cursor: pointer; text-decoration: none;
    transition: var(--transition);
    box-shadow: 0 2px 8px rgba(22,163,74,.3);
  }
  .btn-approve:hover { background:#15803d; transform:translateY(-1px); box-shadow:0 4px 14px rgba(22,163,74,.4); color:#fff; }

  .btn-reject {
    display: inline-flex; align-items: center; gap: 8px;
    background: #dc2626; color: #fff; border: none;
    border-radius: 8px; padding: 12px 28px;
    font-size: .9rem; font-weight: 700;
    cursor: pointer; text-decoration: none;
    transition: var(--transition);
    box-shadow: 0 2px 8px rgba(220,38,38,.3);
  }
  .btn-reject:hover { background:#b91c1c; transform:translateY(-1px); box-shadow:0 4px 14px rgba(220,38,38,.4); color:#fff; }

  .locked-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: #dcfce7; color: #166534;
    border: 1px solid #bbf7d0;
    border-radius: 8px; padding: 12px 22px;
    font-size: .88rem; font-weight: 700;
  }

  .rejected-notice {
    background: #fef2f2; border: 1px solid #fecaca;
    border-radius: 8px; padding: 14px 18px;
    color: #991b1b; font-size: .86rem;
    display: flex; align-items: flex-start; gap: 10px;
  }
  .rejected-notice i { font-size: 1.1rem; flex-shrink: 0; margin-top: 2px; }

  /* ── Reject Modal ──────────────────────────────────────────────────── */
  #rejectModal .modal-content { border-radius: 14px; border: none; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,.2); }
  .modal-hdr-danger { background: linear-gradient(135deg, #7f1d1d, #dc2626); color: #fff; padding: 20px 24px; border-bottom: none; }
  .modal-hdr-danger .modal-title { font-size: 1rem; font-weight: 700; display: flex; align-items: center; gap: 8px; }
  .modal-body-pro { padding: 24px; }
  .modal-footer-pro { background: var(--clr-bg); border-top: 1px solid var(--clr-border); padding: 14px 24px; display: flex; gap: 10px; justify-content: flex-end; }

  .reject-reason-textarea {
    width: 100%; min-height: 130px;
    border: 2px solid var(--clr-border);
    border-radius: 8px; padding: 12px 14px;
    font-size: .88rem; color: var(--clr-slate);
    resize: vertical; transition: var(--transition);
    font-family: inherit;
  }
  .reject-reason-textarea:focus { outline: none; border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220,38,38,.12); }

  .quick-chip {
    display: inline-block;
    background: var(--clr-bg); border: 1px solid var(--clr-border);
    border-radius: 20px; padding: 4px 12px;
    font-size: .75rem; font-weight: 500; color: var(--clr-muted);
    cursor: pointer; transition: var(--transition);
    margin: 3px 2px;
  }
  .quick-chip:hover { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }

  .char-counter { font-size: .75rem; color: var(--clr-muted); text-align: right; margin-top: 4px; }
  .char-counter.warn { color: #d97706; }
  .char-counter.limit { color: #dc2626; }
</style>

<div class="review-page">

  {{-- Breadcrumb --}}
  <div class="review-breadcrumb">
    <a href="{{ route('admin.products.index') }}"><i class="bi bi-grid-fill"></i> Product Approvals</a>
    <i class="bi bi-chevron-right"></i>
    <span>Review Product</span>
  </div>

  {{-- Flash --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Page Header --}}
  <div class="review-hdr">
    <div>
      <h1 class="review-hdr-title">
        <i class="bi bi-patch-check-fill text-primary me-1"></i>
        Product Review
      </h1>
      <p class="review-hdr-sub">Inspect all product details before making your decision.</p>
    </div>
    @php
      $statusClass = match($product->approval_status) {
        'Approved' => 'badge-approved',
        'Rejected' => 'badge-rejected',
        default    => 'badge-pending',
      };
      $statusIcon = match($product->approval_status) {
        'Approved' => 'bi-check-circle-fill',
        'Rejected' => 'bi-x-circle-fill',
        default    => 'bi-hourglass-split',
      };
    @endphp
    <span class="status-pill {{ $statusClass }}">
      <i class="bi {{ $statusIcon }}"></i>
      {{ $product->approval_status }}
    </span>
  </div>

  {{-- ═══════════════════════════════════════════════
        ACTION PANEL — shown ABOVE the review grid
  ═══════════════════════════════════════════════════ --}}
  <div class="action-panel">
    <p class="action-panel-title"><i class="bi bi-shield-check text-primary"></i> Admin Decision</p>

    @if($product->canBeReviewed())
      {{-- PENDING → show Approve + Reject buttons --}}
      <p style="font-size:.85rem;color:var(--clr-muted);margin:0 0 16px;">
        Review all product information below, then choose to approve or reject.
      </p>
      <div class="d-flex flex-wrap gap-3">
        {{-- Approve form --}}
        <form action="{{ route('admin.products.approve', $product) }}" method="POST" id="approveForm">
          @csrf
          <button type="submit" class="btn-approve" id="approveBtn"
                  onclick="return confirm('Are you sure you want to APPROVE this product? This action is final and cannot be reversed.')">
            <i class="bi bi-check-circle-fill"></i> Approve Product
          </button>
        </form>

        {{-- Reject trigger --}}
        <button type="button" class="btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal">
          <i class="bi bi-x-circle-fill"></i> Reject Product
        </button>
      </div>

    @elseif($product->isApprovalLocked())
      {{-- APPROVED → permanently locked --}}
      <div class="locked-badge">
        <i class="bi bi-lock-fill"></i>
        Approved Successfully — Read Only
      </div>
      <p style="font-size:.8rem;color:var(--clr-muted);margin:10px 0 0;">
        This product has been approved and is live on the shop. The approval is final and cannot be changed.
      </p>

    @else
      {{-- REJECTED → waiting for seller to re-upload --}}
      <div class="rejected-notice">
        <i class="bi bi-info-circle-fill"></i>
        <div>
          <strong>Awaiting Seller Resubmission</strong><br>
          This product was rejected. The seller must update and re-submit it before you can review it again.
          @if($product->rejection_reason)
            <div style="margin-top:8px;padding:10px 12px;background:#fff;border:1px solid #fecaca;border-radius:6px;">
              <strong style="font-size:.75rem;text-transform:uppercase;letter-spacing:.4px;color:#dc2626;">Rejection Reason Given:</strong><br>
              <span style="font-size:.86rem;color:#7f1d1d;">{{ $product->rejection_reason }}</span>
            </div>
          @endif
        </div>
      </div>
    @endif
  </div>

  {{-- ═══════════════════════════════════════════════
        MAIN REVIEW GRID
  ═══════════════════════════════════════════════════ --}}
  <div class="review-grid">

    {{-- ── LEFT COLUMN ─── --}}
    <div>

      {{-- Product Image --}}
      <div class="rv-card">
        <div class="rv-card-hdr"><i class="bi bi-image-fill"></i> Product Image</div>
        <div class="rv-card-body">
          @if($product->image)
            <img src="{{ Str::startsWith($product->image, 'products/') ? asset('storage/'.$product->image) : asset('uploads/'.$product->image) }}"
                 alt="{{ $product->name }}" class="main-img">
          @else
            <div class="no-img-placeholder">
              <i class="bi bi-image"></i>
              <span>No image provided</span>
            </div>
          @endif
        </div>
      </div>

      {{-- Seller Information --}}
      <div class="rv-card">
        <div class="rv-card-hdr"><i class="bi bi-shop-window"></i> Seller Information</div>
        <div class="rv-card-body">
          @if($product->seller)
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;padding-bottom:14px;border-bottom:1px solid var(--clr-border);">
              @if($product->seller->business_logo)
                <img src="{{ asset('storage/'.$product->seller->business_logo) }}"
                     alt="{{ $product->seller->business_name }}" class="seller-logo">
              @else
                <div class="seller-logo-placeholder"><i class="bi bi-shop"></i></div>
              @endif
              <div>
                <div style="font-weight:700;color:var(--clr-slate);font-size:.95rem;">{{ $product->seller->business_name }}</div>
                <div style="font-size:.78rem;color:var(--clr-muted);">{{ $product->seller->owner_name }}</div>
              </div>
            </div>
            <div class="detail-row">
              <span class="detail-label">Email</span>
              <span class="detail-val">{{ $product->seller->email }}</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Phone</span>
              <span class="detail-val">{{ $product->seller->phone ?? '—' }}</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">GST No.</span>
              <span class="detail-val">{{ $product->seller->gst_number ?? '—' }}</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Business Addr.</span>
              <span class="detail-val">{{ $product->seller->business_address ?? '—' }}</span>
            </div>
          @else
            <p class="text-muted mb-0" style="font-size:.85rem;">No seller information available.</p>
          @endif
        </div>
      </div>

      {{-- Approval Timeline --}}
      <div class="rv-card">
        <div class="rv-card-hdr"><i class="bi bi-clock-history"></i> Review Timeline</div>
        <div class="rv-card-body">
          <ul class="timeline">

            {{-- Submission --}}
            <li class="timeline-item">
              <div class="timeline-dot tl-neutral"><i class="bi bi-plus-circle-fill"></i></div>
              <div class="timeline-content">
                <p class="timeline-title">Product Submitted</p>
                <p class="timeline-sub">{{ $product->created_at->format('d M Y, h:i A') }}</p>
              </div>
            </li>

            {{-- History entries --}}
            @foreach($product->approvalHistories as $history)
              @php
                $tlClass = match($history->action) {
                  'Approved' => 'tl-approved',
                  'Rejected' => 'tl-rejected',
                  default    => 'tl-pending',
                };
                $tlIcon = match($history->action) {
                  'Approved' => 'bi-check-circle-fill',
                  'Rejected' => 'bi-x-circle-fill',
                  default    => 'bi-hourglass-split',
                };
              @endphp
              <li class="timeline-item">
                <div class="timeline-dot {{ $tlClass }}"><i class="bi {{ $tlIcon }}"></i></div>
                <div class="timeline-content">
                  <p class="timeline-title">{{ $history->action }}</p>
                  <p class="timeline-sub">{{ $history->acted_at->format('d M Y, h:i A') }} · by {{ $history->actor_name ?? 'Admin' }}</p>
                  @if($history->reason)
                    <div class="timeline-reason">{{ $history->reason }}</div>
                  @endif
                </div>
              </li>
            @endforeach

            {{-- Current state if no history yet --}}
            @if($product->approvalHistories->isEmpty())
              <li class="timeline-item">
                <div class="timeline-dot tl-pending"><i class="bi bi-hourglass-split"></i></div>
                <div class="timeline-content">
                  <p class="timeline-title">Awaiting Admin Review</p>
                  <p class="timeline-sub">No decision has been made yet.</p>
                </div>
              </li>
            @endif

          </ul>
        </div>
      </div>

    </div>{{-- /LEFT COLUMN --}}

    {{-- ── RIGHT COLUMN ─── --}}
    <div>

      {{-- Product Details --}}
      <div class="rv-card">
        <div class="rv-card-hdr"><i class="bi bi-info-circle-fill"></i> Product Details</div>
        <div class="rv-card-body">
          <div style="margin-bottom:20px;">
            <p style="font-size:1.4rem;font-weight:800;color:var(--clr-slate);margin:0 0 4px;">{{ $product->name }}</p>
            <p style="font-size:.8rem;color:var(--clr-muted);margin:0;">Submitted on {{ $product->created_at->format('d M Y') }}</p>
          </div>

          <div class="detail-row">
            <span class="detail-label">Category</span>
            <span class="detail-val">{{ $product->category->name ?? '—' }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Brand</span>
            <span class="detail-val">{{ $product->brand->name ?? ($product->seller->business_name ?? '—') }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Selling Price</span>
            <span class="detail-val" style="font-weight:700;color:#16a34a;font-size:1rem;">₹{{ number_format($product->price, 2) }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Original Price</span>
            <span class="detail-val">
              @if($product->original_price)
                <s style="color:var(--clr-muted);">₹{{ number_format($product->original_price, 2) }}</s>
                @if($product->discount_percentage > 0)
                  <span style="background:#dcfce7;color:#16a34a;border-radius:4px;padding:1px 7px;font-size:.75rem;font-weight:700;margin-left:4px;">
                    {{ $product->discount_percentage }}% OFF
                  </span>
                @endif
              @else
                —
              @endif
            </span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Stock</span>
            <span class="detail-val">
              @if($product->stock > 10)
                <span style="color:#16a34a;font-weight:600;">{{ $product->stock }} units</span>
              @elseif($product->stock > 0)
                <span style="color:#d97706;font-weight:600;">{{ $product->stock }} units (low stock)</span>
              @else
                <span style="color:#dc2626;font-weight:600;">Out of stock</span>
              @endif
            </span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Approval Status</span>
            <span class="detail-val">
              <span class="status-pill {{ $statusClass }}" style="padding:4px 10px;">
                <i class="bi {{ $statusIcon }}"></i> {{ $product->approval_status }}
              </span>
            </span>
          </div>
          @if($product->approved_at)
            <div class="detail-row">
              <span class="detail-label">Approved At</span>
              <span class="detail-val">{{ $product->approved_at->format('d M Y, h:i A') }}</span>
            </div>
          @endif
          @if($product->rejected_at)
            <div class="detail-row">
              <span class="detail-label">Rejected At</span>
              <span class="detail-val">{{ $product->rejected_at->format('d M Y, h:i A') }}</span>
            </div>
          @endif
        </div>
      </div>

      {{-- Description --}}
      <div class="rv-card">
        <div class="rv-card-hdr"><i class="bi bi-card-text"></i> Product Description</div>
        <div class="rv-card-body">
          <div class="desc-box">{{ $product->description }}</div>
        </div>
      </div>

      {{-- Rejection Reason (if applicable) --}}
      @if($product->approval_status === 'Rejected' && $product->rejection_reason)
        <div class="rv-card" style="border-color:#fecaca;">
          <div class="rv-card-hdr" style="background:#fef2f2;color:#991b1b;">
            <i class="bi bi-x-circle-fill" style="color:#dc2626;"></i> Current Rejection Reason
          </div>
          <div class="rv-card-body">
            <div style="background:#fff5f5;border:1px solid #fecaca;border-radius:8px;padding:14px 16px;color:#7f1d1d;font-size:.88rem;line-height:1.65;">
              {{ $product->rejection_reason }}
            </div>
          </div>
        </div>
      @endif

      {{-- Variants --}}
      <div class="rv-card">
        <div class="rv-card-hdr">
          <i class="bi bi-layers-fill"></i> Product Variants
          <span style="margin-left:auto;background:rgba(37,99,235,.1);color:var(--clr-blue);border-radius:20px;padding:2px 10px;font-size:.72rem;font-weight:700;">
            {{ $product->variants->count() }} variant{{ $product->variants->count() !== 1 ? 's' : '' }}
          </span>
        </div>
        <div class="rv-card-body" style="padding:0;">
          @if($product->variants->isEmpty())
            <div style="padding:30px;text-align:center;color:var(--clr-muted);">
              <i class="bi bi-layers" style="font-size:2rem;display:block;margin-bottom:8px;color:#cbd5e1;"></i>
              No variants configured yet.
            </div>
          @else
            <div style="overflow-x:auto;">
              <table class="var-table">
                <thead>
                  <tr>
                    <th>Image</th>
                    <th>Color</th>
                    <th>Sizes & Prices</th>
                    <th>Stock</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($product->variants as $variant)
                    <tr>
                      <td>
                        @if($variant->image)
                          <img src="{{ asset('storage/'.$variant->image) }}" alt="Variant" class="var-img">
                        @else
                          <div style="width:40px;height:40px;background:var(--clr-bg);border:1px solid var(--clr-border);border-radius:6px;display:flex;align-items:center;justify-content:center;color:#94a3b8;">
                            <i class="bi bi-image"></i>
                          </div>
                        @endif
                      </td>
                      <td>
                        @if($variant->color)
                          <span class="color-dot" style="background:{{ $variant->color->hex ?? '#ccc' }};"></span>
                          {{ $variant->color->name }}
                        @else
                          <span style="color:var(--clr-muted);">—</span>
                        @endif
                      </td>
                      <td>
                        @if($variant->sizes->isEmpty())
                          <span style="color:var(--clr-muted);font-size:.8rem;">No sizes</span>
                        @else
                          @foreach($variant->sizes as $size)
                            <span class="size-chip">{{ $size->name }}
                              @if($size->pivot->price)
                                · ₹{{ number_format($size->pivot->price, 0) }}
                              @endif
                            </span>
                          @endforeach
                        @endif
                      </td>
                      <td>
                        @if($variant->stock > 0)
                          <span style="font-weight:600;color:var(--clr-slate);">{{ $variant->stock }}</span>
                        @else
                          <span style="color:#dc2626;font-weight:600;">0</span>
                        @endif
                      </td>
                      <td>
                        @if($variant->status)
                          <span style="background:#dcfce7;color:#16a34a;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">Active</span>
                        @else
                          <span style="background:#fee2e2;color:#991b1b;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">Inactive</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>

      {{-- Back button --}}
      <div style="text-align:right;margin-top:8px;">
        <a href="{{ route('admin.products.index', ['status' => $product->approval_status]) }}"
           class="btn btn-outline-secondary btn-sm">
          <i class="bi bi-arrow-left me-1"></i> Back to Product List
        </a>
      </div>

    </div>{{-- /RIGHT COLUMN --}}
  </div>{{-- /review-grid --}}

</div>{{-- /review-page --}}


{{-- ════════════════════════════════════════════════════
      REJECT MODAL
════════════════════════════════════════════════════════ --}}
@if($product->canBeReviewed())
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
    <div class="modal-content">

      {{-- Header --}}
      <div class="modal-hdr-danger">
        <div class="modal-title" id="rejectModalLabel">
          <i class="bi bi-x-circle-fill"></i> Reject Product
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
      </div>

      {{-- Body --}}
      <form action="{{ route('admin.products.reject', $product) }}" method="POST" id="rejectForm">
        @csrf
        <div class="modal-body-pro">

          <p style="font-size:.875rem;color:var(--clr-slate);margin:0 0 16px;">
            You are about to reject <strong>{{ $product->name }}</strong>.
            The seller will see this reason and must address it before resubmitting.
          </p>

          {{-- Quick Chips --}}
          <div style="margin-bottom:12px;">
            <p style="font-size:.75rem;font-weight:700;color:var(--clr-muted);text-transform:uppercase;letter-spacing:.5px;margin:0 0 8px;">
              <i class="bi bi-lightning-fill text-warning me-1"></i> Quick Reasons
            </p>
            <div>
              <span class="quick-chip" onclick="appendReason('Product images are blurry or low quality. Please upload high-resolution images.')">📷 Blurry images</span>
              <span class="quick-chip" onclick="appendReason('Brand name is incorrect or missing. Please verify and correct the brand information.')">🏷️ Incorrect brand</span>
              <span class="quick-chip" onclick="appendReason('This appears to be a duplicate product. Please check existing listings before resubmitting.')">📋 Duplicate product</span>
              <span class="quick-chip" onclick="appendReason('Product is placed in an incorrect category. Please select the most appropriate category.')">📂 Wrong category</span>
              <span class="quick-chip" onclick="appendReason('The pricing appears to be invalid or unrealistic. Please review and correct the product price.')">💰 Invalid pricing</span>
              <span class="quick-chip" onclick="appendReason('Product description is insufficient or misleading. Please provide a detailed and accurate description.')">📝 Poor description</span>
              <span class="quick-chip" onclick="appendReason('Required product information is missing. Please complete all mandatory fields before resubmitting.')">⚠️ Missing information</span>
              <span class="quick-chip" onclick="appendReason('Product images do not match the product description. Please upload correct images.')">🖼️ Image mismatch</span>
            </div>
          </div>

          {{-- Textarea --}}
          <div>
            <label for="rejection_reason" style="font-size:.82rem;font-weight:700;color:var(--clr-slate);margin-bottom:6px;display:block;">
              Rejection Reason <span style="color:#dc2626;">*</span>
            </label>
            <textarea id="rejection_reason"
                      name="rejection_reason"
                      class="reject-reason-textarea"
                      placeholder="Describe clearly why this product is being rejected and what the seller needs to fix…"
                      maxlength="1000"
                      oninput="updateRejectBtn()">{{ old('rejection_reason') }}</textarea>
            <div class="char-counter" id="charCounter">0 / 1000 characters</div>
          </div>

          @error('rejection_reason')
            <div style="background:#fee2e2;color:#991b1b;border-radius:6px;padding:8px 12px;font-size:.82rem;margin-top:8px;">
              <i class="bi bi-exclamation-circle-fill me-1"></i>{{ $message }}
            </div>
          @enderror

        </div>

        {{-- Footer --}}
        <div class="modal-footer-pro">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
            <i class="bi bi-x-lg me-1"></i> Cancel
          </button>
          <button type="submit" id="rejectSubmitBtn" class="btn btn-danger btn-sm" disabled
                  style="display:inline-flex;align-items:center;gap:6px;padding:9px 20px;font-weight:700;">
            <i class="bi bi-x-circle-fill"></i> Confirm Rejection
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

{{-- Auto-open modal if validation failed --}}
@if($errors->has('rejection_reason'))
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      new bootstrap.Modal(document.getElementById('rejectModal')).show();
    });
  </script>
@endif
@endif

<script>
  function updateRejectBtn() {
    const ta   = document.getElementById('rejection_reason');
    const btn  = document.getElementById('rejectSubmitBtn');
    const ctr  = document.getElementById('charCounter');

    if (!ta || !btn || !ctr) return;

    const len = ta.value.trim().length;
    const max = 1000;

    ctr.textContent = len + ' / ' + max + ' characters';
    ctr.className   = 'char-counter' + (len > 900 ? ' limit' : len > 700 ? ' warn' : '');

    btn.disabled = len < 10;
  }

  function appendReason(text) {
    const ta = document.getElementById('rejection_reason');
    if (!ta) return;
    if (ta.value.trim()) {
      ta.value = ta.value.trim() + '\n' + text;
    } else {
      ta.value = text;
    }
    updateRejectBtn();
    ta.focus();
  }

  // Initialise counter on page load
  document.addEventListener('DOMContentLoaded', updateRejectBtn);
</script>
@endsection
