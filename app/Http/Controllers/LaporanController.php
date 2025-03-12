<?php

namespace App\Http\Controllers;

use App\Services\StockReportService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\User;
use App\Models\Category;
use DB;
use App\Models\ActivityLog;
use Carbon\Carbon;


class LaporanController extends Controller
{
    
    public function stokFilter(Request $request)
{
    $startDate = $request->input('start_date', now()->subMonth()->toDateString());
    $endDate = $request->input('end_date', now()->toDateString());

    $query = Product::select(
        'products.id', 
        'products.name', 
        'products.category_id',
        'products.initial_stock', 
        'products.stock',
        DB::raw('COALESCE(products.initial_stock, 0) as stok_awal'),
        DB::raw('COALESCE(transaksi.total_masuk, 0) as barang_masuk'),
        DB::raw('COALESCE(transaksi.total_keluar, 0) as barang_keluar'),
        DB::raw('COALESCE(stock_opnames.stock_opname_masuk, 0) as stock_opname_masuk'),
        DB::raw('COALESCE(stock_opnames.stock_opname_keluar, 0) as stock_opname_keluar'),
        DB::raw('(COALESCE(products.initial_stock, 0) 
                 + COALESCE(transaksi.total_masuk, 0) 
                 - COALESCE(transaksi.total_keluar, 0)
                 + COALESCE(stock_opnames.stock_opname_masuk, 0)
                 - COALESCE(stock_opnames.stock_opname_keluar, 0)) as stok_akhir')
    )
    ->with('category')
    ->leftJoin(DB::raw('(SELECT product_id, 
            SUM(CASE WHEN type = "Masuk" AND status = "Diterima" THEN quantity ELSE 0 END) as total_masuk,
            SUM(CASE WHEN type = "Keluar" AND status = "Diterima" THEN quantity ELSE 0 END) as total_keluar
            FROM stock_transactions GROUP BY product_id) as transaksi'), 'products.id', '=', 'transaksi.product_id')
            ->leftJoin(DB::raw('(SELECT product_id, 
            SUM(CASE WHEN difference > 0 THEN difference ELSE 0 END) as stock_opname_masuk,
            SUM(CASE WHEN difference < 0 THEN ABS(difference) ELSE 0 END) as stock_opname_keluar
            FROM stock_opnames 
            GROUP BY product_id) as stock_opnames'), 'products.id', '=', 'stock_opnames.product_id');
        
    if ($request->has('category') && $request->category != '') {
        $query->where('products.category_id', $request->category);
    }

    if ($request->has('start_date') && $request->start_date != '') {
        $query->whereDate('products.created_at', '>=', $request->start_date);
    }
    if ($request->has('end_date') && $request->end_date != '') {
        $query->whereDate('products.created_at', '<=', $request->end_date);
    }

    $stok = $query->paginate(10);
    $categories = Category::all();

    return view('laporan.stok', compact('stok', 'categories', 'startDate', 'endDate'));
}


public function stok(Request $request, StockReportService $stockReportService)
{
    $startDate = $request->input('start_date', now()->subMonth()->toDateString());
    $endDate = $request->input('end_date', now()->toDateString());

    $filters = [
        'start_date' => $startDate,
        'end_date' => $endDate,
        'category' => $request->input('category'),
    ];

    $stok = $stockReportService->getStockReport($filters);
    $categories = Category::all();

    return view('laporan.stok', compact('stok', 'categories', 'startDate', 'endDate', 'filters'));
}


public function aktivitas(Request $request)
{
    // Ambil tanggal dari request atau gunakan default
    $startDate = $request->input('tanggal_mulai', now()->startOfMonth()->toDateString());
    $endDate = $request->input('tanggal_akhir', now()->toDateString());

    // Konversi tanggal ke format Carbon
    $start = Carbon::parse($startDate)->startOfDay();
    $end = Carbon::parse($endDate)->endOfDay();

    // Ambil aktivitas berdasarkan rentang tanggal
    $aktivitas = ActivityLog::whereBetween('created_at', [$start, $end])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('laporan.aktivitas', compact('aktivitas', 'startDate', 'endDate'));
}

public function destroy($id)
{
    // Find the activity log by ID
    $activityLog = ActivityLog::find($id);

    if (!$activityLog) {
        // If no activity log is found, redirect with an error message
        return redirect()->route('laporan.aktivitas')->with('error', 'Activity log not found.');
    }

    // Delete the activity log
    $activityLog->delete();

    // Redirect with a success message
    return redirect()->route('laporan.aktivitas')->with('success', 'Activity log deleted successfully.');
}






}