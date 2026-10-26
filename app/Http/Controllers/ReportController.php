<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Sale::with('items.product')->latest();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $sales = $query->get();

        return view('reports.index', compact('sales', 'startDate', 'endDate'));
    }

    // Method untuk Download PDF Laporan
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Sale::with('items.product')->latest();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $sales = $query->get();

        $pdf = Pdf::loadView('reports.pdf', compact('sales', 'startDate', 'endDate'));
        return $pdf->download('Laporan_Penjualan_GlowDesk_' . date('Y-m-d') . '.pdf');
    }

    // Method untuk Download Excel
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(new SalesReportExport($startDate, $endDate), 'Laporan_Penjualan_GlowDesk_' . date('Y-m-d') . '.xlsx');
    }
}