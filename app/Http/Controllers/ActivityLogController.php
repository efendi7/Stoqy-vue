<?php
namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $activities = $this->activityLogService->getActivities($request->search);

        return view('activities.index', compact('activities'));
    }

    public function userActivities($userId)
    {
        $activities = $this->activityLogService->getUserActivities($userId);

        return view('activities.index', compact('activities'));
    }
}
