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

    public function getActivityLogs($filters)
    {
        return $this->activityLogRepository->getActivityLogs($filters);
    }
}
