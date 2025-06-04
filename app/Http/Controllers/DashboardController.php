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
        $user = Auth::user(); // Get the authenticated user
        
        // Get all necessary data and the determined view path from the service
        $viewData = $this->dashboardService->getDashboardData($request, $user);

        // Extract the determined view path from the data
        $dashboardView = $viewData['dashboardView'] ?? 'dashboard.default'; // Fallback to a default view

        // Remove the view path from viewData before passing it to the view
        unset($viewData['dashboardView']);

        // Pass the remaining data to the determined view
        return view($dashboardView, $viewData);
    }
}