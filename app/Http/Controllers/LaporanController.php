<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\User;
use App\Models\Category;
use DB;

class LaporanController extends Controller
{
    public function stokFilter(Request $request)
{
    $stok = Stock::query();

    if ($request->has('category')) {
        $stok->where('category_id', $request->category);
    }
    if ($request->has('start_date') && $request->has('end_date')) {
        $stok->whereBetween('created_at', [$request->start_date, $request->end_date]);
    }

    return view('laporan.stok', ['stok' => $stok->get()]);
}

public function exportStok()
{
    return Excel::download(new StockExport, 'laporan_stok.xlsx');
}

    public function stok()
{
    $categories = Category::all();

    $stok = Product::select(
        'products.id', 
        'products.name', 
        'products.category_id',
        'products.stock',
        DB::raw('COALESCE(transaksi.total_masuk, 0) as barang_masuk'),
        DB::raw('COALESCE(transaksi.total_keluar, 0) as barang_keluar'),
        DB::raw('COALESCE(products.stock, 0) + COALESCE(transaksi.total_masuk, 0) - COALESCE(transaksi.total_keluar, 0) as stok_akhir')
   )
   ->with('category')
   ->leftJoin(DB::raw('(SELECT product_id, 
       SUM(CASE WHEN type = "Masuk" AND status = "Diterima" THEN quantity ELSE 0 END) as total_masuk,
       SUM(CASE WHEN type = "Keluar" AND status = "Diterima" THEN quantity ELSE 0 END) as total_keluar
       FROM stock_transactions GROUP BY product_id) as transaksi'), 'products.id', '=', 'transaksi.product_id')
   ->orderBy('products.category_id')
   ->paginate(10);
   

    return view('laporan.stok', compact('stok', 'categories'));
}


    public function transaksi()
    {
        $transaksi = StockTransaction::select('product_id', 'quantity', 'transaction_type', 'created_at')
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('laporan.transaksi', compact('transaksi'));
    }

    public function aktivitas()
    {
        $aktivitas = DB::table('activity_logs')
            ->join('users', 'activity_logs.user_id', '=', 'users.id')
            ->select('users.name', 'activity_logs.action', 'activity_logs.created_at')
            ->orderBy('activity_logs.created_at', 'desc')
            ->get();

        return view('laporan.aktivitas', compact('aktivitas'));
    }
}
