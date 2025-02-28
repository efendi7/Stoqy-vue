<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\User;
use DB;

class LaporanController extends Controller
{
    public function stok()
    {
        $stok = Product::select('name', 'stock', 'category_id')
            ->with('category')
            ->orderBy('category_id')
            ->get();

        return view('laporan.stok', compact('stok'));
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
