<?php
namespace App\Services;

use App\Interfaces\ActivityLogRepositoryInterface;

class ActivityLogService
{
    protected $activityLogRepository;

    public function __construct(ActivityLogRepositoryInterface $activityLogRepository)
    {
        $this->activityLogRepository = $activityLogRepository;
    }

    public function getAllLogs($search = null)
    {
        return $this->activityLogRepository->getAll($search);
    }

    public function getLogsByUser($userId)
    {
        return $this->activityLogRepository->getByUser($userId);
    }

    public function deleteLogById($id): bool
{
    return $this->activityLogRepository->deleteById($id);
}


    public function deleteAllActivityLogs(): bool
    {
        return $this->activityLogRepository->deleteAllLogs();
    }
}
