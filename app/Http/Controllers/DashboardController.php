<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\UserService;
use Illuminate\Http\Request;

class DashboardController extends Controller 
{
    protected $dashboardService;
    protected $userService;
    
    public function __construct(DashboardService $dashboardService, UserService $userService)
    {
        $this->dashboardService = $dashboardService;
        $this->userService = $userService;
    }
    
    public function index(Request $request)
    {
        $user = auth()->user();
        $viewData = $this->dashboardService->getDashboardData($request, $user);
        
        return view('dashboard', $viewData);
    }
}