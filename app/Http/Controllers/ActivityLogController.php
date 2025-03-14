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
        // Definisikan tanggal awal dan akhir (opsional untuk filter)
        $startDate = $request->input('tanggal_mulai', now()->startOfMonth()->toDateString());
        $endDate = $request->input('tanggal_akhir', now()->toDateString());

        // Ambil data aktivitas log
        $aktivitas = $this->activityLogService->getActivityLogs($startDate, $endDate);

        // Pastikan semua variabel diteruskan ke view
        return view('activities.index', compact('aktivitas', 'startDate', 'endDate'));
    }
}
