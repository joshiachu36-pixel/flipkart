@extends('layout.seller')

@section('content')

<style>
:root { --dp: #2874f0; --ds: #1a9e5f; --dd: #e02020; --dw: #f09820; }

.dc-header {
  background: linear-gradient(135deg, #1a1f2e 0%, #2d3545 50%, #1a2a5e 100%);
  color: #fff; padding: 28px 32px 22px; border-radius: 14px; margin-bottom: 28px;
}
.dc-header h1 { font-size: 1.4rem; font-weight: 800; margin: 0; }
.dc-header p  { font-size: 0.8rem; color: #a0b4cf; margin: 5px 0 0; }

.dc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px; }

.dc-card {
  background: #fff; border-radius: 14px; box-shadow: 0 3px 18px rgba(0,0,0,0.08);
  overflow: hidden; transition: transform .2s, box-shadow .2s;
}
.dc-card:hover { transform: translateY(-4px); box-shadow: 0 8px 28px rgba(0,0,0,0.13); }

.dc-card-header { padding: 20px; display: flex; align-items: center; gap: 14px; }
.dc-card-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; }
.dc-card-title { font-size: 1rem; font-weight: 700; margin: 0; }
.dc-card-desc  { font-size: 0.76rem; color: #6c757d; margin: 3px 0 0; }

.dc-card-body { padding: 16px 20px; border-top: 1px solid #f0f0f0; background: #fafafa; }

.dc-form-row { display: flex; gap: 8px; align-items: flex-end; }
.dc-select { flex: 1; border: 1.5px solid #dee2e6; border-radius: 8px; padding: 8px 12px; font-size: 0.83rem; background: #fff; }
.dc-select:focus { border-color: var(--dp); outline: none; }

.btn-dl {
  padding: 8px 18px; border-radius: 8px; border: none; font-size: 0.83rem; font-weight: 600;
  cursor: pointer; transition: opacity .2s; display: flex; align-items: center; gap: 6px;
}
.btn-dl:hover { opacity: .87; }
.btn-pdf   { background: var(--dd); color: #fff; }
.btn-print { background: var(--dp); color: #fff; }

/* Icon colors */
.i-seller   { background: #dbeafe; color: #2874f0; }
.i-product  { background: #d1fae5; color: #1a9e5f; }
.i-market   { background: #ede9fe; color: #6366f1; }
.i-cat      { background: #fef3c7; color: #f09820; }
.i-interest { background: #fee2e2; color: #e02020; }
.i-variant  { background: #d1faf0; color: #0d9488; }

.dc-info {
  background: #f0f7ff; border: 1.5px solid #bfdbfe; border-radius: 12px;
  padding: 18px 22px; margin-bottom: 24px; font-size: 0.83rem; color: #1d4ed8;
}
.dc-info ul { margin: 8px 0 0 18px; }
.dc-info li { margin: 5px 0; }

.dc-note {
  background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 10px;
  padding: 12px 16px; font-size: 0.78rem; color: #92400e; margin-top: 16px;
}
</style>

<div>

  <div class="dc-header">
    <h1><i class="bi bi-cloud-download-fill me-2"></i>Download Center</h1>
    <p>{{ $seller->business_name }} · Generate and download professional reports</p>
  </div>

  <div class="dc-info">
    <strong><i class="bi bi-info-circle-fill me-1"></i>Available Formats</strong>
    <ul>
      <li><strong>PDF Export</strong> — Download a professional PDF report to your computer.</li>
      <li><strong>Print</strong> — Open browser print dialog (sidebar &amp; navbar excluded).</li>
      <li><em>Excel / CSV coming soon</em> — Architecture is ready for future expansion.</li>
    </ul>
  </div>

  <div class="dc-grid">

    {{-- Seller Report --}}
    <div class="dc-card">
      <div class="dc-card-header">
        <div class="dc-card-icon i-seller"><i class="bi bi-person-badge-fill"></i></div>
        <div>
          <div class="dc-card-title">Seller Report</div>
          <div class="dc-card-desc">Full analytics for your store</div>
        </div>
      </div>
      <div class="dc-card-body">
        <form action="{{ route('seller.reports.download-generate') }}" method="GET" target="_blank">
          <input type="hidden" name="report_type" value="seller">
          <div class="dc-form-row">
            <select name="format" class="dc-select">
              <option value="pdf">Export as PDF</option>
              <option value="print">Print</option>
            </select>
            <button type="submit" class="btn-dl btn-pdf">
              <i class="bi bi-file-earmark-pdf-fill"></i>Generate
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Product Report --}}
    <div class="dc-card">
      <div class="dc-card-header">
        <div class="dc-card-icon i-product"><i class="bi bi-box-seam-fill"></i></div>
        <div>
          <div class="dc-card-title">Product Report</div>
          <div class="dc-card-desc">Top products with interest data</div>
        </div>
      </div>
      <div class="dc-card-body">
        <form action="{{ route('seller.reports.download-generate') }}" method="GET" target="_blank">
          <input type="hidden" name="report_type" value="product">
          <div class="dc-form-row">
            <select name="format" class="dc-select">
              <option value="pdf">Export as PDF</option>
              <option value="print">Print</option>
            </select>
            <button type="submit" class="btn-dl btn-pdf">
              <i class="bi bi-file-earmark-pdf-fill"></i>Generate
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Variant Report --}}
    <div class="dc-card">
      <div class="dc-card-header">
        <div class="dc-card-icon i-variant"><i class="bi bi-layers-fill"></i></div>
        <div>
          <div class="dc-card-title">Variant Report</div>
          <div class="dc-card-desc">Per-color variant interest data</div>
        </div>
      </div>
      <div class="dc-card-body">
        <form action="{{ route('seller.reports.download-generate') }}" method="GET" target="_blank">
          <input type="hidden" name="report_type" value="variant">
          <div class="dc-form-row">
            <select name="format" class="dc-select">
              <option value="pdf">Export as PDF</option>
              <option value="print">Print</option>
            </select>
            <button type="submit" class="btn-dl btn-pdf">
              <i class="bi bi-file-earmark-pdf-fill"></i>Generate
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Category Report --}}
    <div class="dc-card">
      <div class="dc-card-header">
        <div class="dc-card-icon i-cat"><i class="bi bi-grid-fill"></i></div>
        <div>
          <div class="dc-card-title">Category Report</div>
          <div class="dc-card-desc">Interest distribution by category</div>
        </div>
      </div>
      <div class="dc-card-body">
        <form action="{{ route('seller.reports.download-generate') }}" method="GET" target="_blank">
          <input type="hidden" name="report_type" value="category">
          <div class="dc-form-row">
            <select name="format" class="dc-select">
              <option value="pdf">Export as PDF</option>
              <option value="print">Print</option>
            </select>
            <button type="submit" class="btn-dl btn-pdf">
              <i class="bi bi-file-earmark-pdf-fill"></i>Generate
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Customer Interest Report --}}
    <div class="dc-card">
      <div class="dc-card-header">
        <div class="dc-card-icon i-interest"><i class="bi bi-heart-pulse-fill"></i></div>
        <div>
          <div class="dc-card-title">Customer Interest Report</div>
          <div class="dc-card-desc">Wishlist &amp; cart interaction data</div>
        </div>
      </div>
      <div class="dc-card-body">
        <form action="{{ route('seller.reports.download-generate') }}" method="GET" target="_blank">
          <input type="hidden" name="report_type" value="customer_interest">
          <div class="dc-form-row">
            <select name="format" class="dc-select">
              <option value="pdf">Export as PDF</option>
              <option value="print">Print</option>
            </select>
            <button type="submit" class="btn-dl btn-pdf">
              <i class="bi bi-file-earmark-pdf-fill"></i>Generate
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>

  <div class="dc-note">
    <i class="bi bi-lightbulb-fill me-1"></i>
    <strong>Coming Soon:</strong> Excel (.xlsx) and CSV export will be added in a future update. The download architecture is already prepared for this.
  </div>

  <div style="margin-top:16px;">
    <a href="{{ route('seller.reports.index') }}" class="btn btn-sm btn-outline-primary">
      <i class="bi bi-arrow-left me-1"></i>Back to Reports
    </a>
  </div>

</div>

@endsection
