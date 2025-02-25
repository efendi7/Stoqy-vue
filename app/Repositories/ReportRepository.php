<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ReportRepository
{
    // ✅ Ambil Laporan Stok Barang
    public function getStockReport($startDate, $endDate, $categoryId = null)
    {
        $query = DB::table('stock_transactions')  // Ganti 'stocks' menjadi 'stock_transactions'
            ->select('products.name', 'categories.name as category', 'stock_transactions.quantity', 'stock_transactions.updated_at') // Ganti 'stocks' menjadi 'stock_transactions'
            ->join('products', 'stock_transactions.product_id', '=', 'products.id') // Ganti 'stocks' menjadi 'stock_transactions'
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('stock_transactions.updated_at', [$startDate, $endDate]); // Ganti 'stocks' menjadi 'stock_transactions'

        if ($categoryId) {
            $query->where('categories.id', $categoryId);
        }

        return $query->orderBy('stock_transactions.updated_at', 'desc') // Ganti 'stocks' menjadi 'stock_transactions'
            ->get();
    }

    // ✅ Ambil Laporan Barang Masuk
    public function getIncomingTransactions($startDate, $endDate)
    {
        return DB::table('transactions')
            ->select('products.name', 'transactions.quantity', 'transactions.transaction_date')
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->where('transactions.type', 'incoming')
            ->whereBetween('transactions.transaction_date', [$startDate, $endDate])
            ->orderBy('transactions.transaction_date', 'desc')
            ->get();
    }

    // ✅ Ambil Laporan Barang Keluar
    public function getOutgoingTransactions($startDate, $endDate)
    {
        return DB::table('transactions')
            ->select('products.name', 'transactions.quantity', 'transactions.transaction_date')
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->where('transactions.type', 'outgoing')
            ->whereBetween('transactions.transaction_date', [$startDate, $endDate])
            ->orderBy('transactions.transaction_date', 'desc')
            ->get();
    }
}
