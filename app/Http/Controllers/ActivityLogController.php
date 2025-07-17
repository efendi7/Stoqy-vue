<?php
namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

    // Kirim data sebagai props ke komponen Vue 'ActivityLog/Index'
    return Inertia::render('ActivityLog/Index', [
        'aktivitas' => $aktivitas,
        'filters' => $request->only(['search', 'tanggal_mulai', 'tanggal_akhir']), // Kirim filter
    ]);
}


   public function deleteAllLogs()
    {
        // 2. TAMBAHKAN LOGIKA INI
        // Truncate adalah cara tercepat untuk mengosongkan tabel.
        ActivityLog::truncate();

        // 3. KEMBALIKAN DENGAN PESAN SUKSES
        return redirect()->route('laporan.aktivitas.index')
                         ->with('success', 'Semua log aktivitas berhasil dihapus.');
    }

    public function destroy($id)
{
    $this->activityLogService->deleteLogById($id);

    return redirect()->route('laporan.aktivitas.index')->with('success', 'Log aktivitas berhasil dihapus.');
}

public function getApiLogs(Request $request)
{
    $search = $request->input('search');
    $startDate = $request->input('tanggal_mulai', now()->startOfMonth()->toDateString());
    $endDate = $request->input('tanggal_akhir', now()->toDateString());

    $activities = $this->activityLogService->getAllLogs($search, $startDate, $endDate);

    $paginated = $activities->appends($request->query());

    return response()->json([
        'data' => $paginated->items(),
        'meta' => [
            'current_page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
            'prev_page_url' => $paginated->previousPageUrl(),
            'next_page_url' => $paginated->nextPageUrl(),
        ]
    ]);
}

}
