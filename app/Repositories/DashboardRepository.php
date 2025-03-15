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
        return $this->productModel->whereColumn('stock', '>=', 'minimum_stock')->count();
    }

    public function getOutOfStockCount(): int
    {
        return $this->productModel->where('stock', 0)->count();
    }

    public function getTopProductsByStock(int $limit): Collection
    {
        return $this->productModel->orderByDesc('stock')->limit($limit)->get(['name', 'stock']);
    }

    // Stock Transaction related methods
    public function getIncomingTransactionsCount(): int
    {
        return $this->transactionModel->where('type', 'Masuk')
            ->where('status', 'Diterima')
            ->count();
    }

    public function getOutgoingTransactionsCount(): int
    {
        return $this->transactionModel->where('type', 'Keluar')
            ->where('status', 'Diterima')
            ->count();
    }

    public function getTransactionsCountByDate(string $date, string $type, string $status): int
    {
        return $this->transactionModel->whereDate('transaction_date', $date)
            ->where('type', $type)
            ->where('status', $status)
            ->count();
    }

    public function getTodayIncomingTransactionsCount(): int
    {
        return $this->getTransactionsCountByDate(Carbon::now()->format('Y-m-d'), 'Masuk', 'Diterima');
    }

    public function getTodayOutgoingTransactionsCount(): int
    {
        return $this->getTransactionsCountByDate(Carbon::now()->format('Y-m-d'), 'Keluar', 'Diterima');
    }

    public function getPendingIncomingTransactions(int $limit): Collection
    {
        return $this->transactionModel->where('type', 'Masuk')
            ->where('status', 'Pending')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getPendingOutgoingTransactions(int $limit): Collection
    {
        return $this->transactionModel->where('type', 'Keluar')
            ->where('status', 'Pending')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getIncomingTasksPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->transactionModel->where('type', 'Masuk')
            ->where('status', 'Pending')
            ->latest()
            ->paginate($perPage);
    }

    public function getOutgoingTasksPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->transactionModel->where('type', 'Keluar')
            ->where('status', 'Pending')
            ->latest()
            ->paginate($perPage);
    }

    public function getCompletedTasksPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->transactionModel->whereIn('status', ['Diterima', 'Ditolak', 'Confirmed'])
            ->latest()
            ->paginate($perPage);
    }

    // Activity Log related methods
    public function getTodayActivitiesPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->activityLogModel->with('user')
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->paginate($perPage);
    }


}