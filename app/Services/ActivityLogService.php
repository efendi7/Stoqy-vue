<?php
namespace App\Services;

use App\Models\ActivityLog;
use Carbon\Carbon;

class ActivityLogService
{
    public function getActivityLogs($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        return ActivityLog::whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Menampilkan 10 data per halaman
    }

    public function deleteActivityLog($id)
    {
        $activityLog = ActivityLog::find($id);

        if (!$activityLog) {
            return false;
        }

        $activityLog->delete();
        return true;
    }
}
