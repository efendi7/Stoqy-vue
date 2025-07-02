<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockTransaction;
use App\Models\ActivityLog;
use App\Interfaces\DashboardRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardRepositoryInterface
{
    protected $userModel;
    protected $productModel;
    protected $supplierModel;
    protected $transactionModel;
    protected $activityLogModel;

    public function __construct(User $userModel, Product $productModel, Supplier $supplierModel, StockTransaction $transactionModel, ActivityLog $activityLogModel)
    {
        $this->userModel = $userModel;
        $this->productModel = $productModel;
        $this->supplierModel = $supplierModel;
        $this->transactionModel = $transactionModel;
        $this->activityLogModel = $activityLogModel;
    }

    // User related methods
    public function getUserCount(): int
    {
        return $this->userModel->count();
    }

    public function getActiveUsersCount(): int
    {
        return $this->userModel->where('is_logged_in', true)->count();
    }

    // Supplier related methods
    public function getSupplierCount(): int
    {
        return $this->supplierModel->count();
    }

    // Product related methods
    public function getProductCount(): int
    {
        return $this->productModel->count();
    }

    public function getLowStockCount(): int
    {
        return $this->productModel->whereColumn('stock', '<', 'minimum_stock')->count();
    }

    public function getAvailableStockCount(): int
    {
        return $this->productModel->whereColumn('stock', '>=', 'minimum_stock')->where('stock', '>', 0)->count();
    }

    public function getOutOfStockCount(): int
    {
        return $this->productModel->where('stock', 0)->count();
    }

    public function getProductsForChart(string $categoryId = 'all', string $sortOrder = 'desc', int $limit = 10): Collection
    {
        return $this->productModel
            ->query()
            // Terapkan filter kategori jika bukan 'all'
            ->when($categoryId !== 'all', function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })
            // PERBAIKAN: Tambahkan validasi untuk data yang valid
            ->whereNotNull('name')
            ->where('name', '!=', '')
            ->whereNotNull('stock')
            ->where('stock', '>=', 0)
            ->orderBy('stock', $sortOrder)
            ->limit($limit)
            ->get(['id', 'name', 'stock'])
            // PERBAIKAN: Filter hasil setelah query untuk validasi tambahan
            ->filter(function ($product) {
                return !empty(trim($product->name)) && is_numeric($product->stock) && $product->stock >= 0;
            })
            // PERBAIKAN: Reindex collection untuk memastikan index berurutan
            ->values();
    }

    public function getAllCategories(): Collection
    {
        // Asumsi Anda memiliki model Category
        return Category::orderBy('name')->get();
    }

    // Stock Transaction related methods
    public function getIncomingTransactionsCountInPeriod(Carbon $startDate, Carbon $endDate): int
    {
        return $this->transactionModel
            ->where('type', 'Masuk')
            ->where('status', 'Diterima')
            ->whereBetween('transaction_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->count();
    }

    public function getOutgoingTransactionsCountInPeriod(Carbon $startDate, Carbon $endDate): int
    {
        return $this->transactionModel
            ->where('type', 'Keluar')
            ->where('status', 'Diterima')
            ->whereBetween('transaction_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->count();
    }

    /**
     * [BARU & OPTIMAL] Mengambil data transaksi yang dikelompokkan per hari untuk chart.
     * Ini menggantikan N+1 query dengan satu query tunggal yang efisien.
     */
    public function getTransactionCountsGroupedByDate(Carbon $startDate, Carbon $endDate): Collection
    {
        return $this->transactionModel
            ->select(DB::raw('DATE(transaction_date) as date'), DB::raw("SUM(CASE WHEN type = 'Masuk' THEN 1 ELSE 0 END) as incoming_count"), DB::raw("SUM(CASE WHEN type = 'Keluar' THEN 1 ELSE 0 END) as outgoing_count"))
            ->where('status', 'Diterima')
            ->whereBetween('transaction_date', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
    }

    public function getTodayIncomingTransactionsCount(): int
    {
        return $this->transactionModel->whereDate('transaction_date', Carbon::today())->where('type', 'Masuk')->where('status', 'Diterima')->count();
    }

    public function getTodayOutgoingTransactionsCount(): int
    {
        return $this->transactionModel->whereDate('transaction_date', Carbon::today())->where('type', 'Keluar')->where('status', 'Diterima')->count();
    }

    public function getManagerPendingIncomingTransactions(int $limit): Collection
    {
        return $this->transactionModel->where('type', 'Masuk')->where('status', 'Confirmed')->with('product')->latest()->limit($limit)->get();
    }

    public function getManagerPendingOutgoingTransactions(int $limit): Collection
    {
        return $this->transactionModel->where('type', 'Keluar')->where('status', 'Confirmed')->with('product')->latest()->limit($limit)->get();
    }

    public function getIncomingTasksPaginated(int $perPage, int $page = 1, string $pageName = 'page'): LengthAwarePaginator
    {
        return $this->transactionModel
            ->where('type', 'Masuk')
            ->where('status', 'Pending')
            ->with('product')
            ->latest()
            ->paginate($perPage, ['*'], $pageName, $page);
    }

    public function getOutgoingTasksPaginated(int $perPage, int $page = 1, string $pageName = 'page'): LengthAwarePaginator
    {
        return $this->transactionModel
            ->where('type', 'Keluar')
            ->where('status', 'Pending')
            ->with('product')
            ->latest()
            ->paginate($perPage, ['*'], $pageName, $page);
    }

    public function getCompletedTasksPaginated(int $perPage, int $page = 1, string $pageName = 'page'): LengthAwarePaginator
    {
        return $this->transactionModel
            ->whereIn('status', ['Diterima', 'Ditolak', 'Confirmed'])
            ->latest()
            ->with('product')
            ->paginate($perPage, ['*'], $pageName, $page);
    }

    public function getTodayActivitiesPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->activityLogModel->with('user')->whereDate('created_at', Carbon::today())->orderByDesc('created_at')->paginate($perPage);
    }
}
