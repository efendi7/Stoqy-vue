<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockTransaction;
use App\Models\ActivityLog;
use App\Interfaces\DashboardRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardRepository implements DashboardRepositoryInterface
{
    protected $userModel;
    protected $productModel;
    protected $supplierModel;
    protected $transactionModel;
    protected $activityLogModel;

    public function __construct(
        User $userModel,
        Product $productModel,
        Supplier $supplierModel,
        StockTransaction $transactionModel,
        ActivityLog $activityLogModel
    ) {
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
        // Assuming 'is_logged_in' correctly reflects active users. You might also check 'last_activity_at'
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
        // Available means stock >= minimum_stock and stock > 0
        return $this->productModel->whereColumn('stock', '>=', 'minimum_stock')->where('stock', '>', 0)->count();
    }

    public function getOutOfStockCount(): int
    {
        return $this->productModel->where('stock', 0)->count();
    }

    public function getTopProductsByStock(int $limit): Collection
    {
        return $this->productModel->orderByDesc('stock')->limit($limit)->get(['id', 'name', 'stock']); // Added 'id' if needed
    }

    // Stock Transaction related methods
    public function getIncomingTransactionsCountInPeriod(Carbon $startDate, Carbon $endDate): int
    {
        return $this->transactionModel->where('type', 'Masuk')
            ->where('status', 'Diterima') // Only count accepted transactions
            ->whereBetween('transaction_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->count();
    }

    public function getOutgoingTransactionsCountInPeriod(Carbon $startDate, Carbon $endDate): int
    {
        return $this->transactionModel->where('type', 'Keluar')
            ->where('status', 'Diterima') // Only count accepted transactions
            ->whereBetween('transaction_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->count();
    }

     public function getTransactionsCountByDate(string $date, string $type, string $status): int
    {
        return $this->transactionModel->whereDate('transaction_date', $date)
            ->where('type', $type)
            ->where('status', $status)
            ->count();
    }

    public function getTodayIncomingTransactionsCount(): int // <--- This implementation
    {
        return $this->getTransactionsCountByDate(Carbon::now()->format('Y-m-d'), 'Masuk', 'Diterima');
    }

    public function getTodayOutgoingTransactionsCount(): int
    {
        return $this->getTransactionsCountByDate(Carbon::now()->format('Y-m-d'), 'Keluar', 'Diterima');
    }

    // For Warehouse Manager: pending transactions that need manager approval (e.g., status 'Confirmed' by staff, awaiting manager 'Diterima'/'Ditolak')
    public function getManagerPendingIncomingTransactions(int $limit): Collection
    {
        return $this->transactionModel->where('type', 'Masuk')
            ->where('status', 'Confirmed') // Assuming 'Confirmed' means awaiting manager decision
            ->with('product') // Eager load product for display
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getManagerPendingOutgoingTransactions(int $limit): Collection
    {
        return $this->transactionModel->where('type', 'Keluar')
            ->where('status', 'Confirmed') // Assuming 'Confirmed' means awaiting manager decision
            ->with('product') // Eager load product for display
            ->latest()
            ->limit($limit)
            ->get();
    }

      public function getIncomingTasksPaginated(int $perPage, int $page = 1, string $pageName = 'page'): LengthAwarePaginator
    {
        return $this->transactionModel->where('type', 'Masuk')
            ->where('status', 'Pending') // 'Pending' for staff to process
            ->with('product') // Eager load product for display
            ->latest()
            ->paginate($perPage, ['*'], $pageName, $page);
    }

    public function getOutgoingTasksPaginated(int $perPage, int $page = 1, string $pageName = 'page'): LengthAwarePaginator
    {
        return $this->transactionModel->where('type', 'Keluar')
            ->where('status', 'Pending') // 'Pending' for staff to process
            ->with('product') // Eager load product for display
            ->latest()
            ->paginate($perPage, ['*'], $pageName, $page);
    }

    public function getCompletedTasksPaginated(int $perPage, int $page = 1, string $pageName = 'page'): LengthAwarePaginator
    {
        return $this->transactionModel->whereIn('status', ['Diterima', 'Ditolak', 'Confirmed'])
            ->latest()
            ->with('product') // Eager load product for display
            ->paginate($perPage, ['*'], $pageName, $page);
    }

     public function getTodayActivitiesPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->activityLogModel->with('user')
            ->whereDate('created_at', Carbon::today())
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}