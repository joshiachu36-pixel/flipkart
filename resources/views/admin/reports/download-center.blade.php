@extends('layout.admin')

@section('content')

<style>
:root { --dp: #2874f0; --ds: #1a9e5f; --dd: #e02020; --dw: #f09820; }

.adc-header {
  background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #2874f0 100%);
  color: #fff; padding: 28px 32px 22px; border-radius: 14px; margin-bottom: 28px;
}
.adc-header h1 { font-size: 1.4rem; font-weight: 800; margin: 0; }
.adc-header p  { font-size: 0.8rem; color: #93c5fd; margin: 5px 0 0; }

.adc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px; }

.adc-card {
  background: #fff; border-radius: 14px; box-shadow: 0 3px 18px rgba(0,0,0,0.08);
  overflow: hidden; transition: transform .2s, box-shadow .2s;
}
.adc-card:hover { transform: translateY(-4px); box-shadow: 0 8px 28px rgba(0,0,0,0.13); }

.adc-card-header { padding: 20px; display: flex; align-items: center; gap: 14px; }
.adc-card-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; }
.adc-card-title { font-size: 1rem; font-weight: 700; margin: 0; }
.adc-card-desc  { font-size: 0.76rem; color: #6c757d; margin: 3px 0 0; }
.adc-card-body  { padding: 16px 20px; border-top: 1px solid #f0f0f0; background: #fafafa; }

.adc-form-row { display: flex; gap: 8px; align-items: flex-end; }
.adc-select { flex: 1; border: 1.5px solid #dee2e6; border-radius: 8px; padding: 8px 12px; font-size: 0.83rem; background: #fff; }
.adc-select:focus { border-color: var(--dp); outline: none; }

.btn-dl { padding: 8px 18px; border-radius: 8px; border: none; font-size: 0.83rem; font-weight: 600; cursor: pointer; transition: opacity .2s; display: flex; align-items: center; gap: 6px; }
.btn-dl:hover { opacity: .87; }
.btn-pdf   { background: var(--dd); color: #fff; }
.btn-print { background: var(--dp); color: #fff; }

.i-market   { background: #ede9fe; color: #6366f1; }
.i-seller   { background: #dbeafe; color: #2874f0; }
.i-product  { background: #d1fae5; color: #1a9e5f; }
.i-cat      { background: #fef3c7; color: #f09820; }
.i-interest { background: #fee2e2; color: #e02020; }
.i-variant  { background: #d1faf0; color: #0d9488; }

.adc-info { background: #f0f7ff; border: 1.5px solid #bfdbfe; border-radius: 12px; padding: 18px 22px; margin-bottom: 24px; font-size: 0.83rem; color: #1d4ed8; }
.adc-info ul { margin: 8px 0 0 18px; }
.adc-info li { margin: 5px 0; }
.adc-note { background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 10px; padding: 12px 16px; font-size: 0.78rem; color: #92400e; margin-top: 16px; }
</style>

<div>

  <div class="adc-header">
    <h1><i class="bi bi-cloud-download-fill me-2"></i>Marketplace Download Center</h1>
    <p>Super Admin · Generate marketplace-wide reports in PDF or Print format</p>
  </div>

  <div class="adc-info">
    <strong><i class="bi bi-shield-fill-check me-1"></i>Admin Reports</strong> — These reports cover the <strong>entire marketplace</strong>. All sellers, all products, all analytics data.
    <ul>
      <li><strong>PDF Export</strong> — Downloads a professional PDF to your computer.</li>
      <li><strong>Print</strong> — Opens browser print dialog with sidebar excluded.</li>
      <li><em>Excel / CSV coming soon</em> — Architecture is ready for future expansion.</li>
    </ul>
  </div>

  <div class="adc-grid">

    {{-- Marketplace Report --}}
    <div class="adc-card">
      <div class="adc-card-header">
        <div class="adc-card-icon i-market"><i class="bi bi-globe2"></i></div>
        <div>
          <div class="adc-card-title">Marketplace Report</div>
          <div class="adc-card-desc">Complete marketplace overview</div>
        </div>
      </div>
      <div class="adc-card-body">
        <form action="{{ route('admin.reports.download-generate') }}" method="GET" target="_blank">
          <input type="hidden" name="report_type" value="marketplace">
          <div class="adc-form-row">
            <select name="format" class="adc-select">
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

    {{-- Seller Report --}}
    <div class="adc-card">
      <div class="adc-card-header">
        <div class="adc-card-icon i-seller"><i class="bi bi-shop-window"></i></div>
        <div>
          <div class="adc-card-title">Seller Performance Report</div>
          <div class="adc-card-desc">Top sellers, interest, comparisons</div>
        </div>
      </div>
      <div class="adc-card-body">
        <form action="{{ route('admin.reports.download-generate') }}" method="GET" target="_blank">
          <input type="hidden" name="report_type" value="seller">
          <div class="adc-form-row">
            <select name="format" class="adc-select">
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
    <div class="adc-card">
      <div class="adc-card-header">
        <div class="adc-card-icon i-product"><i class="bi bi-box-seam-fill"></i></div>
        <div>
          <div class="adc-card-title">Product Report</div>
          <div class="adc-card-desc">Most popular products marketplace-wide</div>
        </div>
      </div>
      <div class="adc-card-body">
        <form action="{{ route('admin.reports.download-generate') }}" method="GET" target="_blank">
          <input type="hidden" name="report_type" value="product">
          <div class="adc-form-row">
            <select name="format" class="adc-select">
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
    <div class="adc-card">
      <div class="adc-card-header">
        <div class="adc-card-icon i-cat"><i class="bi bi-grid-fill"></i></div>
        <div>
          <div class="adc-card-title">Category Report</div>
          <div class="adc-card-desc">Category-level performance analytics</div>
        </div>
      </div>
      <div class="adc-card-body">
        <form action="{{ route('admin.reports.download-generate') }}" method="GET" target="_blank">
          <input type="hidden" name="report_type" value="category">
          <div class="adc-form-row">
            <select name="format" class="adc-select">
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
    <div class="adc-card">
      <div class="adc-card-header">
        <div class="adc-card-icon i-variant"><i class="bi bi-layers-fill"></i></div>
        <div>
          <div class="adc-card-title">Variant Report</div>
          <div class="adc-card-desc">Per-variant accurate analytics</div>
        </div>
      </div>
      <div class="adc-card-body">
        <form action="{{ route('admin.reports.download-generate') }}" method="GET" target="_blank">
          <input type="hidden" name="report_type" value="variant">
          <div class="adc-form-row">
            <select name="format" class="adc-select">
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
    <div class="adc-card">
      <div class="adc-card-header">
        <div class="adc-card-icon i-interest"><i class="bi bi-heart-pulse-fill"></i></div>
        <div>
          <div class="adc-card-title">Customer Interest Report</div>
          <div class="adc-card-desc">Wishlist &amp; cart interaction analytics</div>
        </div>
      </div>
      <div class="adc-card-body">
        <form action="{{ route('admin.reports.download-generate') }}" method="GET" target="_blank">
          <input type="hidden" name="report_type" value="customer_interest">
          <div class="adc-form-row">
            <select name="format" class="adc-select">
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

  <div class="adc-note">
    <i class="bi bi-lightbulb-fill me-1"></i>
    <strong>Future Plans:</strong> Excel (.xlsx) and CSV export will be added in a future update.
    The download architecture is already structured with a strategy pattern, making this extension straightforward.
  </div>

  <div style="margin-top:16px;">
    <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-primary">
      <i class="bi bi-arrow-left me-1"></i>Back to Admin Reports
    </a>
  </div>

</div>

@endsection
