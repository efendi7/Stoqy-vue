<?php
namespace App\Repositories;

use App\Models\ActivityLog;
use App\Interfaces\ActivityLogRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class ActivityLogRepository implements ActivityLogRepositoryInterface
{
    protected $model;

    public function __construct(ActivityLog $model)
    {
        $this->model = $model;
    }

    public function getAll(string $search = null, string $fromDate = null, string $toDate = null, int $perPage = 10): LengthAwarePaginator
{
    $query = $this->model->newQuery()->with('user'); // ✅ Load relasi user

    if ($search) {
        $query->where('action', 'LIKE', "%{$search}%"); // 🛠️ kolom seharusnya 'action' bukan 'activity'
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

    public function deleteById($id): bool
{
    $log = ActivityLog::findOrFail($id);
    return $log->delete();
}

 public function getTodayActivities($limit = 10)
    {
        return Activity::with('user')
            ->whereDate('created_at', now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

}