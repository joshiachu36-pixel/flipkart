<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ReportService;

class DownloadCenterController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    // ─── Seller Download Center ────────────────────────────────────────────────
    public function sellerIndex()
    {
        $seller = Auth::guard('seller')->user();
        return view('seller.reports.download-center', compact('seller'));
    }

    /**
     * Generate a specific report type for the seller.
     * Strategy pattern — easy to add CSV/Excel later by checking $format.
     */
    public function sellerDownload(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:seller,product,variant,category,customer_interest',
            'format'      => 'required|in:pdf,print',
        ]);

        $seller   = Auth::guard('seller')->user();
        $data     = $this->reportService->getSellerReportData($seller->id, $request);
        $data['seller']        = $seller;
        $data['generatedAt']   = now()->format('d M Y, h:i A');
        $data['reportType']    = $request->report_type;
        $data['appliedFilters']= ['Report Type: ' . ucfirst(str_replace('_', ' ', $request->report_type))];
        $data['format']        = $request->format;

        if ($request->format === 'pdf') {
            return $this->generateSellerPdf($data, $request->report_type);
        }

        // Print: return HTML view
        return view('seller.reports.pdf', $data);
    }

    private function generateSellerPdf(array $data, string $type): mixed
    {
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('seller.reports.pdf', $data)
                ->setPaper('a4', 'portrait');
            return $pdf->download("seller-{$type}-report-" . now()->format('Y-m-d') . '.pdf');
        }
        return view('seller.reports.pdf', $data);
    }

    // ─── Admin Download Center ─────────────────────────────────────────────────
    public function adminIndex()
    {
        abort_unless(can_do('reports.view'), 403, 'You do not have permission to access the download center.');
        return view('admin.reports.download-center');
    }

    public function adminDownload(Request $request)
    {
        abort_unless(can_do('reports.export'), 403, 'You do not have permission to download reports.');
        $request->validate([
            'report_type' => 'required|in:marketplace,seller,product,category,variant,customer_interest',
            'format'      => 'required|in:pdf,print',
        ]);

        $data = $this->reportService->getAdminReportData($request);
        $data['generatedAt']    = now()->format('d M Y, h:i A');
        $data['reportType']     = $request->report_type;
        $data['appliedFilters'] = ['Report Type: ' . ucfirst(str_replace('_', ' ', $request->report_type))];

        if ($request->format === 'pdf') {
            return $this->generateAdminPdf($data, $request->report_type);
        }

        return view('admin.reports.pdf', $data);
    }

    private function generateAdminPdf(array $data, string $type): mixed
    {
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pdf', $data)
                ->setPaper('a4', 'landscape');
            return $pdf->download("marketplace-{$type}-report-" . now()->format('Y-m-d') . '.pdf');
        }
        return view('admin.reports.pdf', $data);
    }
}
