<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // DashboardService akan menggunakan $request untuk memfilter data,
        // termasuk berdasarkan 'transaction_status' yang baru.
        $viewData = $this->dashboardService->getDashboardData($request, $user);
        
        // FIX: Pastikan prop untuk status yang dipilih dikirim kembali ke view.
        // Ini memungkinkan dropdown untuk menampilkan state yang benar setelah filter.
        $viewData['selectedTransactionStatus'] = $request->input('transaction_status', 'all');

        $component = match ($user->role) {
            'admin' => 'Dashboard/AdminDashboard',
            'warehouse_manager' => 'Dashboard/WarehouseManagerDashboard',
            'warehouse_staff' => 'Dashboard/WarehouseStaffDashboard',
            default => 'Dashboard/AdminDashboard',
        };

        return Inertia::render($component, $viewData);
    }
}
