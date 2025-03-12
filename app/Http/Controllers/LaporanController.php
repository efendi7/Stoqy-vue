<?php

namespace App\Http\Controllers;

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

public function exportStok()
{
    return Excel::download(new StockExport, 'laporan_stok.xlsx');
}

public function stok(Request $request)
{
    $categories = Category::all();
    
    $startDate = $request->input('start_date', now()->subMonth()->toDateString());
    $endDate   = $request->input('end_date', now()->toDateString());

    $stok = Product::select(
        'products.id', 
        'products.name', 
        'products.category_id',
        'products.initial_stock',
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
       SUM(CASE WHEN type = "Masuk" AND status = "Diterima" AND transaction_date BETWEEN "'.$startDate.'" AND "'.$endDate.'" THEN quantity ELSE 0 END) as total_masuk,
       SUM(CASE WHEN type = "Keluar" AND status = "Diterima" AND transaction_date BETWEEN "'.$startDate.'" AND "'.$endDate.'" THEN quantity ELSE 0 END) as total_keluar
       FROM stock_transactions GROUP BY product_id) as transaksi'), 'products.id', '=', 'transaksi.product_id')
       ->leftJoin(DB::raw('(SELECT product_id, 
       SUM(CASE WHEN difference > 0 THEN difference ELSE 0 END) as stock_opname_masuk,
       SUM(CASE WHEN difference < 0 THEN ABS(difference) ELSE 0 END) as stock_opname_keluar
       FROM stock_opnames 
       GROUP BY product_id) as stock_opnames'), 'products.id', '=', 'stock_opnames.product_id')
   
    ->orderBy('products.category_id')
    ->paginate(10);

    return view('laporan.stok', compact('stok', 'categories', 'startDate', 'endDate'));
}


    public function aktivitas(Request $request)
{
    // Default to one month ago and today
    $startDate = $request->input('tanggal_mulai', now()->subMonth()->toDateString());
    $endDate = $request->input('tanggal_akhir', now()->toDateString());

    $query = ActivityLog::with('user'); // Make sure 'user' relationship exists in ActivityLog

    // Apply filters if dates are provided
    if ($startDate) {
        $query->whereDate('created_at', '>=', $startDate);
    }
    if ($endDate) {
        $query->whereDate('created_at', '<=', $endDate);
    }

    // Fetch activity logs
    $aktivitas = $query->orderBy('created_at', 'desc')->paginate(10);

    // Pass data to the Blade view
    return view('laporan.aktivitas', compact('aktivitas', 'startDate', 'endDate'));
}

public function destroy($id)
{
    $log = ActivityLog::findOrFail($id);

    $log->delete();
    return redirect()->route('laporan.aktivitas')->with('success', 'Log aktivitas berhasil dihapus.');
}

}