<?php

namespace App\Http\Controllers;

use App\Services\UserService; // Import UserService

use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService) // Inject UserService
    {
        $this->userService = $userService;
    }

    public function index()
    {
        // Get key metrics
        $totalProducts = Product::count();
        $lowStockItems = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        // Get transaction counts
        $incomingTransactions = StockTransaction::whereDate('transaction_date', Carbon::today())
            ->where('type', 'Masuk')
            ->count();
        $outgoingTransactions = StockTransaction::whereDate('transaction_date', Carbon::today())
            ->where('type', 'Keluar')
            ->count();
        $activeUsers = User::where('last_login_at', '>=', Carbon::now()->subDays(7))->count();

        $userRole = $this->userService->getUserRole(auth()->id()); // Get user role

        // Get data for charts
        $stockData = Product::orderBy('stock', 'desc')
            ->limit(10)
            ->pluck('stock', 'name')
            ->toArray();
            
        $transactionData = StockTransaction::selectRaw('DATE(transaction_date) as date, COUNT(*) as count')
            ->where('transaction_date', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Get recent activities
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Debug log chart data
        error_log('Stock Chart Data: ' . print_r([
            'labels' => array_keys($stockData),
            'data' => array_values($stockData)
        ], true));
        error_log('Transaction Chart Data: ' . print_r([
            'labels' => array_keys($transactionData),
            'data' => array_values($transactionData)
        ], true));
        
        \Log::debug('Stock Chart Data:', [
            'labels' => array_keys($stockData),
            'data' => array_values($stockData)
        ]);
        \Log::debug('Transaction Chart Data:', [
            'labels' => array_keys($transactionData),
            'data' => array_values($transactionData)
        ]);

        return view('dashboard', [ 
            'userRole' => $userRole, // Pass user role to the view
            'totalProducts' => $totalProducts,
            'lowStockItems' => $lowStockItems,
            'incomingTransactions' => $incomingTransactions,
            'outgoingTransactions' => $outgoingTransactions,
            'activeUsers' => $activeUsers,
            'stockLabels' => array_keys($stockData),
            'stockData' => array_values($stockData),
            'transactionLabels' => array_keys($transactionData),
            'transactionData' => array_values($transactionData),
            'recentActivities' => $recentActivities,
            'userRole' => auth()->user()->role // Add user role to the view
        ]);
    }
}
