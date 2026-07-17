<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@php
  $isAdmin = request()->is('admin/*') || isset($totalSellers);
  
  if ($isAdmin) {
      $tab = request('tab', 'top-sellers');
      $titles = [
          'top-sellers'  => 'SELLER PERFORMANCE REPORT',
          'top-products' => 'PRODUCT ANALYTICS REPORT',
          'category'     => 'CATEGORY PERFORMANCE REPORT',
          'variant'      => 'VARIANT ANALYTICS REPORT',
          'pending'      => 'PENDING PRODUCTS REPORT',
          'rejected'     => 'REJECTED PRODUCTS REPORT',
          'zero'         => 'ZERO INTEREST PRODUCTS REPORT',
      ];
      $reportTitle = $titles[$tab] ?? 'MARKETPLACE ANALYTICS REPORT';
  } else {
      $reportTitle = 'PRODUCT ANALYTICS REPORT';
  }
@endphp
<title>{{ ucwords(strtolower($reportTitle)) }} — Flipkart — {{ now()->format('d M Y') }}</title>
<style>
/* ══════════════════════════════════════════════════════════════
   BUSINESS REPORT STYLE (A4 Portrait - Black & White)
   Palette: Minimalist Black, White, and Light Greys
   ══════════════════════════════════════════════════════════════ */

* { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
  font-size: 10px;
  color: #000000;
  background: #ffffff;
  line-height: 1.4;
  padding: 0;
  margin: 0;
}

/* Page container */
.page {
  max-width: 800px;
  margin: 0 auto;
  background: #ffffff;
  padding: 20px;
}

/* Centered Header */
.report-header {
  text-align: center;
  margin-top: 10px;
  margin-bottom: 15px;
}

.report-header h1 {
  font-size: 16px;
  font-weight: bold;
  margin: 0 0 4px 0;
  letter-spacing: 1px;
}

.report-header h2 {
  font-size: 12px;
  font-weight: bold;
  margin: 0 0 10px 0;
  letter-spacing: 0.5px;
}

.header-divider {
  border-top: 1px solid #000000;
  border-bottom: 1px solid #000000;
  height: 3px;
  margin: 8px 0 15px 0;
}

/* Info section table */
.info-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

.info-table td {
  border: none;
  font-size: 10px;
  padding: 3px 0;
  vertical-align: top;
}

.info-label {
  font-weight: bold;
  width: 120px;
}

.info-value {
  border-bottom: 1px solid #cccccc;
  padding-left: 5px;
}

/* Section styling */
.report-section {
  margin-bottom: 25px;
  page-break-inside: avoid;
}

.section-title {
  font-size: 11px;
  font-weight: bold;
  text-transform: uppercase;
  border-bottom: 1.5px solid #000000;
  padding-bottom: 2px;
  margin-bottom: 10px;
}

/* Summary table */
.summary-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 15px;
}

.summary-table td {
  padding: 5px 0;
  font-size: 10px;
  border-bottom: 1px dashed #cccccc;
}

.summary-table tr:last-child td {
  border-bottom: none;
}

/* Main report tables */
.report-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 5px;
  margin-bottom: 15px;
}

.report-table th {
  border-top: 1.5px solid #000000;
  border-bottom: 1.5px solid #000000;
  padding: 6px 8px;
  font-weight: bold;
  text-align: left;
  font-size: 9.5px;
  text-transform: uppercase;
}

.report-table td {
  border-bottom: 1px solid #e0e0e0;
  padding: 5px 8px;
  font-size: 9.5px;
  vertical-align: middle;
}

.report-table tr:last-child td {
  border-bottom: 1.5px solid #000000;
}

/* Alignment utilities */
.text-center { text-align: center !important; }
.text-right { text-align: right !important; }

/* Footer styling */
#footer {
  text-align: center;
  font-size: 8px;
  margin-top: 30px;
  border-top: 1px solid #000000;
  padding-top: 8px;
  line-height: 1.4;
  page-break-inside: avoid;
}

/* Screen toolbar for browser preview */
.preview-toolbar {
  background: #ffffff;
  border-bottom: 1px solid #e2e8f0;
  padding: 10px 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  gap: 12px;
}

.toolbar-title {
  font-size: 0.85rem;
  font-weight: 700;
  color: #1e293b;
  display: flex;
  align-items: center;
  gap: 6px;
}

.toolbar-actions {
  display: flex;
  gap: 8px;
}

.btn-print {
  background: #0f172a;
  color: #ffffff;
  border: none;
  border-radius: 4px;
  padding: 6px 14px;
  font-size: 0.8rem;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.btn-print:hover {
  background: #1e293b;
}

.btn-back {
  background: #f1f5f9;
  color: #334155;
  border: 1px solid #e2e8f0;
  border-radius: 4px;
  padding: 6px 12px;
  font-size: 0.8rem;
  cursor: pointer;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 5px;
}

.btn-back:hover {
  background: #e2e8f0;
}

/* ══════════════════════════════════════════════════════════════
   PRINT OVERRIDES
   ══════════════════════════════════════════════════════════════ */
@media print {
  .no-print { display: none !important; }
  body { background: #ffffff !important; color: #000000 !important; }
  .page { max-width: 100%; padding: 0; }
  @page { size: A4 portrait; margin: 15mm 12mm; }
  
  /* DomPDF Page numbering helper compatibility */
  #footer {
    position: fixed;
    bottom: 0px;
    left: 0px;
    right: 0px;
  }
}

/* DomPDF Page Number Counter */
.page-num:before {
  content: counter(page);
}
.page-total:before {
  content: counter(pages);
}
</style>

@if(request()->has('autoprint'))
<script>
  window.addEventListener('load', function() {
    setTimeout(function() { window.print(); }, 500);
  });
</script>
@endif
</head>
<body>

@php
  $quickDate = request('quick_date');
  $dateFrom = request('date_from');
  $dateTo = request('date_to');

  $period = 'All Dates';
  if ($quickDate && $quickDate !== 'custom') {
      $labels = [
          'today'      => 'Today',
          'yesterday'  => 'Yesterday',
          'last7'      => 'Last 7 Days',
          'last30'     => 'Last 30 Days',
          'this_month' => 'This Month',
          'last_month' => 'Last Month',
      ];
      $period = $labels[$quickDate] ?? ucfirst($quickDate);
  } elseif ($dateFrom || $dateTo) {
      $period = ($dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d M Y') : 'Start') . ' to ' . ($dateTo ? \Carbon\Carbon::parse($dateTo)->format('d M Y') : 'End');
  }

  $hasFilters = false;
  $filterList = [];
  if (isset($appliedFilters) && is_array($appliedFilters)) {
      foreach ($appliedFilters as $filter) {
          if (strpos($filter, 'No filters applied') === false) {
              $hasFilters = true;
              $filterList[] = $filter;
          }
      }
  }
@endphp


<div class="page">
  {{-- Header --}}
  <div class="report-header">
    <h1>FLIPKART MARKETPLACE</h1>
    <h2>{{ $reportTitle }}</h2>
  </div>

  <div class="header-divider"></div>

  {{-- Business Info --}}
  <table class="info-table">
    <tr>
      <td class="info-label">Business Name:</td>
      <td class="info-value" style="width: 35%;">{{ $isAdmin ? 'Flipkart Marketplace' : $seller->business_name }}</td>
      <td class="info-label" style="padding-left: 20px;">Generated Date:</td>
      <td class="info-value" style="width: 35%;">{{ now()->format('d M Y') }}</td>
    </tr>
    @if(!$isAdmin)
    <tr>
      <td class="info-label">Seller Name:</td>
      <td class="info-value">{{ $seller->owner_name }}</td>
      <td class="info-label" style="padding-left: 20px;">Generated Time:</td>
      <td class="info-value">{{ now()->format('h:i A') }}</td>
    </tr>
    @endif
    <tr>
      <td class="info-label">Report Type:</td>
      <td class="info-value">{{ ucwords(strtolower($reportTitle)) }}</td>
      <td class="info-label" style="padding-left: 20px;">
        @if($isAdmin) Generated Time: @else Report Period: @endif
      </td>
      <td class="info-value">
        @if($isAdmin) {{ now()->format('h:i A') }} @else {{ $period }} @endif
      </td>
    </tr>
    @if($isAdmin)
    <tr>
      <td class="info-label">Report Period:</td>
      <td class="info-value">{{ $period }}</td>
      <td class="info-label" style="padding-left: 20px;"></td>
      <td class="info-value"></td>
    </tr>
    @endif
    <tr>
      <td class="info-label">Applied Filters:</td>
      <td class="info-value" colspan="3">
        @if($hasFilters)
          {{ implode(', ', $filterList) }}
        @else
          All Products, All Categories, All Dates
        @endif
      </td>
    </tr>
  </table>

  {{-- Summary Section --}}
  <div class="report-section">
    <div class="section-title">Summary</div>
    <table class="summary-table">
      @if(!$isAdmin)
        {{-- Seller Summary --}}
        <tr>
          <td style="width: 60%; font-weight: bold;">Total Products</td>
          <td class="text-right">{{ $totalProducts }}</td>
        </tr>
        <tr>
          <td style="font-weight: bold;">Wishlist Count</td>
          <td class="text-right">{{ $totalWishlist }}</td>
        </tr>
        <tr>
          <td style="font-weight: bold;">Cart Count</td>
          <td class="text-right">{{ $totalCart }}</td>
        </tr>
        <tr>
          <td style="font-weight: bold;">Total Interest</td>
          <td class="text-right" style="font-weight: bold;">{{ $totalInterest }}</td>
        </tr>
        <tr>
          <td style="font-weight: bold;">Zero Interest Products</td>
          <td class="text-right">{{ $zeroInterestCount }}</td>
        </tr>
      @else
        {{-- Admin Summary --}}
        @if($tab === 'top-sellers')
          <tr>
            <td style="width: 60%; font-weight: bold;">Total Sellers</td>
            <td class="text-right">{{ $totalSellers }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Approved Sellers</td>
            <td class="text-right">{{ $approvedSellers }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Pending Sellers</td>
            <td class="text-right">{{ $pendingSellers }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Wishlist Count</td>
            <td class="text-right">{{ $totalWishlist }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Cart Count</td>
            <td class="text-right">{{ $totalCart }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Total Interest</td>
            <td class="text-right" style="font-weight: bold;">{{ $totalInterest }}</td>
          </tr>
        @elseif($tab === 'top-products')
          <tr>
            <td style="width: 60%; font-weight: bold;">Total Products</td>
            <td class="text-right">{{ $totalProducts }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Approved Products</td>
            <td class="text-right">{{ $approvedProducts }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Pending Products</td>
            <td class="text-right">{{ $pendingProducts }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Total Brands</td>
            <td class="text-right">{{ count($brands) }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Wishlist Count</td>
            <td class="text-right">{{ $totalWishlist }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Cart Count</td>
            <td class="text-right">{{ $totalCart }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Total Interest</td>
            <td class="text-right" style="font-weight: bold;">{{ $totalInterest }}</td>
          </tr>
        @elseif($tab === 'category')
          <tr>
            <td style="width: 60%; font-weight: bold;">Total Categories</td>
            <td class="text-right">{{ count($categories) }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Wishlist Count</td>
            <td class="text-right">{{ $totalWishlist }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Cart Count</td>
            <td class="text-right">{{ $totalCart }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Total Interest</td>
            <td class="text-right" style="font-weight: bold;">{{ $totalInterest }}</td>
          </tr>
        @elseif($tab === 'variant')
          <tr>
            <td style="width: 60%; font-weight: bold;">Total Products</td>
            <td class="text-right">{{ $totalProducts }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Wishlist Count</td>
            <td class="text-right">{{ $totalWishlist }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Cart Count</td>
            <td class="text-right">{{ $totalCart }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Total Interest</td>
            <td class="text-right" style="font-weight: bold;">{{ $totalInterest }}</td>
          </tr>
        @elseif($tab === 'pending')
          <tr>
            <td style="width: 60%; font-weight: bold;">Pending Products</td>
            <td class="text-right">{{ $pendingProducts }}</td>
          </tr>
        @elseif($tab === 'rejected')
          <tr>
            <td style="width: 60%; font-weight: bold;">Rejected Products</td>
            <td class="text-right">{{ count($rejectedProductsList) }}</td>
          </tr>
        @elseif($tab === 'zero')
          <tr>
            <td style="width: 60%; font-weight: bold;">Zero Interest Products</td>
            <td class="text-right">{{ count($zeroInterestList) + count($untrackedProducts) }}</td>
          </tr>
        @endif
      @endif
    </table>
  </div>

  {{-- Main Data Tables --}}
  <div class="report-section">
    <div class="section-title">Report Data</div>
    
    @if(!$isAdmin)
      {{-- Seller Report - BOTH Top Products and Variant Analytics (backward compatible) --}}
      <table class="report-table">
        <thead>
          <tr>
            <th style="width: 6%; text-align: center;">#</th>
            <th style="width: 44%;">Product Name</th>
            <th style="width: 26%;">Category</th>
            <th style="width: 8%; text-align: right;">Wishlist</th>
            <th style="width: 8%; text-align: right;">Cart</th>
            <th style="width: 8%; text-align: right;">Total Interest</th>
          </tr>
        </thead>
        <tbody>
          @forelse($topProducts as $idx => $a)
            <tr>
              <td class="text-center">{{ $idx + 1 }}</td>
              <td style="font-weight: bold;">{{ $a->product?->name ?? 'Unknown' }}</td>
              <td>{{ $a->product?->category?->name ?? '—' }}</td>
              <td class="text-right">{{ number_format($a->wishlist_count) }}</td>
              <td class="text-right">{{ number_format($a->cart_count) }}</td>
              <td class="text-right" style="font-weight: bold;">{{ number_format($a->total_interest) }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center" style="padding: 12px; color: #555555;">No product analytics data available.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      @if($variantAnalytics->isNotEmpty())
        <div class="section-title" style="margin-top: 25px;">Variant Analytics</div>
        <table class="report-table">
          <thead>
            <tr>
              <th style="width: 35%;">Product</th>
              <th style="width: 20%;">Variant / Color</th>
              <th style="width: 27%;">Sizes</th>
              <th style="width: 6%; text-align: right;">Wishlist</th>
              <th style="width: 6%; text-align: right;">Cart</th>
              <th style="width: 6%; text-align: right;">Interest</th>
            </tr>
          </thead>
          <tbody>
            @foreach($variantAnalytics as $va)
              <tr>
                <td style="font-weight: bold;">{{ $va['product']->name ?? 'Unknown' }}</td>
                <td>{{ $va['color']?->name ?? 'N/A' }}</td>
                <td>
                  @php
                    $sizeList = [];
                    foreach ($va['sizes'] as $sz) {
                        $sizeList[] = $sz->name . ' (' . $sz->pivot->stock . ')';
                    }
                  @endphp
                  {{ implode(', ', $sizeList) ?: 'N/A' }}
                </td>
                <td class="text-right">{{ number_format($va['wishlist']) }}</td>
                <td class="text-right">{{ number_format($va['cart']) }}</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($va['total_interest']) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif

    @else
      {{-- Admin Report - Select Active Tab Table --}}
      @if($tab === 'top-sellers')
        <table class="report-table">
          <thead>
            <tr>
              <th style="width: 8%; text-align: center;">#</th>
              <th style="width: 44%;">Seller</th>
              <th style="width: 16%; text-align: right;">Products</th>
              <th style="width: 10%; text-align: right;">Wishlist</th>
              <th style="width: 10%; text-align: right;">Cart</th>
              <th style="width: 12%; text-align: right;">Total Interest</th>
            </tr>
          </thead>
          <tbody>
            @forelse($sellerPerformance as $idx => $sp)
              <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td style="font-weight: bold;">{{ $sp['seller']?->business_name ?? 'Unknown' }}</td>
                <td class="text-right">{{ number_format($sp['product_count']) }}</td>
                <td class="text-right">{{ number_format($sp['wishlist_count']) }}</td>
                <td class="text-right">{{ number_format($sp['cart_count']) }}</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($sp['total_interest']) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center" style="padding: 12px; color: #555555;">No seller performance data available.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      @elseif($tab === 'top-products')
        <table class="report-table">
          <thead>
            <tr>
              <th style="width: 6%; text-align: center;">#</th>
              <th style="width: 32%;">Product Name</th>
              <th style="width: 22%;">Seller</th>
              <th style="width: 18%;">Category</th>
              <th style="width: 7%; text-align: right;">Wishlist</th>
              <th style="width: 7%; text-align: right;">Cart</th>
              <th style="width: 8%; text-align: right;">Total Interest</th>
            </tr>
          </thead>
          <tbody>
            @forelse($popularProducts as $idx => $a)
              <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td style="font-weight: bold;">{{ $a->product?->name ?? 'Unknown' }}</td>
                <td>{{ $a->product?->seller?->business_name ?? '—' }}</td>
                <td>{{ $a->product?->category?->name ?? '—' }}</td>
                <td class="text-right">{{ number_format($a->wishlist_count) }}</td>
                <td class="text-right">{{ number_format($a->cart_count) }}</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($a->total_interest) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center" style="padding: 12px; color: #555555;">No product analytics data available.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      @elseif($tab === 'category')
        <table class="report-table">
          <thead>
            <tr>
              <th style="width: 8%; text-align: center;">#</th>
              <th style="width: 52%;">Category</th>
              <th style="width: 12%; text-align: right;">Wishlist</th>
              <th style="width: 12%; text-align: right;">Cart</th>
              <th style="width: 16%; text-align: right;">Total Interest</th>
            </tr>
          </thead>
          <tbody>
            @forelse($categoryPerformance as $idx => $cp)
              <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td style="font-weight: bold;">{{ $cp['category']?->name ?? 'Unknown' }}</td>
                <td class="text-right">{{ number_format($cp['wishlist_count']) }}</td>
                <td class="text-right">{{ number_format($cp['cart_count']) }}</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($cp['total_interest']) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center" style="padding: 12px; color: #555555;">No category analytics data available.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      @elseif($tab === 'variant')
        <table class="report-table">
          <thead>
            <tr>
              <th style="width: 25%;">Product</th>
              <th style="width: 18%;">Seller</th>
              <th style="width: 16%;">Variant / Color</th>
              <th style="width: 23%;">Sizes</th>
              <th style="width: 6%; text-align: right;">Wishlist</th>
              <th style="width: 6%; text-align: right;">Cart</th>
              <th style="width: 6%; text-align: right;">Interest</th>
            </tr>
          </thead>
          <tbody>
            @forelse($variantAnalytics as $va)
              <tr>
                <td style="font-weight: bold;">{{ $va['product']->name ?? 'Unknown' }}</td>
                <td>{{ $va['product']->seller?->business_name ?? '—' }}</td>
                <td>{{ $va['color']?->name ?? 'N/A' }}</td>
                <td>
                  @php
                    $sizeList = [];
                    foreach ($va['sizes'] as $sz) {
                        $sizeList[] = $sz->name . ' (' . $sz->pivot->stock . ')';
                    }
                  @endphp
                  {{ implode(', ', $sizeList) ?: 'N/A' }}
                </td>
                <td class="text-right">{{ number_format($va['wishlist']) }}</td>
                <td class="text-right">{{ number_format($va['cart']) }}</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($va['total_interest']) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center" style="padding: 12px; color: #555555;">No variant analytics data available.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      @elseif($tab === 'pending')
        <table class="report-table">
          <thead>
            <tr>
              <th style="width: 8%; text-align: center;">#</th>
              <th style="width: 42%;">Product Name</th>
              <th style="width: 24%;">Seller</th>
              <th style="width: 16%;">Category</th>
              <th style="width: 10%;">Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pendingProductsList as $idx => $p)
              <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td style="font-weight: bold;">{{ $p->name }}</td>
                <td>{{ $p->seller?->business_name ?? '—' }}</td>
                <td>{{ $p->category?->name ?? '—' }}</td>
                <td>Pending Approval</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center" style="padding: 12px; color: #555555;">No pending products.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      @elseif($tab === 'rejected')
        <table class="report-table">
          <thead>
            <tr>
              <th style="width: 8%; text-align: center;">#</th>
              <th style="width: 42%;">Product Name</th>
              <th style="width: 24%;">Seller</th>
              <th style="width: 16%;">Category</th>
              <th style="width: 10%;">Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rejectedProductsList as $idx => $p)
              <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td style="font-weight: bold;">{{ $p->name }}</td>
                <td>{{ $p->seller?->business_name ?? '—' }}</td>
                <td>{{ $p->category?->name ?? '—' }}</td>
                <td>Rejected</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center" style="padding: 12px; color: #555555;">No rejected products.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      @elseif($tab === 'zero')
        <table class="report-table">
          <thead>
            <tr>
              <th style="width: 8%; text-align: center;">#</th>
              <th style="width: 42%;">Product Name</th>
              <th style="width: 24%;">Seller</th>
              <th style="width: 16%;">Category</th>
              <th style="width: 10%;">Status</th>
            </tr>
          </thead>
          <tbody>
            @php $counter = 1; @endphp
            @foreach($zeroInterestList as $a)
              <tr>
                <td class="text-center">{{ $counter++ }}</td>
                <td style="font-weight: bold;">{{ $a->product?->name ?? 'Unknown' }}</td>
                <td>{{ $a->product?->seller?->business_name ?? '—' }}</td>
                <td>{{ $a->product?->category?->name ?? '—' }}</td>
                <td>Zero Interest</td>
              </tr>
            @endforeach
            @foreach($untrackedProducts as $p)
              <tr>
                <td class="text-center">{{ $counter++ }}</td>
                <td style="font-weight: bold;">{{ $p->name }}</td>
                <td>{{ $p->seller?->business_name ?? '—' }}</td>
                <td>{{ $p->category?->name ?? '—' }}</td>
                <td>No Data</td>
              </tr>
            @endforeach
            @if(count($zeroInterestList) == 0 && count($untrackedProducts) == 0)
              <tr>
                <td colspan="5" class="text-center" style="padding: 12px; color: #555555;">No zero interest products.</td>
              </tr>
            @endif
          </tbody>
        </table>
      @endif
    @endif
  </div>

  {{-- Report Totals --}}
  <div class="report-section">
    <div class="section-title">Report Totals</div>
    <table class="summary-table">
      @if(!$isAdmin)
        <tr>
          <td style="width: 60%; font-weight: bold;">Total Products</td>
          <td class="text-right">{{ $totalProducts }}</td>
        </tr>
        <tr>
          <td style="font-weight: bold;">Total Wishlist</td>
          <td class="text-right">{{ $totalWishlist }}</td>
        </tr>
        <tr>
          <td style="font-weight: bold;">Total Cart</td>
          <td class="text-right">{{ $totalCart }}</td>
        </tr>
        <tr>
          <td style="font-weight: bold; border-bottom: 1.5px solid #000000; padding-bottom: 4px;">Total Interest</td>
          <td class="text-right" style="font-weight: bold; border-bottom: 1.5px solid #000000; padding-bottom: 4px;">{{ $totalInterest }}</td>
        </tr>
      @else
        @if($tab === 'top-sellers')
          <tr>
            <td style="width: 60%; font-weight: bold;">Total Sellers</td>
            <td class="text-right">{{ $totalSellers }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Total Products</td>
            <td class="text-right">{{ $totalProducts }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Total Wishlist</td>
            <td class="text-right">{{ $totalWishlist }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Total Cart</td>
            <td class="text-right">{{ $totalCart }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; border-bottom: 1.5px solid #000000; padding-bottom: 4px;">Total Interest</td>
            <td class="text-right" style="font-weight: bold; border-bottom: 1.5px solid #000000; padding-bottom: 4px;">{{ $totalInterest }}</td>
          </tr>
        @elseif($tab === 'top-products')
          <tr>
            <td style="width: 60%; font-weight: bold;">Total Products</td>
            <td class="text-right">{{ $totalProducts }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Total Wishlist</td>
            <td class="text-right">{{ $totalWishlist }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Total Cart</td>
            <td class="text-right">{{ $totalCart }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; border-bottom: 1.5px solid #000000; padding-bottom: 4px;">Total Interest</td>
            <td class="text-right" style="font-weight: bold; border-bottom: 1.5px solid #000000; padding-bottom: 4px;">{{ $totalInterest }}</td>
          </tr>
        @elseif($tab === 'category')
          <tr>
            <td style="width: 60%; font-weight: bold;">Total Categories</td>
            <td class="text-right">{{ count($categories) }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Total Wishlist</td>
            <td class="text-right">{{ $totalWishlist }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Total Cart</td>
            <td class="text-right">{{ $totalCart }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; border-bottom: 1.5px solid #000000; padding-bottom: 4px;">Total Interest</td>
            <td class="text-right" style="font-weight: bold; border-bottom: 1.5px solid #000000; padding-bottom: 4px;">{{ $totalInterest }}</td>
          </tr>
        @elseif($tab === 'variant')
          <tr>
            <td style="width: 60%; font-weight: bold;">Total Products</td>
            <td class="text-right">{{ $totalProducts }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Total Wishlist</td>
            <td class="text-right">{{ $totalWishlist }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold;">Total Cart</td>
            <td class="text-right">{{ $totalCart }}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; border-bottom: 1.5px solid #000000; padding-bottom: 4px;">Total Interest</td>
            <td class="text-right" style="font-weight: bold; border-bottom: 1.5px solid #000000; padding-bottom: 4px;">{{ $totalInterest }}</td>
          </tr>
        @elseif($tab === 'pending')
          <tr>
            <td style="width: 60%; font-weight: bold; border-bottom: 1.5px solid #000000; padding-bottom: 4px;">Total Pending Products</td>
            <td class="text-right" style="font-weight: bold; border-bottom: 1.5px solid #000000; padding-bottom: 4px;">{{ $pendingProducts }}</td>
          </tr>
        @elseif($tab === 'rejected')
          <tr>
            <td style="width: 60%; font-weight: bold; border-bottom: 1.5px solid #000000; padding-bottom: 4px;">Total Rejected Products</td>
            <td class="text-right" style="font-weight: bold; border-bottom: 1.5px solid #000000; padding-bottom: 4px;">{{ count($rejectedProductsList) }}</td>
          </tr>
        @elseif($tab === 'zero')
          <tr>
            <td style="width: 60%; font-weight: bold; border-bottom: 1.5px solid #000000; padding-bottom: 4px;">Total Zero Interest Products</td>
            <td class="text-right" style="font-weight: bold; border-bottom: 1.5px solid #000000; padding-bottom: 4px;">{{ count($zeroInterestList) + count($untrackedProducts) }}</td>
          </tr>
        @endif
      @endif
    </table>
  </div>

  {{-- Footer --}}
  <div id="footer">
    Generated by Flipkart Marketplace<br>
    This is a system generated report.<br>
    Page <span class="page-num"></span> of <span class="page-total"></span>
  </div>
</div>

</body>
</html>
