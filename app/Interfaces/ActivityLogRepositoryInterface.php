<?php
namespace App\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface ActivityLogRepositoryInterface
{
    public function getAll(?string $search = null, ?string $fromDate = null, ?string $toDate = null, int $perPage = 10): LengthAwarePaginator;
    public function getByUser(int $userId, int $perPage = 10): LengthAwarePaginator;
    public function deleteById($id): bool;
    public function deleteAllLogs(): bool;
    public function getTodayActivities($limit = 10);
}
