@extends('layout.seller')

@section('content')
<style>
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
    --radius-sm:      6px;
    --shadow:         0 2px 12px rgba(0,0,0,0.07);
    --shadow-hover:   0 8px 28px rgba(0,0,0,0.12);
    --transition:     all 0.2s cubic-bezier(0.4,0,0.2,1);
  }

  /* ── Page Header ──────────────────────────────────── */
  .page-hdr {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
  }
  .page-hdr-left h1 {
    font-size: 1.4rem; font-weight: 700; color: var(--clr-slate);
    letter-spacing: -0.02em; margin: 0;
    display: flex; align-items: center; gap: 10px;
  }
  .page-hdr-left h1 i { color: var(--clr-blue); }
  .page-hdr-left p { font-size: 0.82rem; color: var(--clr-muted); margin: 5px 0 0; }

  .btn-pro-primary {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--clr-blue);
    color: #fff; border: none; border-radius: var(--radius-sm);
    padding: 10px 20px; font-size: 0.875rem; font-weight: 600;
    cursor: pointer; text-decoration: none;
    transition: var(--transition);
    box-shadow: 0 2px 8px rgba(37,99,235,0.3);
  }
  .btn-pro-primary:hover {
    background: var(--clr-blue-light);
    color: #fff; transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(37,99,235,0.4);
  }

  /* ── Alert ──────────────────────────────────────── */
  .alert-pro {
    border-radius: var(--radius); border: none;
    font-size: 0.875rem; font-weight: 500;
    display: flex; align-items: center; gap: 10px;
    padding: 13px 18px; margin-bottom: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  }
  .alert-pro.success { background: #f0fdf4; color: #166534; border-left: 4px solid #16a34a; }
  .alert-pro.danger  { background: #fef2f2; color: #991b1b; border-left: 4px solid #dc2626; }
  .alert-pro .btn-close { margin-left: auto; }

  /* ── Table Card ──────────────────────────────────── */
  .table-card {
    background: var(--clr-surface);
    border-radius: var(--radius);
    border: 1px solid var(--clr-border);
    box-shadow: var(--shadow);
    overflow: hidden;
  }
  .table-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid var(--clr-border);
    display: flex; align-items: center; gap: 10px;
    background: linear-gradient(to right, var(--clr-navy), var(--clr-navy-mid, #162447));
  }
  .table-card-header h2 {
    font-size: 0.9rem; font-weight: 600; color: #f1f5f9; margin: 0;
    display: flex; align-items: center; gap: 8px;
  }
  .table-card-header .count-badge {
    background: rgba(255,255,255,0.12); color: #cbd5e1;
    border-radius: 20px; padding: 2px 10px; font-size: 0.72rem; font-weight: 600;
    margin-left: auto;
  }

  /* ── Table ───────────────────────────────────────── */
  .pro-table { width: 100%; border-collapse: collapse; font-size: 0.84rem; }
  .pro-table thead th {
    background: var(--clr-bg);
    color: var(--clr-muted);
    font-size: 0.71rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.6px;
    padding: 11px 16px;
    border-bottom: 1px solid var(--clr-border);
    white-space: nowrap;
  }
  .pro-table tbody td {
    padding: 13px 16px;
    border-bottom: 1px solid var(--clr-border);
    color: var(--clr-text); vertical-align: middle;
  }
  .pro-table tbody tr:last-child td { border-bottom: none; }
  .pro-table tbody tr:hover { background: #f8fafc; }

  /* Product thumbnail */
  .prod-thumb {
    width: 44px; height: 44px;
    border-radius: var(--radius-sm);
    object-fit: cover;
    border: 1px solid var(--clr-border);
  }
  .prod-no-img {
    width: 44px; height: 44px;
    border-radius: var(--radius-sm);
    background: var(--clr-bg);
    border: 1px solid var(--clr-border);
    display: flex; align-items: center; justify-content: center;
    color: #94a3b8; font-size: 1rem;
  }
  .prod-name { font-weight: 600; color: var(--clr-slate); }

  /* Status badge */
  .status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    border-radius: 20px; padding: 3px 11px;
    font-size: 0.72rem; font-weight: 700;
  }
  .status-badge.approved { background: #dcfce7; color: #166534; }
  .status-badge.pending  { background: #fef9c3; color: #92400e; }
  .status-badge.rejected { background: #fee2e2; color: #991b1b; }

  /* Action buttons */
  .action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--clr-border);
    background: var(--clr-surface);
    color: var(--clr-muted);
    font-size: 0.85rem; cursor: pointer;
    text-decoration: none;
    transition: var(--transition);
  }
  .action-btn:hover { transform: translateY(-1px); box-shadow: var(--shadow); }
  .action-btn.edit:hover  { background: var(--clr-blue-dim); color: var(--clr-blue); border-color: rgba(37,99,235,0.25); }
  .action-btn.vars:hover  { background: rgba(100,116,139,0.1); color: var(--clr-slate); }
  .action-btn.del:hover   { background: #fee2e2; color: #dc2626; border-color: rgba(220,38,38,0.25); }

  /* Empty state */
  .empty-state {
    text-align: center; padding: 60px 20px;
    color: var(--clr-muted);
  }
  .empty-state i { font-size: 2.5rem; margin-bottom: 12px; display: block; color: #cbd5e1; }
  .empty-state p { font-size: 0.9rem; margin: 0; }
  .empty-state small { font-size: 0.78rem; color: #94a3b8; margin-top: 4px; display: block; }

  /* ── Delete Modal ──────────────────────────────────── */
  .modal-content { border-radius: 12px; border: none; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.18); }
  .modal-header-danger {
    background: linear-gradient(135deg, #7f1d1d, #dc2626);
    color: #fff; padding: 18px 22px; border-bottom: none;
  }
  .modal-header-danger .modal-title { font-size: 0.95rem; font-weight: 700; display: flex; align-items: center; gap: 8px; }
  .modal-body-pro { padding: 22px; }
  .modal-footer-pro { background: var(--clr-bg); border-top: 1px solid var(--clr-border); padding: 14px 22px; display: flex; gap: 8px; justify-content: flex-end; }

  .btn-cancel {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border-radius: var(--radius-sm);
    background: var(--clr-surface); border: 1px solid var(--clr-border);
    color: var(--clr-text); font-size: 0.85rem; font-weight: 500;
    cursor: pointer; text-decoration: none; transition: var(--transition);
  }
  .btn-cancel:hover { background: var(--clr-bg); color: var(--clr-slate); }

  .btn-delete-confirm {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border-radius: var(--radius-sm);
    background: #dc2626; border: none;
    color: #fff; font-size: 0.85rem; font-weight: 600;
    cursor: pointer; transition: var(--transition);
  }
  .btn-delete-confirm:hover { background: #b91c1c; transform: translateY(-1px); }
</style>

{{-- Alerts --}}
@if(session('success'))
  <div class="alert-pro success alert-dismissible fade show d-flex" role="alert">
    <i class="bi bi-check-circle-fill"></i>
    <span>{{ session('success') }}</span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
  </div>
@endif
@if(session('error'))
  <div class="alert-pro danger alert-dismissible fade show d-flex" role="alert">
    <i class="bi bi-x-circle-fill"></i>
    <span>{{ session('error') }}</span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
  </div>
@endif

{{-- Page Header --}}
<div class="page-hdr">
  <div class="page-hdr-left">
    <h1><i class="bi bi-box-seam-fill"></i> My Products</h1>
    <p>Manage your product listings and inventory</p>
  </div>
  <a href="{{ route('seller.products.create') }}" class="btn-pro-primary">
    <i class="bi bi-plus-lg"></i> Add New Product
  </a>
</div>

{{-- Products Table --}}
<div class="table-card">
  <div class="table-card-header">
    <h2><i class="bi bi-list-ul"></i> Product Listings</h2>
    <span class="count-badge">{{ $products->total() ?? $products->count() }} products</span>
  </div>
  <div style="overflow-x: auto;">
    <table class="pro-table">
      <thead>
        <tr>
          <th style="width:60px;">Image</th>
          <th>Product Name</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Category</th>
          <th>Status</th>
          <th style="text-align:right; padding-right:22px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $product)
        <tr>
          <td>
            @if($product->image)
              <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="prod-thumb">
            @else
              <div class="prod-no-img"><i class="bi bi-image"></i></div>
            @endif
          </td>
          <td>
            <span class="prod-name">{{ $product->name }}</span>
          </td>
          <td style="font-weight:600; color:var(--clr-slate);">${{ number_format($product->price, 2) }}</td>
          <td style="color:var(--clr-muted);">{{ $product->stock }}</td>
          <td>
            @if($product->category)
              <span style="background:var(--clr-blue-dim);color:var(--clr-blue);border-radius:6px;padding:3px 10px;font-size:.74rem;font-weight:600;">
                {{ $product->category->name }}
              </span>
            @else
              <span style="color:#94a3b8;">N/A</span>
            @endif
          </td>
          <td>
            @php
              $st = strtolower($product->approval_status);
              $cls = $st === 'approved' ? 'approved' : ($st === 'pending' ? 'pending' : 'rejected');
              $dot = $st === 'approved' ? '#16a34a' : ($st === 'pending' ? '#d97706' : '#dc2626');
            @endphp
            <span class="status-badge {{ $cls }}">
              <span style="width:6px;height:6px;border-radius:50%;background:{{ $dot }};display:inline-block;flex-shrink:0;"></span>
              {{ $product->approval_status }}
            </span>
            {{-- Show rejection reason below badge --}}
            @if($product->approval_status === 'Rejected' && $product->rejection_reason)
              <div style="margin-top:6px;font-size:.72rem;color:#991b1b;background:#fef2f2;border:1px solid #fecaca;border-radius:5px;padding:5px 8px;max-width:200px;line-height:1.4;">
                <i class="bi bi-exclamation-circle-fill me-1"></i>
                {{ Str::limit($product->rejection_reason, 80) }}
              </div>
            @endif
            {{-- Pending: under review notice --}}
            @if($product->approval_status === 'Pending')
              <div style="margin-top:5px;font-size:.7rem;color:#92400e;">
                <i class="bi bi-clock me-1"></i>Under Admin Review
              </div>
            @endif
          </td>
          <td style="text-align:right;">
            <div style="display:flex;gap:6px;justify-content:flex-end;flex-wrap:wrap;align-items:center;">

              @if($product->approval_status === 'Rejected')
                {{-- REJECTED: Edit (re-upload) + Variants + Delete --}}
                <a href="{{ route('seller.products.edit', $product) }}"
                   class="action-btn edit"
                   title="Edit / Re-upload Product">
                  <i class="bi bi-pencil-fill"></i>
                </a>
                <a href="{{ route('seller.products.variants.manage', $product) }}"
                   class="action-btn vars"
                   title="Manage Variants">
                  <i class="bi bi-layers-fill"></i>
                </a>
                <button type="button"
                        class="action-btn del"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteModal"
                        data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->name }}"
                        title="Delete Product">
                  <i class="bi bi-trash-fill"></i>
                </button>

              @elseif($product->approval_status === 'Pending')
                {{-- PENDING: Locked edit (disabled) + Variants + Delete --}}
                <span title="Cannot edit while under admin review"
                      class="action-btn"
                      style="cursor:not-allowed;opacity:.5;">
                  <i class="bi bi-pencil-fill"></i>
                </span>
                <a href="{{ route('seller.products.variants.manage', $product) }}"
                   class="action-btn vars"
                   title="Manage Variants">
                  <i class="bi bi-layers-fill"></i>
                </a>
                <button type="button"
                        class="action-btn del"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteModal"
                        data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->name }}"
                        title="Delete Product">
                  <i class="bi bi-trash-fill"></i>
                </button>

              @else
                {{-- APPROVED: Locked edit (disabled) + Variants + Delete --}}
                <span title="Approved products cannot be edited"
                      class="action-btn"
                      style="cursor:not-allowed;opacity:.5;">
                  <i class="bi bi-pencil-fill"></i>
                </span>
                <a href="{{ route('seller.products.variants.manage', $product) }}"
                   class="action-btn vars"
                   title="Manage Variants">
                  <i class="bi bi-layers-fill"></i>
                </a>
                <button type="button"
                        class="action-btn del"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteModal"
                        data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->name }}"
                        title="Delete Product">
                  <i class="bi bi-trash-fill"></i>
                </button>
              @endif

            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7">
            <div class="empty-state">
              <i class="bi bi-inbox"></i>
              <p>No products yet</p>
              <small>Add your first product to get started selling.</small>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($products->hasPages())
    <div style="padding: 16px 22px; border-top: 1px solid var(--clr-border);">
      {{ $products->links() }}
    </div>
  @endif
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
    <div class="modal-content">
      <div class="modal-header-danger">
        <div class="modal-title" id="deleteModalLabel">
          <i class="bi bi-exclamation-triangle-fill"></i> Delete Product?
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body-pro">
        <p style="margin:0;font-size:.9rem;color:var(--clr-text);">
          Are you sure you want to permanently delete
          <strong id="deleteProductName" style="color:var(--clr-slate);"></strong>?
        </p>
        <p style="margin:12px 0 0;font-size:.8rem;color:var(--clr-muted);background:var(--clr-bg);padding:10px 14px;border-radius:var(--radius-sm);">
          <i class="bi bi-info-circle me-1"></i>
          This will also remove all variants, images, wishlist entries, and cart records.
          <strong>This action cannot be undone.</strong>
        </p>
      </div>
      <div class="modal-footer-pro">
        <button type="button" class="btn-cancel" data-bs-dismiss="modal">
          <i class="bi bi-x-lg"></i> Cancel
        </button>
        <form id="deleteForm" method="POST" style="display:inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn-delete-confirm">
            <i class="bi bi-trash-fill"></i> Delete Product
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
      const btn         = event.relatedTarget;
      const productId   = btn.getAttribute('data-product-id');
      const productName = btn.getAttribute('data-product-name');
      document.getElementById('deleteProductName').textContent = productName;
      document.getElementById('deleteForm').action = '{{ url("seller/products") }}/' + productId;
    });
  });
</script>
@endsection
