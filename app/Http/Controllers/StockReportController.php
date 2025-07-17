<?php
namespace App\Http\Controllers;

use App\Services\StockReportService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockReportController extends Controller
{
    protected $stockReportService;

    public function __construct(StockReportService $stockReportService)
    {
        $this->stockReportService = $stockReportService;
    }

    /**
     * Menampilkan laporan stok berdasarkan filter default atau input user.
     */
    public function index(Request $request)
    {
        // Tetapkan tanggal default (1 bulan terakhir)
        $startDate = $request->input('start_date', now()->subMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        // Tetapkan filter
        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'category' => $request->input('category'),
        ];

       $stok = $this->stockReportService->getStockReport($filters);
    $categories = $this->stockReportService->getCategories();

    // Gunakan Inertia::render untuk mengirim data sebagai props ke komponen Vue
    return Inertia::render('StockReport/Index', [
        'stok' => $stok,
        'categories' => $categories,
        'filters' => $filters, // Kirim filter kembali agar form tetap terisi
    ]);
    }

    /**
     * Memfilter laporan stok (opsional).
     */
    public function filter(Request $request)
    {
        // Gunakan logika yang sama dengan index jika tidak ada filter yang spesifik
        return $this->index($request);
    }
}
