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
    $search = $request->input('search');
    $startDate = $request->input('tanggal_mulai', now()->startOfMonth()->toDateString());
    $endDate = $request->input('tanggal_akhir', now()->toDateString());

    $aktivitas = $this->activityLogService->getAllLogs($search, $startDate, $endDate);

    return view('activities.index', compact('aktivitas', 'search', 'startDate', 'endDate'));
}


    public function deleteAllLogs()
    {
        $this->activityLogService->deleteAllActivityLogs();
        return redirect()->route('laporan.aktivitas.index')->with('success', 'Semua log aktivitas berhasil dihapus.');

    }

    public function destroy($id)
{
    $this->activityLogService->deleteLogById($id);

    return redirect()->route('laporan.aktivitas.index')->with('success', 'Log aktivitas berhasil dihapus.');
}

}
