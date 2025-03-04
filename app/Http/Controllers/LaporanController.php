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
        $query = Product::select(
            'products.id', 
            'products.name', 
            'products.category_id',
            'products.initial_stock',
            DB::raw('COALESCE(transaksi.total_masuk, 0) as barang_masuk'),
            DB::raw('COALESCE(transaksi.total_keluar, 0) as barang_keluar'),
            DB::raw('COALESCE(products.stock, 0) + COALESCE(transaksi.total_masuk, 0) - COALESCE(transaksi.total_keluar, 0) as stok_akhir')
        )
        ->with('category')
        ->leftJoin(DB::raw('(SELECT product_id, 
            SUM(CASE WHEN type = "Masuk" AND status = "Diterima" THEN quantity ELSE 0 END) as total_masuk,
            SUM(CASE WHEN type = "Keluar" AND status = "Diterima" THEN quantity ELSE 0 END) as total_keluar
            FROM stock_transactions GROUP BY product_id) as transaksi'), 'products.id', '=', 'transaksi.product_id');
    
        // **Filter berdasarkan kategori**
        if ($request->has('category') && $request->category != '') {
            $query->where('products.category_id', $request->category);
        }
    
        // **Filter berdasarkan periode**
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('products.created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('products.created_at', '<=', $request->end_date);
        }
    
        $stok = $query->paginate(10);
        $categories = Category::all();
    
        return view('laporan.stok', compact('stok', 'categories'));
    }
    
public function exportStok()
{
    return Excel::download(new StockExport, 'laporan_stok.xlsx');
}

public function stok(Request $request)
{
    $categories = Category::all();
    
    // Set tanggal default: dari satu bulan yang lalu hingga hari ini
    $startDate = $request->input('start_date', now()->subMonth()->toDateString());
    $endDate   = $request->input('end_date', now()->toDateString());

    $stok = Product::select(
        'products.id', 
        'products.name', 
        'products.category_id',
        'products.initial_stock',
        DB::raw('0
            + (
                SELECT COALESCE(SUM(CASE WHEN type = "Masuk" AND status = "Diterima" THEN quantity ELSE 0 END),0)
                FROM stock_transactions
                WHERE product_id = products.id
                AND transaction_date < "'.$startDate.'"
            )
            - (
                SELECT COALESCE(SUM(CASE WHEN type = "Keluar" AND status = "Diterima" THEN quantity ELSE 0 END),0)
                FROM stock_transactions
                WHERE product_id = products.id
                AND transaction_date < "'.$startDate.'"
            ) as stok_awal'),
        DB::raw('COALESCE(transaksi.total_masuk, 0) as barang_masuk'),
        DB::raw('COALESCE(transaksi.total_keluar, 0) as barang_keluar'),
        DB::raw('(0
            + (
                SELECT COALESCE(SUM(CASE WHEN type = "Masuk" AND status = "Diterima" THEN quantity ELSE 0 END),0)
                FROM stock_transactions
                WHERE product_id = products.id
                AND transaction_date < "'.$startDate.'"
            )
            - (
                SELECT COALESCE(SUM(CASE WHEN type = "Keluar" AND status = "Diterima" THEN quantity ELSE 0 END),0)
                FROM stock_transactions
                WHERE product_id = products.id
                AND transaction_date < "'.$startDate.'"
            )
            + COALESCE(transaksi.total_masuk, 0)
            - COALESCE(transaksi.total_keluar, 0)) as stok_akhir')
    )
    ->with('category')
    ->leftJoin(DB::raw('(SELECT product_id, 
       SUM(CASE WHEN type = "Masuk" AND status = "Diterima" AND transaction_date BETWEEN "'.$startDate.'" AND "'.$endDate.'" THEN quantity ELSE 0 END) as total_masuk,
       SUM(CASE WHEN type = "Keluar" AND status = "Diterima" AND transaction_date BETWEEN "'.$startDate.'" AND "'.$endDate.'" THEN quantity ELSE 0 END) as total_keluar
       FROM stock_transactions GROUP BY product_id) as transaksi'), 'products.id', '=', 'transaksi.product_id')
    ->orderBy('products.category_id')
    ->paginate(10);

    return view('laporan.stok', compact('stok', 'categories', 'startDate', 'endDate'));
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
