<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection; // Make sure this is imported if used
use Illuminate\Pagination\LengthAwarePaginator; // Make sure this is imported
use Carbon\Carbon;

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
    public function getTopProductsByStock(int $limit): Collection;

    // Stock Transaction related methods
    public function getIncomingTransactionsCountInPeriod(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate): int; // Added Carbon type hints
    public function getOutgoingTransactionsCountInPeriod(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate): int; // Added Carbon type hints
    public function getTransactionsCountByDate(string $date, string $type, string $status): int;
    public function getTodayIncomingTransactionsCount(): int;
    public function getTodayOutgoingTransactionsCount(): int;
    public function getManagerPendingIncomingTransactions(int $limit): Collection;
    public function getManagerPendingOutgoingTransactions(int $limit): Collection;

    // **IMPORTANT: Update these methods**
    public function getIncomingTasksPaginated(int $perPage, int $page = 1, string $pageName = 'page'): LengthAwarePaginator;
    public function getOutgoingTasksPaginated(int $perPage, int $page = 1, string $pageName = 'page'): LengthAwarePaginator;
    public function getCompletedTasksPaginated(int $perPage, int $page = 1, string $pageName = 'page'): LengthAwarePaginator;

    // Activity Log related methods
    public function getTodayActivitiesPaginated(int $perPage): LengthAwarePaginator;
}