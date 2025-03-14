<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

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
    public function getIncomingTransactionsCount(): int;
    public function getOutgoingTransactionsCount(): int;
    public function getTransactionsCountByDate(string $date, string $type, string $status): int;
    public function getTodayIncomingTransactionsCount(): int;
    public function getTodayOutgoingTransactionsCount(): int;
    public function getPendingIncomingTransactions(int $limit): Collection;
    public function getPendingOutgoingTransactions(int $limit): Collection;
    public function getIncomingTasksPaginated(int $perPage): \Illuminate\Pagination\LengthAwarePaginator;
    public function getOutgoingTasksPaginated(int $perPage): \Illuminate\Pagination\LengthAwarePaginator;
    public function getCompletedTasksPaginated(int $perPage): \Illuminate\Pagination\LengthAwarePaginator;
    
    // Activity Log related methods
    public function getTodayActivitiesPaginated(int $perPage): \Illuminate\Pagination\LengthAwarePaginator;
}