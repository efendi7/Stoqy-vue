<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        
        
        $viewData = $this->dashboardService->getDashboardData($request, $user);

        $dashboardView = $viewData['dashboardView'];
        unset($viewData['dashboardView']);

        return view($dashboardView, $viewData);
    }
}