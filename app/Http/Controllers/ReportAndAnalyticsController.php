<?php

namespace App\Http\Controllers;

use App\Application\ReportAnalytics\ReportAnalytics;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ReportAndAnalyticsController extends Controller
{
    public function __construct(
        private ReportAnalytics $reportAnalytics
    ) {}

    public function index(): View
    {
        $data = $this->reportAnalytics->getReportPageData();

        return view('report', compact('data'));
    }

    public function liveData(): JsonResponse
    {
        return response()->json($this->reportAnalytics->getReportPageData());
    }

    public function indexEndOfElection(): View
    {
        $endOfElection = $this->reportAnalytics->getEndOfElectionData();

        return view('endofelection', compact('endOfElection'));
    }

    public function exportEndOfElectionPdf(): Response
    {
        $endOfElection = $this->reportAnalytics->getEndOfElectionData();

        $pdf = app('dompdf.wrapper')
            ->loadView('pdf.end-of-election-pdf', [
                'endOfElection' => $endOfElection,
                'generatedAt' => now(),
            ])
            ->setPaper('a4', 'landscape');

        return $pdf->download('end-of-election-report-'.now()->format('Y-m-d').'.pdf');
    }
}
