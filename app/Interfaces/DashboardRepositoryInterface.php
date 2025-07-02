<?php

namespace App\Interfaces;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface DashboardRepositoryInterface
{
    // User related methods
    public function getUserCount(): int;
    public function getActiveUsersCount(): int;

    // Supplier related methods
    public function getSupplierCount(): int;

    // Product related methods
    public function getProductCount(): int;
    public function getLowStockCount(): int;
    public function getAvailableStockCount(): int;
    public function getOutOfStockCount(): int;
    public function getProductsForChart(string $categoryId = 'all', string $sortOrder = 'desc', int $limit = 10): Collection;
    public function getAllCategories(): Collection;

    // Stock Transaction related methods
    public function getIncomingTransactionsCountInPeriod(Carbon $startDate, Carbon $endDate): int;
    public function getOutgoingTransactionsCountInPeriod(Carbon $startDate, Carbon $endDate): int;

    // --- PERUBAHAN DI SINI ---
    // Hapus atau ganti baris ini:
    // public function getTransactionsCountByDate(string $date, string $type, string $status): int;

    // Dengan baris ini:
    public function getTransactionCountsGroupedByDate(Carbon $startDate, Carbon $endDate): Collection;
    // --- AKHIR PERUBAHAN ---

    public function getTodayIncomingTransactionsCount(): int;
    public function getTodayOutgoingTransactionsCount(): int;
    public function getManagerPendingIncomingTransactions(int $limit): Collection;
    public function getManagerPendingOutgoingTransactions(int $limit): Collection;
    public function getIncomingTasksPaginated(int $perPage, int $page = 1, string $pageName = 'page'): LengthAwarePaginator;
    public function getOutgoingTasksPaginated(int $perPage, int $page = 1, string $pageName = 'page'): LengthAwarePaginator;
    public function getCompletedTasksPaginated(int $perPage, int $page = 1, string $pageName = 'page'): LengthAwarePaginator;

    // Activity Log methods
    public function getTodayActivitiesPaginated(int $perPage): LengthAwarePaginator;
}
