<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    // ✅ Laporan Stok Barang
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $categoryId = $request->input('category_id');

        \Log::info('Fetching stock reports with parameters:', ['start_date' => $startDate, 'end_date' => $endDate, 'category_id' => $categoryId]);
        $stockReports = $this->reportService->getStockReport($startDate, $endDate, $categoryId);
        \Log::info('Stock reports fetched:', ['reports' => $stockReports]);


        return view('reports.stock', compact('stockReports'));
    }

    // ✅ Laporan Barang Masuk & Keluar
    public function incomingOutgoing(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        \Log::info('Fetching incoming transactions with parameters:', ['start_date' => $startDate, 'end_date' => $endDate]);
        $incomingTransactions = $this->reportService->getIncomingTransactions($startDate, $endDate);
        \Log::info('Incoming transactions fetched:', ['transactions' => $incomingTransactions]);

        \Log::info('Fetching outgoing transactions with parameters:', ['start_date' => $startDate, 'end_date' => $endDate]);
        $outgoingTransactions = $this->reportService->getOutgoingTransactions($startDate, $endDate);
        \Log::info('Outgoing transactions fetched:', ['transactions' => $outgoingTransactions]);


        return view('reports.incoming_outgoing', compact('incomingTransactions', 'outgoingTransactions'));
    }
}
