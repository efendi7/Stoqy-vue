<?php
namespace App\Repositories;

use App\Models\ActivityLog;
use App\Interfaces\ActivityLogRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityLogRepository implements ActivityLogRepositoryInterface
{
    protected $model;

    public function __construct(ActivityLog $model)
    {
        $this->model = $model;
    }

    public function getAll(string $search = null, string $fromDate = null, string $toDate = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if ($search) {
            $query->where('activity', 'LIKE', "%{$search}%");
        }

        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getByUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)->latest()->paginate($perPage);
    }

    public function deleteAllLogs(): bool
    {
        return (bool) $this->model->query()->delete();
    }
}