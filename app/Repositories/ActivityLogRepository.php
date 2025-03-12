<?php
namespace App\Repositories;
use \App\Interfaces\ActivityLogRepositoryInterface;
use App\Models\ActivityLog;

class ActivityLogRepository implements ActivityLogRepositoryInterface
{
    public function getActivityLogs($filters)
    {
        return ActivityLog::with('user')
            ->when(!empty($filters['start_date']), function ($query) use ($filters) {
                return $query->whereDate('created_at', '>=', $filters['start_date']);
            })
            ->when(!empty($filters['end_date']), function ($query) use ($filters) {
                return $query->whereDate('created_at', '<=', $filters['end_date']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
}
