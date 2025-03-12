<?php
namespace App\Repositories;

use App\Models\Product;
use App\Interfaces\StockReportRepositoryInterface;
use Illuminate\Support\Facades\DB;

class StockReportRepository implements StockReportRepositoryInterface
{
    public function getStockReport($filters)
    {
        $startDate = $filters['start_date'] ?? now()->subMonth()->toDateString();
        $endDate = $filters['end_date'] ?? now()->toDateString();

        return Product::select(
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
        ->leftJoin(DB::raw('(SELECT product_id, 
                SUM(CASE WHEN type = "Masuk" AND status = "Diterima" THEN quantity ELSE 0 END) as total_masuk,
                SUM(CASE WHEN type = "Keluar" AND status = "Diterima" THEN quantity ELSE 0 END) as total_keluar
                FROM stock_transactions GROUP BY product_id) as transaksi'), 'products.id', '=', 'transaksi.product_id')
        ->leftJoin(DB::raw('(SELECT product_id, 
                SUM(CASE WHEN difference > 0 THEN difference ELSE 0 END) as stock_opname_masuk,
                SUM(CASE WHEN difference < 0 THEN ABS(difference) ELSE 0 END) as stock_opname_keluar
                FROM stock_opnames 
                GROUP BY product_id) as stock_opnames'), 'products.id', '=', 'stock_opnames.product_id')
        ->when(!empty($filters['category']), function ($query) use ($filters) {
            return $query->where('products.category_id', $filters['category']);
        })
        ->paginate(10);
    }
}
