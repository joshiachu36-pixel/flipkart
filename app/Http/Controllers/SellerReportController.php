<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Services\ReportService;

class SellerReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    // ─── Main report page (with filters) ──────────────────────────────────────
    public function index(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $data   = $this->reportService->getSellerReportData($seller->id, $request);

        // Merge seller into data (service doesn't have it in scope)
        $data['seller'] = $seller;

        return view('seller.reports.index', $data);
    }

    // ─── Export PDF ────────────────────────────────────────────────────────────
    public function exportPdf(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $data   = $this->reportService->getSellerReportData($seller->id, $request);
        $data['seller']        = $seller;
        $data['generatedAt']   = now()->format('d M Y, h:i A');
        $data['appliedFilters'] = $this->buildSellerFilterSummary($request);

        // Use DomPDF if installed, otherwise fallback to printable HTML
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('seller.reports.pdf', $data)
                ->setPaper('a4', 'portrait');
            return $pdf->download('seller-report-' . now()->format('Y-m-d') . '.pdf');
        }

        // Fallback: render printable HTML
        return view('seller.reports.pdf', $data);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────
    private function buildSellerFilterSummary(Request $request): array
    {
        $filters = [];

        if ($request->filled('search')) {
            $filters[] = 'Search: ' . $request->search;
        }
        if ($request->filled('product_id')) {
            $product = Product::find($request->product_id);
            $filters[] = 'Product: ' . ($product?->name ?? $request->product_id);
        }
        if ($request->filled('category_id')) {
            $cat = \App\Models\Category::find($request->category_id);
            $filters[] = 'Category: ' . ($cat?->name ?? $request->category_id);
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $filters[] = 'Status: ' . ucfirst($request->status);
        }
        if ($request->filled('quick_date') && $request->quick_date !== 'custom') {
            $labels = [
                'today'      => 'Today',
                'yesterday'  => 'Yesterday',
                'last7'      => 'Last 7 Days',
                'last30'     => 'Last 30 Days',
                'this_month' => 'This Month',
                'last_month' => 'Last Month',
            ];
            $filters[] = 'Period: ' . ($labels[$request->quick_date] ?? $request->quick_date);
        } elseif ($request->filled('date_from') || $request->filled('date_to')) {
            $filters[] = 'Date: ' . ($request->date_from ?? '∞') . ' → ' . ($request->date_to ?? '∞');
        }

        return $filters ?: ['No filters applied (all data)'];
    }
}
