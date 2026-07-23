<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductAnalytics;
use App\Models\Seller;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Services\ReportService;

class AdminReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    // ─── Main admin report page (with filters) ─────────────────────────────────
    public function index(Request $request)
    {
        abort_unless(can_do('reports.view'), 403, 'You do not have permission to view reports.');
        $data = $this->reportService->getAdminReportData($request);
        return view('admin.reports.index', $data);
    }

    // ─── Export PDF ─────────────────────────────────────────────────────────────
    public function exportPdf(Request $request)
    {
        abort_unless(can_do('reports.export'), 403, 'You do not have permission to export reports.');
        $data = $this->reportService->getAdminReportData($request);
        $data['generatedAt']    = now()->format('d M Y, h:i A');
        $data['appliedFilters'] = $this->buildAdminFilterSummary($request);

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pdf', $data)
                ->setPaper('a4', 'landscape');
            return $pdf->download('marketplace-report-' . now()->format('Y-m-d') . '.pdf');
        }

        return view('admin.reports.pdf', $data);
    }

    // ─── Helpers ────────────────────────────────────────────────────────────────
    private function buildAdminFilterSummary(Request $request): array
    {
        $filters = [];

        if ($request->filled('search')) {
            $filters[] = 'Search: ' . $request->search;
        }
        if ($request->filled('seller_id')) {
            $seller = Seller::find($request->seller_id);
            $filters[] = 'Seller: ' . ($seller?->business_name ?? $request->seller_id);
        }
        if ($request->filled('category_id')) {
            $cat = Category::find($request->category_id);
            $filters[] = 'Category: ' . ($cat?->name ?? $request->category_id);
        }
        if ($request->filled('brand_id')) {
            $brand = Brand::find($request->brand_id);
            $filters[] = 'Brand: ' . ($brand?->name ?? $request->brand_id);
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $filters[] = 'Status: ' . ucfirst($request->status);
        }
        if ($request->filled('approval_status') && $request->approval_status !== 'all') {
            $filters[] = 'Approval: ' . ucfirst($request->approval_status);
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

        return $filters ?: ['No filters applied — entire marketplace'];
    }
}
